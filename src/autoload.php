<?php

spl_autoload_register(
    function( $class_name )
    {
        if ( false !== strpos( $class_name, 'Development\\' ) )
        {
            var_dump( __DIR__ . '/' . str_replace( 'Development\\', '', $class_name ) . '.php' );
            include __DIR__ . '/' . str_replace( 'Development\\', '', $class_name ) . '.php';
        }
    }
);