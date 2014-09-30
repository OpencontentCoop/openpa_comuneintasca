<?php

$Module = $Params['Module'];
$Module->setTitle( 'Dati Comune In Tasca' );
$Profile = $Params['Profile'];
$currentUri = 'comuneintasca/data';
eZURI::transformURI( $currentUri, false, 'full' );
try
{
    $helper = ComuneInTascaHelper::instance();
    
    if ( $Profile == NULL )
    {
        $data = array();
        foreach( $helper->profiles() as $profile )
        {
            $data[] = array(
                'name' => $profile->attribute( 'name' ),
                'id' => $profile->attribute( 'contentobject_id' ),
                'dateModified' => $profile->attribute( 'modified_subnode' ),
                'uri' => $currentUri . '/' . $profile->attribute( 'contentobject_id' )
            );            
        }
    }
    else
    {
        $profileObject = eZContentObject::fetch( $Profile );
        if ( $profileObject instanceof eZContentObject )
        {
            $data = new ComuneInTascaProfile( $profileObject );
        }
        else
        {
            response( '404 Not Found', array( 'error' => 'Not found' ) );
        }
    }
}
catch( Exception $e )
{
    response( '400 Bad Request', array( 'error' => $e->getMessage() ) );
}

response( '200 OK', $data );

function response( $httpHeader, $data )
{
    header( "HTTP/1.1 $httpHeader" );
    header('Content-Type: application/json');
    echo json_encode( $data );
    //eZDisplayDebug();
    eZExecution::cleanExit();
}