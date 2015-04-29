<?php

// start session
session_start();

// This is where we put global configuration variables
require_once('my_fb_config.php');

// kill the session
session_destroy();

// This would automatically redirect back to the website home
//header('Location: '.$my_base_url.'index.php' );


echo '<a href='.$my_base_url.'index.php>Login</a>';
