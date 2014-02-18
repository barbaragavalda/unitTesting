<?php

spl_autoload_register(
    function ( $class_name ){
        $file = __DIR__ . '/' . str_replace('Development\\', '', $class_name) . '.php';
        var_dump($file);
        include_once( $file );
    }
);