<?php
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "Import profilo Comune in tasca" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions(
    '[json:][root:]',
    '',
    array(
        'json' => 'Absolute path to json profile file',
        'root' => 'Profile root node'
    )
);
$script->initialize();
$script->setUseDebugAccumulators( true );

$user = eZUser::fetchByName( 'admin' );
eZUser::setCurrentlyLoggedInUser( $user , $user->attribute( 'contentobject_id' ) );

$root = eZContentObjectTreeNode::fetch( $options['root']  );

$jsonData = json_decode( file_get_contents( $options['json'] ) );
try
{
    function importItem( $item, $parentNodeId )
    {
        global $cli;
        
        if ( !isset( $item->ref ) )
        {
            $contentOptions = new SQLIContentOptions( array(
                'class_identifier'      => 'item_comuneintasca',
                'remote_id'				=> 'profile_cit_' . $item->id,
                'language'              => 'ita-IT'
            ) );
            $content = SQLIContent::create( $contentOptions );
            $content->fields->title = $item->name->it;
            $content->fields['eng-GB']->title = $item->name->en;
            $content->fields['ger-DE']->title = $item->name->de;
            
            if ( isset( $item->image ) )
            {
                $content->fields->image = SQLIContentUtils::getRemoteFile( $item->image->it );
                $content->fields['eng-GB']->image = SQLIContentUtils::getRemoteFile( $item->image->it );
                $content->fields['ger-DE']->image = SQLIContentUtils::getRemoteFile( $item->image->it );
            }
            
            if ( isset( $item->objectIds ) )
            {
                $objectIds = array();
                foreach( $item->objectIds as $id )
                {
                    $related = eZContentObject::fetchByRemoteID( $id );
                    if ( $related instanceof eZContentObject )
                    {
                        $objectIds[] = $related->attribute( 'id' );
                    }
                }
                $objectIds = implode( '-', $objectIds );
                $content->fields->objects = $objectIds;
                $content->fields['eng-GB']->objects = $objectIds;
                $content->fields['ger-DE']->objects = $objectIds;
            }
            
            if ( isset( $item->query ) )
            {
                $query = $item->query->type;
                if ( isset( $item->query->classifications ) )
                {
                    foreach( $item->query->classifications as $classification )
                    {
                        foreach( get_object_vars( $classification ) as $property => $value )
                        {                
                            $query .= '::' . $property . '=' . $value;
                        }
                    }
                }
                $content->fields->query = $query;
                $content->fields['eng-GB']->query = $query;
                $content->fields['ger-DE']->query = $query;
            }
            
            $content->addLocation( SQLILocation::fromNodeID( $parentNodeId ) );
            $publisher = SQLIContentPublisher::getInstance();
            $publisher->publish( $content );
            $cli->output( ' |- ' . $content->name );
            $newParentNodeId = $content->main_node_id;
            unset( $content );
            
            if ( isset( $item->items ) )
            {
                foreach( $item->items as $subItem )
                importItem( $subItem, $newParentNodeId );
            }
        }        
    }
    
    function importData( $root, $zoneId, $data )
    {
        global $cli;
        
        $remoteId = $root->object()->attribute( 'remote_id' ) . '_' . $zoneId;
        $object = eZContentObject::fetchByRemoteID( $remoteId );
        if ( $object )
        {
            $cli->output( "Import {$object->attribute( 'name' )}" );
            $parentNodeId = $object->attribute( 'main_node_id' );
            foreach( $data as $item )
            {
                importItem( $item, $parentNodeId );
            }
        }
    }
    
    if ( $jsonData )
    {    
        //importData( $root, 'highlights', $jsonData->highlights );    
        importData( $root, 'navigationItems', $jsonData->navigationItems );    
        importData( $root, 'menu', $jsonData->menu );    
    }
}
catch( Exception $e )
{
    $errCode = $e->getCode();
    $errCode = $errCode != 0 ? $errCode : 1; // If an error has occured, script must terminate with a status other than 0
    $script->shutdown( $errCode, $e->getMessage() );
}