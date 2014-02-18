<?php

spl_autoload_register(
    function ( $class_name ){
        $file = __DIR__ . '/' . str_replace('Development\\', '', $class_name) . '.php';
        include_once( $file );
    }
);