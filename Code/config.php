<?php

/******************************************************************************
 * Projekt: Hotel: rezervace a správa ubytování                               *
 * Předmět: IIS - Informační systémy - FIT VUT v Brně                         *
 * Rok:     2020/2021                                                         *
 * Tým:     xsekul01, varianta 2                                              *
 * Autoři:                                                                    *
 *          Jakub Sekula   (xsekul01) - xsekul01@stud.fit.vutbr.cz            *
 *          Lukáš Perina   (xperin11) - xperin11@stud.fit.vutbr.cz            *
 *			Martin Fekete  (xfeket00) - xfeket00@stud.fit.vutbr.cz            *
 ******************************************************************************/

?>

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