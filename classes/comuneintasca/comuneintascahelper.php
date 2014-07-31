<?php

class ComuneInTascaHelper
{
    const ROOT_CLASSIDENTIFIER = "root_comuneintasca";
    
    const PROFILE_CLASSIDENTIFIER = "profilo_comuneintasca";

    const ITEM_CLASSIDENTIFIER = "item_comuneintasca";
    
    const ZONE_CLASSIDENTIFIER = "zona_comuneintasca";
    
    private static $_instance;
    
    protected $rootNode;
    
    protected $profiles;    
    
    protected function __construct()
    {        
    }
    
    public static function instance()
    {
        if ( !self::$_instance instanceof ComuneInTascaHelper )
        {
            self::$_instance = new ComuneInTascaHelper();
        }
        return self::$_instance;
    }
    
    public function rootNode( $createIfNotExists = true )
    {        
        $appSectionHelper = AppSectionHelper::instance();
        if ( $this->rootNode === null )
        {
            $params = array( 'Depth' => 1,
                             'DepthOperator' => 'eq',
                             'ClassFilterType' => 'include',
                             'ClassFilterArray' => array( self::ROOT_CLASSIDENTIFIER ),
                             'Limit' => 1 );
    
            if ( eZContentObjectTreeNode::subTreeCountByNodeID( $params, $appSectionHelper->rootNode()->attribute( 'node_id' ) ) )
            {
                $rootChildren = eZContentObjectTreeNode::subTreeByNodeID( $params, $appSectionHelper->rootNode()->attribute( 'node_id' ) );
                $this->rootNode = $rootChildren[0];
            }
            elseif( $createIfNotExists )
            {                
                $this->rootNode = self::createRootNode();
            }
        }
        return $this->rootNode;
    }
    
    public function profiles()
    {
        if ( $this->profiles === null )
        {
            $this->profiles = array();
            $params = array( 'Depth' => 1,
                             'DepthOperator' => 'eq',
                             'ClassFilterType' => 'include',
                             'ClassFilterArray' => array( self::PROFILE_CLASSIDENTIFIER ) );            
            $this->profiles = $this->rootNode()->subTree( $params );
        }
        return $this->profiles;
    }
    
    protected static function createRootNode()
    {
        $section = eZSection::fetchByIdentifier( "comuneintasca" );
        if ( !$section )
        {
            $section = new eZSection( array() );
            $section->setAttribute( 'name', "Comune in Tasca" );
            $section->setAttribute( 'identifier', "comuneintasca" );
            $section->setAttribute( 'navigation_part_identifier', AppSectionHelper::NAVIGATION_IDENTIFIER );
            $section->store();
        }
        
        $params = array(
            'parent_node_id' => AppSectionHelper::rootNode()->attribute( 'node_id' ),
            'section_id' => $section->attribute( 'id' ),
            'class_identifier' => self::ROOT_CLASSIDENTIFIER,
            'attributes' => array(
                'titolo' => 'Comune in Tasca'
            )
        );
        
        $contentObject = eZContentFunctions::createAndPublishObject( $params );
        if( !$contentObject )
        {
            throw new Exception( 'Failed creating Comune In Tasca root node' );
        }
        return $contentObject->attribute( 'main_node' );
    }
    
    public static function executeWorkflow( $process, $event )
    {
        $parameters = $process->attribute( 'parameter_list' );
        $objectId = $parameters['object_id'];
        $object = eZContentObject::fetch( $objectId );
        if ( $object instanceof eZContentObject )
        {
            if ( $object->attribute( 'class_identifier' ) == self::PROFILE_CLASSIDENTIFIER )
            {
                $profile = new ComuneInTascaProfile( $object );
                $profile->checkZones();
            }
        }
    }
    
}