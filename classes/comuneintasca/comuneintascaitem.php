<?php

class ComuneInTascaItem
{
    const NAME_ATTRIBUTE_IDENTIFIER = 'title';
    
    const DESCRIPTION_ATTRIBUTE_IDENTIFIER = 'tooltip';
    
    const IMAGE_ATTRIBUTE_IDENTIFIER = 'image';
    
    const OBJECTIDS_ATTRIBUTE_IDENTIFIER = 'objects';
    
    const QUERY_ATTRIBUTE_IDENTIFIER = 'query';

    const APP_CLASS_IDENTIFIER = 'app_comuneintasca';

    private $propertiesMap = array(
        'id' => 'getId',
        'name' => 'getName',
        'description' => 'getDescription',
        'image' => 'getImage',
        'objectIds' => 'getObjectIds',       
        'query' => 'getQuery',
        'items' => 'getItems'
    );
    
    private $node;    
    
    private $object;

    private $dataMap;
    
    protected function attribute( $identifier )
    {
        if ( isset( $this->dataMap[$identifier] ) &&
             $this->dataMap[$identifier] instanceof eZContentObjectAttribute &&
             $this->dataMap[$identifier]->hasContent() )
        {
            return $this->dataMap[$identifier];
        }
        return false;
    }    
    
    public function __construct( eZContentObject $object, $reference = false )
    {
        $this->object = $object;
        $this->node = $object->attribute( 'main_node' );
        $this->dataMap = $object->attribute( 'data_map' );
        
        if ( $reference )
        {
            $this->ref = $this->getId();
        }
        else
        {
            foreach( $this->propertiesMap as $key => $function )
            {
                $value = call_user_func( array( $this, $function ) );
                if ( $value )
                    $this->{$key} = $value;
            }
        }
    }
    
    protected function getId()
    {
        return $this->object->attribute( 'remote_id' );
    }
    
    protected function getName()
    {
        if ( $attribute = $this->attribute( self::NAME_ATTRIBUTE_IDENTIFIER ) )
        {
            return ComuneInTascaI18n::fromAttribute( $attribute );
        }
        return false;
    }
    
    protected function getDescription()
    {
        if ( $attribute = $this->attribute( self::DESCRIPTION_ATTRIBUTE_IDENTIFIER ) )        
        {
            return ComuneInTascaI18n::fromAttribute( $attribute );
        }
        return false;
    }
    
    protected function getImage()
    {    
        if ( $attribute = $this->attribute( self::IMAGE_ATTRIBUTE_IDENTIFIER ) )        
        {            
            return ComuneInTascaI18n::fromAttribute( $attribute );
        }
        return false;
    }
    
    protected function getObjectIds()
    {
        $data = false;
        $objects = array();
        if ( $attribute = $this->attribute( self::OBJECTIDS_ATTRIBUTE_IDENTIFIER ) ) 
        {            
            $data = array();
            $string = $attribute->toString();
            if ( !empty( $string ) )
            {
                $ids = explode( '-', $string );
                foreach( $ids as $id )
                {
                    $objects[] = eZContentObject::fetch( $id );
                }
            }            
        }

        if ( count( $objects ) == 1 )
        {                        
            if ( $objects[0] instanceof eZContentObject
                 && $objects[0]->attribute( 'class_identifier' ) == self::APP_CLASS_IDENTIFIER )
            {                                
                $this->app = ComuneInTascaApp::fromObject( $objects[0] );
                return null;
            }
        }
        
        foreach( $objects as $object )
        {            
            if ( $object instanceof eZContentObject )
            {
                $data[] = $object->attribute( 'remote_id' );
            }
        }
       
        if ( empty( $data ) && !$this->getQuery() && !$this->getItems() )
        {
            $data = array( $this->object->attribute( 'remote_id' ) );
        }
        return $data;
    }
    
    public static function parseQuery( $queryString, $asObject = true )
    {
        $parts = explode( '::', $queryString );
        if ( $asObject )
            $query = new stdClass();
        else
            $query = array();
        
        if ( $asObject )
            $query->type = isset( $parts[0] ) ? $parts[0] : null;
        else
        {
            $class = null;
            if ( isset( $parts[0] ) )
            {
                $classObject =  eZContentClass::fetchByIdentifier( $parts[0] );
                if ( $classObject instanceof eZContentClass )
                {
                    $class = $classObject->attribute( 'name' );
                }
            }
            $query['type'] = $class;
        }
            
        $classifications = null;
        if ( isset( $parts[1] ) )
        {
            $classifications = array();
            $subParts = explode( ';', $parts[1] );
            foreach( $subParts as $subPart )
            {
                $subSubPart = explode( '=', $subPart );
                $classifications[] = array( $subSubPart[0] => $subSubPart[1] );
            }            
        }
        
        if ( $asObject )
            $query->classifications = $classifications;
        elseif ( $classObject instanceof eZContentClass )
        {
            $dataMap = $classObject->attribute( 'data_map' );
            $_classifications = array();            
            foreach( $classifications as $item )
            {
                foreach( $item as $identifier => $classification )
                {
                    if ( isset( $dataMap[$identifier] ) )
                    {
                        $_classifications[$dataMap[$identifier]->attribute( 'name' )] = $classification;
                    }
                }
            }
            $query['classifications'] = $_classifications;
        }
        
        return $query;
    }
    
    // esempio event::materia=Arte;tipologia=Evento Singolo
    protected function getQuery()
    {  
        if ( $attribute = $this->attribute( self::QUERY_ATTRIBUTE_IDENTIFIER ) ) 
        {                        
            return self::parseQuery( $attribute->toString() );
        }
        return false;
    }
    
    protected function getItems()
    {  
        if ( $this->node->attribute( 'children_count' ) > 0 )
        {
            $items = array();
            foreach( $this->node->attribute( 'children' ) as $node )
            {
                $items[] = new ComuneInTascaItem( $node->attribute( 'object' ) );
            }
            return $items;
        }
        return false;
    }
}