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

include_once( "config.php" );

function getUserInfo(){
    global $connection;

    $sql = "SELECT * FROM User WHERE email='".$_SESSION[ 'email' ]."'";

    $result = $connection->query( $sql );

    if( $result->num_rows > 0 ) {
        while( $row = $result->fetch_assoc() ){
            return $row;
        }
    }

}

?>