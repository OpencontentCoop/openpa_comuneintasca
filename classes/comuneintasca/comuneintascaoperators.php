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
        }        
    }
}