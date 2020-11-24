<?php
session_start();
$DATABASE_HOST  = "mysql57.websupport.sk:3311";
$DATABASE_USER  = "fit_iis";
$DATABASE_PASS  = "Sx5.b195]+";
$DATABASE_NAME  = "fit_iis";

$connection = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$connection->set_charset("utf8");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

?>