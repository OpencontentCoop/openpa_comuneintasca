<?php

class ComuneInTascaI18n
{
    protected $languageMap = array(
        'it' => 'ita-IT',
        'en' => 'eng-GB',
        'de' => 'ger-DE'
    );
    
    public static function fromObject( eZContentObject $object, $asObject = false )
    {
        $item = new self();
        foreach( $item->getLanguageMap() as $key => $language )
        {
            $item->{$key} = $object->attribute( 'id' );
        }
        return $item;
    }
    public static function fromAttribute( eZContentObjectAttribute $attribute, $asObject = false )
    {
        $object = $attribute->attribute( 'object' );
        $item = new self();
        foreach( $item->getLanguageMap() as $key => $language )
        {
            $node = eZContentObjectTreeNode::fetch( $object->attribute( 'main_node_id' ), $language );
            if ( $node instanceof eZContentObjectTreeNode )
            {
                $dataMap = $node->attribute( 'data_map' );
                $current = isset( $dataMap[$attribute->attribute( 'contentclass_attribute_identifier' )] ) ? $dataMap[$attribute->attribute( 'contentclass_attribute_identifier' )] : false;
                if ( $current instanceof eZContentObjectAttribute && $current->attribute( 'has_content' ) )
                {
                    $apiUrl = sprintf( $item->baseUrl( 'attribute' ), $current->attribute( 'contentobject_id' ), $current->attribute( 'contentclass_attribute_identifier' ), $language );
                    switch( $attribute->attribute( 'data_type_string' ) )
                    {
                        case 'ezimage':
                            $image = $current->content()->attribute( 'original' );
                            $imageUrl = $image['full_path'];
                            eZURI::transformURI( $imageUrl, false, 'full' );
                            if ( $asObject )
                                $item->{$key} = array( 'value' => $imageUrl, 'api_url' => $apiUrl );
                            else
                                $item->{$key} = $imageUrl;
                            break;
                        
                        case 'ezobjectrelationlist':
                            $string = $current->toString();
                            if ( $asObject )
                                $item->{$key} = array( 'value' => explode( '-', $string ), 'api_url' => $apiUrl );
                            else
                                $item->{$key} = explode( '-', $string );
                            break;
                        
                        default:
                            if ( $asObject )
                                $item->{$key} = array( 'value' => $current->toString(), 'api_url' => $apiUrl );
                            else
                                $item->{$key} = $current->toString();
                    }                    
                }
            }
            if ( !isset( $item->{$key} ) )
            {
                $item->{$key} = null;
            }
        }
        return $item;
    }
    
    public function getLanguageMap()
    {
        return $this->languageMap;    
    }
    
    protected function baseUrl( $type )
    {
        $baseUrl = '';
        if ( $type == 'object' )
        {
            $baseUrl = 'api/opendata/v1/content/object/%d?Translation=%s';
        }
        elseif ( $type == 'attribute' )
        {
            $baseUrl = 'api/opendata/v1/content/object/%d/field/%s?Translation=%s';
        }
                
        eZURI::transformURI( $baseUrl, false, 'full' );
        return $baseUrl;
    }
}