<?php



function debug_to_console( $data ) {

$enable_debug_to_console = true;

    if($enable_debug_to_console) {
        if (is_array($data))
            $output = "<script>console.log( 'Php debug output: " . implode(',', $data) . "' );</script>";
        else
            $output = "<script>console.log( 'Php debug output: " . $data . "' );</script>";

        echo $output;
    }

}
