<?php

class eZComuneInTascaType extends eZWorkflowEventType
{

    const WORKFLOW_TYPE_STRING = 'ezcomuneintasca';

    function __construct()
    {
        parent::__construct(
            self::WORKFLOW_TYPE_STRING,
            'Comune in Tasca'
        );        
    }

    function execute( $process, $event )
    {
        try
        {            
            ComuneInTascaHelper::executeWorkflow( $process, $event );
            return eZWorkflowType::STATUS_ACCEPTED;    
        }
        catch( Exception $e )
        {
            eZDebug::writeError( $e->getMessage(), __METHOD__ );
            return eZWorkflowType::STATUS_REJECTED;
        }
        
    }
}

eZWorkflowEventType::registerEventType( eZComuneInTascaType::WORKFLOW_TYPE_STRING, 'eZComuneInTascaType' );
