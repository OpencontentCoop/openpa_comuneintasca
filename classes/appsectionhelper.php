<?php

class AppSectionHelper
{
    private static $_instance;
    
    protected $rootNode;
    
    const ROOT_CLASSIDENTIFIER = "apps_container";
    
    const NAVIGATION_IDENTIFIER = "ezappsnavigationpart";
    const SECTION_IDENTIFIER = "apps";
    const SECTION_NAME = "App mobile";

    protected function __construct()
    {        
    }
    
    public static function instance()
    {
        if ( !self::$_instance instanceof AppSectionHelper )
        {
            self::$_instance = new AppSectionHelper();
        }
        return self::$_instance;
    }
    
    public function rootNode( $createIfNotExists = true )
    {        
        if ( $this->rootNode === null )
        {
            $params = array( 'Depth' => 1,
                             'DepthOperator' => 'eq',
                             'ClassFilterType' => 'include',
                             'ClassFilterArray' => array( self::ROOT_CLASSIDENTIFIER ),
                             'Limitation' => array(),
                             'Limit' => 1 );
    
            if ( eZContentObjectTreeNode::subTreeCountByNodeID( $params, 1 ) )
            {
                $rootChildren = eZContentObjectTreeNode::subTreeByNodeID( $params, 1 );
                $this->rootNode = $rootChildren[0];
            }
            elseif( $createIfNotExists )
            {
                $this->rootNode = self::createRootNode();
            }
        }
        return $this->rootNode;
    }
    
    protected static function createRootNode()
    {
        $section = eZSection::fetchByIdentifier( self::SECTION_IDENTIFIER );
        if ( !$section )
        {
            $section = new eZSection( array() );
            $section->setAttribute( 'name', self::SECTION_NAME );
            $section->setAttribute( 'identifier', self::SECTION_IDENTIFIER );
            $section->setAttribute( 'navigation_part_identifier', self::NAVIGATION_IDENTIFIER );
            $section->store();
        }
        
        $params = array(
            'parent_node_id' => 1,
            'section_id' => $section->attribute( 'id' ),
            'class_identifier' => self::ROOT_CLASSIDENTIFIER,
            'attributes' => array(
                'titolo' => 'Applicazioni mobile'
            )
        );
        
        $contentObject = eZContentFunctions::createAndPublishObject( $params );
        if( !$contentObject )
        {
            throw new Exception( 'Failed creating Apps root node' );
        }
        return $contentObject->attribute( 'main_node' );
    }
    
}
    