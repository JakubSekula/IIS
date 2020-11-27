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
include_once("include.php");
sessionTimeout();
printHTMLheader( "Hotels" );
printHeader();

$user = getUserInfo();
$name = array_values( $user )[ 1 ] . " " . array_values( $user )[ 2 ];

$Hotel = new UserService();

$userInfo = $Hotel->GetUser( $_SESSION[ 'id' ] );

$address = "";

// if the address is not provided, empty string is assigned 
if( $userInfo[ 'address' ] == "" ){
    $address = "";
} else {
    $address = $userInfo[ 'address' ];
}

$phone = "";

// if the phone number is not provided, empty string is assigned 
if( $userInfo[ 'phone_number' ] == "" ){
    $phone = "";
} else {
    $phone = $userInfo[ 'phone_number' ];
}

// Data update on load checker
if(isset($_GET['act'])){
    echo "<div class='confirm' id='confirm'> <h5> Data updated</h5> </div>";
    echo "<script>$(document).ready( function () {";
    echo "setTimeout(function(){ $('#confirm').css('display', 'none'); }, 2000);});</script>";
        
}

print
    ("

        <form action=\"update.php\" method=\"post\">
        <div class=\"RegisterForm\">
            <h1>$name</h1>
            <hr>

            <label for=\"name\"><b>Name</b></label>
            <input type=\"text\" value=".$userInfo[ 'name' ] ." name=\"name\" id=\"name\">

            <label for=\"surname\"><b>Surname</b></label>
            <input type=\"text\" value=".$userInfo[ 'surname' ] ." name=\"surname\" id=\"surname\">

            <label for=\"phone_number\"><b>Phone number</b></label>
            <input type=\"text\" value=\"$phone\" name=\"phone_number\" id=\"phone_number\">

            <label for=\"email\"><b>Email</b></label>
            <input type=\"text\" value=". $userInfo[ 'email' ] ." name=\"email\" id=\"email\" readonly>

            <label for=\"address\"><b>address</b></label>
            <input type=\"text\" value=\"$address\" name=\"address\" id=\"address\" >

            <hr>

            <input type=\"hidden\" id=\"custId\" name=\"Users\" value=\"Update\">
            <input type=\"hidden\" name=\"id\" value=" . $_SESSION[ 'id' ] . ">
            <input type=\"hidden\" name=\"rights\" value=" . $_SESSION[ 'rights' ] . ">
            <button type=\"submit\" class=\"registerbtn\">Update</button>

        </div>

        </form>

        <hr>

        <form action=\"update.php\" method=\"post\">
        
        <div class=\"RegisterForm\">
            <h1>Change password</h1>
            <hr>

            <label for=\"oldpasswrd\"><b>Current Password</b></label>
            <input type=\"password\" value=\"\" name=\"oldpasswrd\" id=\"oldpasswrd\">

            <label for=\"newpasswrd\"><b>New Password</b></label>
            <input type=\"password\" value=\"\" name=\"newpasswrd\" id=\"newpasswrd\">

            <hr>

            <input type=\"hidden\" id=\"Users\" name=\"Users\" value=\"psw\">
            <input type=\"hidden\" name=\"id\" value=" . $_SESSION[ 'id' ] . ">
            <input type=\"hidden\" name=\"rights\" value=" . $_SESSION[ 'rights' ] . ">
            <button type=\"submit\" class=\"registerbtn\">Change password</button>

        </div>

        </form>
    ");


?>

</body>
</html>