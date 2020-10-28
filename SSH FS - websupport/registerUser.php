<?php

include_once( "config.php" );

$name = htmlspecialchars( $_POST[ 'name' ] );
$surname = htmlspecialchars( $_POST[ 'surname' ] );
$mail = htmlspecialchars( $_POST[ 'email' ] );
$password = htmlspecialchars( $_POST[ 'password' ] );

$hashed_password = password_hash( $password, PASSWORD_DEFAULT );

$sql = "INSERT INTO User ( name, surname, email, password )
                VALUES ( '$name', '$surname', '$mail', '$hashed_password' )";

    if( $connection->query( $sql ) === TRUE ) {
        header( 'Location: signinView.php' );
    
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }

?>