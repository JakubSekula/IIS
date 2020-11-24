<?php

include_once( 'services.php' );

session_start();

$Home = new UserService();

$Home->updateUser( $_POST, $_SESSION[ 'id' ] );
header( 'location: index.php' );
exit();

?>