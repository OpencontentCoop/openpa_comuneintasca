<?php
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "Fix image import CIT" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions();
$script->initialize();
$script->setUseDebugAccumulators( true );

$user = eZUser::fetchByName( 'admin' );
eZUser::setCurrentlyLoggedInUser( $user , $user->attribute( 'contentobject_id' ) );

try
{
    
    ## fix titles
//    $language = 'eng-GB';
//    $zoneNode = eZContentObjectTreeNode::fetch(870367);
//    foreach( $zoneNode->subTree( array( 'Depth' => 1,
//                                        'DepthOperator' => 'eq',
//                                        'Language' => $language,
//                                        'SortBy' => $zoneNode->sortArray() ) ) as $node )
//    {
//        $cli->error( $node->attribute('name') );
//        $object = $node->attribute( 'object' );
//        $dataMap = $object->fetchDataMap( false, $language );
//        $title = $dataMap['title'];
//        
//        $db = eZDB::instance();
//        $db->begin();
//        
//        //$dataMap['title']->fromString( strtoupper( $dataMap['title']->toString() ) );
//        //$dataMap['title']->store();
//        
//        $class = $object->attribute( 'content_class' );
//        $name = $class->contentObjectName( $object, false, $language );
//        $object->setName( $name, false, $language );        
//		$object->store();
//        $nodes = $object->assignedNodes();
//        foreach ( $nodes as $node )
//        {
//            $node->setName( $object->attribute( 'name' ) );
//            $node->updateSubTreePath();
//        }
//        $db->commit();            
//        eZContentOperationCollection::registerSearchObject( $object->attribute( 'id' ) );        
//    }
    
    ## fix images
    //$data = array(
    //    "/home/httpd/openpa.ezcommunity/html/extension/openpa_comuneintasca/data/cultura.csv",
    //    "/home/httpd/openpa.ezcommunity/html/extension/openpa_comuneintasca/data/hotels.csv",    
    //    "/home/httpd/openpa.ezcommunity/html/extension/openpa_comuneintasca/data/itineraries.csv",
    //    "/home/httpd/openpa.ezcommunity/html/extension/openpa_comuneintasca/data/mainevents.csv",
    //    "/home/httpd/openpa.ezcommunity/html/extension/openpa_comuneintasca/data/restaurants.csv",
    //    "/home/httpd/openpa.ezcommunity/html/extension/openpa_comuneintasca/data/testi.csv",
    //    "/home/httpd/openpa.ezcommunity/html/extension/openpa_comuneintasca/data/turista.json"
    //);
    //
    //foreach( $data as $csvFile )
    //{
    //    $cli->warning( basename( $csvFile ) );
    //    
    //    $options = new SQLICSVOptions( array(
    //        'csv_path' => $csvFile,
    //        'delimiter' => ';',
    //        'enclosure'   => '"'
    //    ) );
    //    $csvDoc = new SQLICSVDoc( $options );        
    //    $csvDoc->parse();        
    //    foreach( $csvDoc->rows as $row )
    //    {
    //        $remote_id = $row->id;
    //        $fileImage = '/home/httpd/openpa.ezcommunity/html/extension/openpa_comuneintasca/data/images/' . $row->pictureUrl;
    //        $object = eZContentObject::fetchByRemoteID( $remote_id );
    //        //$dataMap = $object->attribute( 'data_map' );
    //        //$imageStringArray = explode( '|', $dataMap['image']->toString() );
    //        //$image = $imageStringArray[0];
    //        //$cli->error( $image );
    //        //if ( !file_exists( $image ) && file_exists( $fileImage ) )
    //        //{                
    //        //    $dataMap['image']->fromString( $fileImage );
    //        //    $dataMap['image']->store();
    //        //    $cli->error($dataMap['image']->toString());
    //        //}
    //        if ( !is_file( $fileImage ) )
    //        {
    //            $cli->error( $object->attribute( 'remote_id' ) );
    //            
    //            if ( $object instanceof eZContentObject )
    //            {
    //            
    //                //$dataMap = $object->fetchDataMap( false, 'ita-IT' );
    //                //$content = $dataMap['image']->attribute( 'content' );
    //                //$content->removeAliases( $dataMap['image'] );
    //                //
    //                //$dataMap = $object->fetchDataMap( false, 'eng-GB' );
    //                //$content = $dataMap['image']->attribute( 'content' );
    //                //$content->removeAliases( $dataMap['image'] );
    //                //
    //                //$dataMap = $object->fetchDataMap( false, 'ger-DE' );
    //                //$content = $dataMap['image']->attribute( 'content' );
    //                //$content->removeAliases( $dataMap['image'] );
    //            }
    //        }
    //    }
    //}
}
catch( Exception $e )
{
    $errCode = $e->getCode();
    $errCode = $errCode != 0 ? $errCode : 1; // If an error has occured, script must terminate with a status other than 0
    $script->shutdown( $errCode, $e->getMessage() );
}
eZExecution::cleanExit();