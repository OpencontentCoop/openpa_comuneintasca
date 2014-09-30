<?php

class ComuneInTascaProfile
{    
    const MAIN_ATTRIBUTE_IDENTIFIER = 'identificatore';
    
    public $highlights;
    
    public $navigationItems;

    public $menu;
    
    private $node;    
    
    private $object;

    private $items = array();
    
    private $objects = array();
    
    private $zoneIds = array(
        'highlights' => 'In evidenza',
        'navigationItems' => 'Navigazione principale',
        'menu' => 'Menu laterale'
    );
    
    public function __construct( eZContentObject $object )
    {
        $this->object = $object;
        $this->node = $object->attribute( 'main_node' );
        $this->highlights = $this->getItems( 'highlights' );
        $this->navigationItems = $this->getItems( 'navigationItems' );
        $this->menu = $this->getItems( 'menu' );
    }
    
    public function checkZones()
    {        
        foreach( $this->zoneIds as $zoneId => $zoneName )
        {
            $remoteId = $this->object->attribute( 'remote_id' ) . '_' . $zoneId;
            if ( !eZContentObject::fetchByRemoteID( $remoteId, false ) )
            {
                $params = array(
                    'parent_node_id' => $this->object->attribute( 'main_node_id' ),
                    'class_identifier' => ComuneInTascaHelper::ZONE_CLASSIDENTIFIER,
                    'remote_id' => $remoteId,
                    'attributes' => array( self::MAIN_ATTRIBUTE_IDENTIFIER => $zoneName )
                );
                if ( !eZContentFunctions::createAndPublishObject( $params ) )
                {
                    throw new Exception( "Failed creating Comune In Tasca zone node $zoneId" );
                }
            }
        }
    }
    
    private function getItems( $zoneId )
    {
        if ( !isset( $this->items[$zoneId] ) )
        {
            $this->checkZones();
            $remoteId = $this->object->attribute( 'remote_id' ) . '_' . $zoneId;
            $zoneObject = eZContentObject::fetchByRemoteID( $remoteId );
            if ( !$zoneObject instanceof eZContentObject )
            {
                throw new Exception( "Error fetching zone $zoneId" );
            }
            $zoneNode = $zoneObject->attribute( 'main_node' );
            $this->items[$zoneId] = array();
            foreach( $zoneNode->attribute( 'children' ) as $node )
            {
                $object = $node->attribute( 'object' );                
                $reference = $node->attribute( 'object' )->attribute( 'main_node_id' ) != $node->attribute( 'node_id' );
                //if ( isset( $this->objects[$object->attribute( 'id' )] ) )
                //{
                //    $reference = true;
                //}
                //else
                //{
                //    $this->objects[$object->attribute( 'id' )] = $object->attribute( 'id' );
                //}
                $this->items[$zoneId][] = new ComuneInTascaItem( $object, $reference );
            }
        }
        return $this->items[$zoneId];
    }
}