<?php

class ComuneInTascaApp
{
    public static function fromObject( $object )
    {
        $dataMap = $object->attribute( 'data_map' );
        $data = new self();
        $data->ios = $dataMap['ios']->toString() != '' ? $dataMap['ios']->toString() : null;
        $data->android = $dataMap['android']->toString() != '' ? $dataMap['android']->toString() : null;
        return $data;
    }
}