<?php

include("./session_start.php");

// Unset all session variables
session_unset();

// Destroy it
session_destroy();

echo JSON_encode("Session Destroyed")
    
?>