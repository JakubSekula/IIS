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


// ------------------------------
// LOGGED OUT user just wants to book the room
if(isset($_POST['bookNew'])) {

    $hotel = new HotelService();
    $user = new UserService();

    if (!$user->validateEmail()) {
        print
        ("
        <script type=\"text/javascript\">
            alert(\"Invalid email format or email already used\");
        </script>

        <form action=\"bookView.php\" method=\"post\" id=\"failedBookingForm\">
            <input name=\"hotel_id\" value=\"$_POST[hotel_id]\" type=\"hidden\">
            <input name=\"from\" value=\"$_POST[from]\" type=\"hidden\">
            <input name=\"to\" value=\"$_POST[to]\" type=\"hidden\">
            <input name=\"guests\" value=\"$_POST[guests]\" type=\"hidden\">
            <input name=\"type\" value=\"$_POST[type]\" type=\"hidden\">
            <input name=\"principal\" value=\"$_POST[principal]\" type=\"hidden\">
            <input name=\"name\" value=\"$_POST[name]\" type=\"hidden\">
            <input name=\"surname\" value=\"$_POST[surname]\" type=\"hidden\">
            <input name=\"phone\" value=\"$_POST[phone_number]\" type=\"hidden\">
        </form>

        <script>
            window.onload = function(){
                document.forms['failedBookingForm'].submit();
            }
        </script>
        ");
        exit();
    }

    $reservation_id = $hotel->bookRoom();

    if ($reservation_id != "") {

        print
        ("
            <form method=\"post\" action=\"bookView.php\" id=\"bookSuccessForm\">
                <input name=\"success\" value=\"true\" type=\"hidden\">
                <input name=\"reservation_id\" value=\"$reservation_id\" type=\"hidden\">
                <input name=\"principal\" value=$_POST[principal] type=\"hidden\">
            </form>
        ");

    } else {

        print
        ("
            <form method=\"post\" action=\"bookView.php\" id=\"bookSuccessForm\">
                <input name=\"success\" value=\"false\" type=\"hidden\">
            </form>
            
        ");    
    }

    // Submit the form
    print
    ("
        <script>
            window.onload = function(){
                document.forms['bookSuccessForm'].submit();
            }
        </script>
    ");
}

// ------------------------------
// LOGGED OUT user wants to register and book the room
if (isset($_POST['bookRegisterNew'])) {
    
    $hotel = new HotelService();
    $user = new UserService();

    if (!$user->validatePassword()) {
        print
        ("
        <script type=\"text/javascript\">
            alert(\"Passwords do not match\");
        </script>

        <form action=\"bookView.php\" method=\"post\" id=\"failedBookingForm\">
            <input name=\"hotel_id\" value=\"$_POST[hotel_id]\" type=\"hidden\">
            <input name=\"from\" value=\"$_POST[from]\" type=\"hidden\">
            <input name=\"to\" value=\"$_POST[to]\" type=\"hidden\">
            <input name=\"guests\" value=\"$_POST[guests]\" type=\"hidden\">
            <input name=\"type\" value=\"$_POST[type]\" type=\"hidden\">
            <input name=\"principal\" value=\"$_POST[principal]\" type=\"hidden\">
            <input name=\"name\" value=\"$_POST[name]\" type=\"hidden\">
            <input name=\"surname\" value=\"$_POST[surname]\" type=\"hidden\">
            <input name=\"phone\" value=\"$_POST[phone_number]\" type=\"hidden\">
            <input name=\"email\" value=\"$_POST[email]\" type=\"hidden\">
        </form>

        <script>
            window.onload = function(){
                document.forms['failedBookingForm'].submit();
            }
        </script>
        ");
        exit();
    }
    
    $userID = $user->registerUser();
    
    // Email is not valid
    if ($userID == "") {
        print
        ("
        <script type=\"text/javascript\">
            alert(\"Invalid email format or email already used\");
        </script>

        <form action=\"bookView.php\" method=\"post\" id=\"failedBookingForm\">
            <input name=\"hotel_id\" value=\"$_POST[hotel_id]\" type=\"hidden\">
            <input name=\"from\" value=\"$_POST[from]\" type=\"hidden\">
            <input name=\"to\" value=\"$_POST[to]\" type=\"hidden\">
            <input name=\"guests\" value=\"$_POST[guests]\" type=\"hidden\">
            <input name=\"type\" value=\"$_POST[type]\" type=\"hidden\">
            <input name=\"principal\" value=\"$_POST[principal]\" type=\"hidden\">
            <input name=\"name\" value=\"$_POST[name]\" type=\"hidden\">
            <input name=\"surname\" value=\"$_POST[surname]\" type=\"hidden\">
            <input name=\"phone\" value=\"$_POST[phone_number]\" type=\"hidden\">
        </form>

        <script>
            window.onload = function(){
                document.forms['failedBookingForm'].submit();
            }
        </script>
        ");
        exit();
    }
    
    $_POST['user_id'] = $userID;
    $reservation_id = $hotel->bookRoom();

    if ($reservation_id != "") {

        print
        ("
            <form method=\"post\" action=\"bookView.php\" id=\"bookSuccessForm\">
                <input name=\"success\" value=\"true\" type=\"hidden\">
                <input name=\"reservation_id\" value=\"$reservation_id\" type=\"hidden\">
                <input name=\"principal\" value=$_POST[principal] type=\"hidden\">
            </form>
        ");

    } else {

        print
        ("
            <form method=\"post\" action=\"bookView.php\" id=\"bookSuccessForm\">
                <input name=\"success\" value=\"false\" type=\"hidden\">
            </form>
            
        ");    
    }

    // Submit the form
    print
    ("
        <script>
            window.onload = function(){
                document.forms['bookSuccessForm'].submit();
            }
        </script>
    ");
}


// ------------------------------
// LOGGED IN user wants to book the room
if (isset($_POST['bookRegistered'])) {

    $hotel = new HotelService();
    $reservation_id = $hotel->bookRoom();

    if ($reservation_id != "") {

        print
        ("
            <form method=\"post\" action=\"bookView.php\" id=\"bookSuccessForm\">
                <input name=\"success\" value=\"true\" type=\"hidden\">
                <input name=\"reservation_id\" value=\"$reservation_id\" type=\"hidden\">
                <input name=\"principal\" value=$_POST[principal] type=\"hidden\">
            </form>
        ");

    } else {

        print
        ("
            <form method=\"post\" action=\"bookView.php\" id=\"bookSuccessForm\">
                <input name=\"success\" value=\"false\" type=\"hidden\">
            </form>
            
        ");    
    }

    // Submit the form
    print
    ("
        <script>
            window.onload = function(){
                document.forms['bookSuccessForm'].submit();
            }
        </script>
    ");
}


// ------------------------------
// User wants to pay the principal
if (isset($_POST['payment'])) {
    
    $hotel = new HotelService();
    
    // Payment failed
    if (!$hotel->payPrincipal()) {
        
        print
        ("
            <form method=\"post\" action=\"bookView.php\" id=\"bookSuccessForm\">
                <input name=\"payment\" value=\"failed\" type=\"hidden\">
            </form>
            
            <script>
                window.onload = function(){
                    document.forms['bookSuccessForm'].submit();
                }
            </script>
        ");

    // Payment was successful
    } else {

        print
        ("
            <form method=\"post\" action=\"bookView.php\" id=\"bookSuccessForm\">
                <input name=\"payment\" value=\"success\" type=\"hidden\">
            </form>
            
            <script>
                window.onload = function(){
                    document.forms['bookSuccessForm'].submit();
                }
            </script>
        ");
    }
}

?>