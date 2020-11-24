<?php
include_once("include.php");
sessionTimeout();


// ------------------------------
// LOGGED OUT user just wants to book the room
if(isset($_POST['bookNew'])) {

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
// LOGGED OUT user wants to register and book the room
if (isset($_POST['bookRegisterNew'])) {
    
    $hotel = new HotelService();
    $user = new UserService();
    
    $userID = $user->registerUser();
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