<?php

// start session
session_start();

// kill the session
session_destroy();

// redirect back to website home
header('Location: http://localhost:63342/phplogindemo/index2.php' );