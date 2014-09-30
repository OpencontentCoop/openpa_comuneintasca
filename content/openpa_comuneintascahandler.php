<?php

class openpa_comuneintascaHandler
{
    function validateInput( $http, &$module, &$class, $object, &$version, $contentObjectAttributes, $editVersion, $editLanguage, $fromLanguage, $validationParameters )
    {
        $result = array( 'is_valid' => true );        
        if ( $object->attribute( 'class_identifier' ) == 'item_comuneintasca' )
        {
            $firstImage = false;
            $imageAttribute = false;
            $id = false;
            foreach( $contentObjectAttributes  as $attribute )
            {                
                if ( $attribute->attribute( 'contentclass_attribute_identifier' ) == 'objects' )
                {
                    $id = $attribute->attribute( 'id' );
                }
                if ( $attribute->attribute( 'contentclass_attribute_identifier' ) == 'image' )
                {
                    $imageAttribute = $attribute;
                }
            }            
            $values = $_POST["ContentObjectAttribute_data_object_relation_list_" . $id];            
            $toRemove = isset( $_POST['ContentObjectAttribute_selection'][$id] ) ? $_POST['ContentObjectAttribute_selection'][$id] : array();            
            $values = array_diff( $values, $toRemove );            
            if ( count( $values ) > 0 )
            {
                $classIdentifiers = array();
                $objects = eZContentObject::fetchList( true, array( 'id' => array( $values ) ) );
                foreach( $objects as $object )
                {
                    if ( isset( $classIdentifiers[$object->attribute( 'class_identifier' )] ) )
                        $classIdentifiers[$object->attribute( 'class_identifier' )]++;
                    else
                        $classIdentifiers[$object->attribute( 'class_identifier' )] = 1;
                }                
                if ( count( array_keys( $classIdentifiers ) ) > 1 )
                {
                    return array(
                        'is_valid' => false,
                        'warnings' => array( array( 'text' => 'Oggetti: gli oggetti selezionati devono essere tutti dello stesso tipo (classe).' ) )
                    );                    
                }
                elseif ( $imageAttribute instanceof eZContentObjectAttribute && !$imageAttribute->attribute( 'has_content' ) )
                {
                    foreach( $objects as $object )
                    {
                        $dataMap = $object->attribute( 'data_map' );
                        foreach( $dataMap as $attribute )
                        {
                            if ( $attribute->attribute( 'data_type_string' ) == 'ezimage' && $attribute->attribute( 'has_content' ) )
                            {
                                $firstImage = $attribute->toString();
                                break 2;
                            }
                        }
                    }
                    if ( $firstImage )
                    {
                        $imageAttribute->fromString( $firstImage );
                        $imageAttribute->store();
                    }
                }
            }            
        }
       return $result;
    }

}