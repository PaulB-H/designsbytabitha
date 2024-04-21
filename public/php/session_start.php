<?php

$maxlifetime = 3600; // In seconds
$secure = true; // if you only want to receive the cookie over HTTPS
$httponly = true; // prevent JavaScript access to session cookie
$samesite = 'lax';

session_set_cookie_params([
  'lifetime' => $maxlifetime,
  'path' => '/',
  'domain' => $_SERVER['HTTP_HOST'],
  'secure' => $secure,
  'httponly' => $httponly,
  'samesite' => $samesite
]);

ini_set('session.use_strict_mode', 1);

session_start();

?>
