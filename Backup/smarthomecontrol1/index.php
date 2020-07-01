<?php

$username = null;
$password = null;


// Mщtodo para mod_php (Apache)
if ( isset( $_SERVER['PHP_AUTH_USER'] ) ):
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    
// Mщtodo para demais servers
elseif ( isset( $_SERVER['HTTP_AUTHORIZATION'] ) ):

    if ( preg_match( '/^basic/i', $_SERVER['HTTP_AUTHORIZATION'] ) )
      list( $username, $password ) = explode( ':', base64_decode( substr( $_SERVER['HTTP_AUTHORIZATION'], 6 ) ) );
endif;

// Se a autenticaчуo nуo foi enviada
if (strcmp($username, 'reg0') != 0 || strcmp($password, 'reg0pw') != 0):
    header('WWW-Authenticate: Basic realm="Smart home control restricted access');
    header('HTTP/1.0 401 Unauthorized');
    die('Acesso negado.');
endif;

require 'scheduler.php';

use \scheduler as A;

$scheduler = new A;
$scheduler->s1=10;
$scheduler->s2=10;
$scheduler->s3=10;
$scheduler->s4=10;
$scheduler->s5=10;
$scheduler->s6=10;
$scheduler->s7=10;
$scheduler->s8=10;
$json = json_encode($scheduler, JSON_PRETTY_PRINT);
echo($json);
?>