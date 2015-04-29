<?php

// start session
session_start();

// This is where we put global configuration variables
require_once('my_config_file.php');

// kill the session
session_destroy();

// redirect back to website home
header('Location: '.$my_base_url.'index.php' );