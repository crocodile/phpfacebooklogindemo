<?php

function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Php debug output: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Php debug output: " . $data . "' );</script>";

    echo $output;
}