<?php

include "config.php";

function getUserInfo(){
    global $connection;

    $sql = "SELECT * FROM User WHERE email='".$_SESSION[ 'email' ]."'";

    $result = $connection->query( $sql );

    //$array = array();

    if( $result->num_rows > 0 ) {
        while( $row = $result->fetch_assoc() ){
            return $row;
        }
    }

    //return array();
}

?>