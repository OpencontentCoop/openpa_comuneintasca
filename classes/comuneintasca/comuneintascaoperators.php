<?php

class ComuneInTascaOperators
{
    /**
     * Returns the list of template operators this class supports
     *
     * @return array
     */
    function operatorList()
    {
        return array(
            'comune_in_tasca_root',
            'comune_in_tasca_profiles',
            'menuitem_class_identifier',
            'zone_class_identifier',
            'parse_item_query',
            'class_list',
            'attribute_list'
        );
    }

    /**
     * Indicates if the template operators have named parameters
     *
     * @return bool
     */
    function namedParameterPerOperator()
    {
        return true;
    }

    /**
     * Returns the list of template operator parameters
     *
     * @return array
     */
    function namedParameterList()
    {
        return array();
    }

    /**
     * Executes the template operator
     *
     * @param eZTemplate $tpl
     * @param string $operatorName
     * @param mixed $operatorParameters
     * @param string $rootNamespace
     * @param string $currentNamespace
     * @param mixed $operatorValue
     * @param array $namedParameters
     * @param mixed $placement
     */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters, $placement )
    {
        $handler = ComuneInTascaHelper::instance();
        switch( $operatorName )
        {
            case 'comune_in_tasca_root':
                $operatorValue = $handler->rootNode( false );
                break;
            
            case 'comune_in_tasca_profiles':
                $operatorValue = $handler->profiles();
                break;
            
            case 'menuitem_class_identifier':
                $operatorValue = ComuneInTascaHelper::ITEM_CLASSIDENTIFIER;
                break;
            
            case 'zone_class_identifier':
                $operatorValue = ComuneInTascaHelper::ZONE_CLASSIDENTIFIER;
                break;
            
            case 'parse_item_query':
                $operatorValue = ComuneInTascaItem::parseQuery( $operatorValue, false );
                break;
            
            case 'class_list':                
                $operatorValue = $this->comuneInTascaClasses();
                break;
            
            case 'attribute_list':                
                $operatorValue = $this->comuneInTascaAttributesByClass( $operatorValue );
                break;
        }        
    }
    
    function comuneInTascaAttributesByClass( $classIdentifier )
    {
        $data = array();
        
        switch( $classIdentifier )
        {
            case 'ristorante':
                $data['tipo_locale'] = "Tipo di locale";
                break;
            
            case 'event':
                $data['tipo_evento'] = "Tipologia";
                $data['tipo_eventi_manifestazioni'] = "Evento - manifestazione";
                break;
                
            case 'luogo':
                $data['tipo_luogo'] = "Tipologia di luogo";
                break;
            
            case 'accomodation':
                $data['tipologia_hotel'] = "Tipologia di alloggio";
                break;
            
            case 'iniziativa':
                $data['tipo_evento'] = "Tipologia";
                break;
            
            case 'folder':
                $data['classifications'] = "Parole chiave";
                break;
        }
        
        //$class = eZContentClass::fetchByIdentifier( $classIdentifier );
        //if ( $class instanceof eZContentClass )
        //{
        //    foreach( $class->attribute( 'data_map' ) as $attribute )
        //    {
        //        switch ( $attribute->attribute( 'data_type_string' ) )
        //        {
        //            case 'ezobjectrelationlist':
        //                $data[$attribute->attribute( 'identifier' )] = $attribute->attribute( 'name' );
        //                break;
        //            
        //            default:
        //                break;
        //        }
        //    }
        //}
        return $data;
    }
    
    function comuneInTascaClasses()
    {
        $list = eZContentClassClassGroup::fetchClassList( null, 15 );
        $data = array();
        foreach( $list as $class )
        {
            if ( strpos( $class->attribute( 'identifier' ), '_comuneintasca' ) === false
                 && strpos( $class->attribute( 'identifier' ), 'tipologia' ) === false
                 && 'apps_container' != $class->attribute( 'identifier' ) )
            {
                $data[$class->attribute( 'identifier' )] = $class->attribute( 'name' );
            }
        }
        return $data;
    }
}