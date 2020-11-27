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

$hotel_id = $_POST['hotel_id']; 
$from = $_POST['from'];
$to = $_POST['to'];
$guests = $_POST['guests'];
$type = $_POST['type'];
$principal = $_POST['principal'];

$user_id = $_SESSION['id'];

// Check if room can be booked
$hotel = new HotelService();
$hotel_name = $hotel->getHotelName($hotel_id);

// Print booking info
$booking_info = "
    <table class=\"table\">
        <thead>
            <tr>
                <th>Hotel</th>
                <th>Arrival</th>
                <th>Departure</th>
                <th>Guests</th>
                <th>Room type</th>
                <th>Principal to be paid</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>$hotel_name</td>
                <td>$from</td>
                <td>$to</td>
                <td>$guests</td>
                <td>$type</td>
                <td>$principal</td>
            </tr>
        </tbody>
    </table>";


// ----------------------

printHTMLheader("Booking");
printHeader();

echo "<div class=\"m-main\">";

// Booking was successful
if (isset($_POST['success'])) {

    print
    ("
        <div class=\"row ktopmargin\">
        <div class=\"col\">
    ");
    
    // Booking was successful
    // Add payment button if necessary
    if ($_POST['success'] == "true") {
        echo "<h2>Your booking was successful!</h2>";

        // Principal needs to be paid
        if ($principal != '0') {

            $reservation_id = $_POST['reservation_id'];

            print
            ("
                <form action=\"bookAction.php\" method=\"post\">
                    <div>
                        <input name=\"reservation_id\" value=\"$reservation_id\" type=\"hidden\"/>
                        <input name=\"payment\" value=\"Pay\" class=\"registerbtn\" type=\"submit\"/>
                    </div>
                </form>
            ");
        }

    // Booking failed
    } else {
        echo "<h2>Your booking failed, try again later please</h2>";
    }
        
    print
    ("
        <a href=\"/index.php\" class=\"btn btn-primary active\" role=\"button\" aria-pressed=\"true\">Home</a>
        </div>
        </div>
    ");

// Principal was payed
} else if (isset($_POST['payment'])) {

    print
    ("
        <div class=\"row ktopmargin\">
        <div class=\"col\">
    ");
    
    // Booking was successful
    // Add payment button if necessary
    if ($_POST['payment'] == "success") {
        echo "<h2>Your booking principal was succesfully paid!</h2>";

    // Booking failed
    } else {
        echo "<h2>Your payment failed, try again later please</h2>";
    }
    
    print
    ("
        <a href=\"/index.php\" class=\"btn btn-primary active\" role=\"button\" aria-pressed=\"true\">Home</a>
        </div>
        </div>
    ");

// Booking is yet to be confirmed
} else {

    print
    ("
        <div class=\"row ktopmargin\">
            <div class=\"col\">
                <h2>Complete your booking</h2>
            </div>
        </div>
    ");
    
    print($booking_info);
    
    // User not logged in
    if ($_SESSION['loggedin'] != 1) {
        
        print
        ("
            <script>
            $( document ).ready(function() {
                $(\"#bookNew\").click(function() {
                    $(\"#password\").removeAttr(\"required\");
                    $(\"#psw-repeat\").removeAttr(\"required\");
                });
            });
            </script>

            <form action=\"bookAction.php\" method=\"post\">
                <div>
                    <label for=\"name\" class=\"required\"><b>Name</b></label>
        ");
        
        if (isset($_POST['name'])) {
            echo "<input type=\"text\" value=\"".$_POST['name']."\" name=\"name\" id=\"name\" required>";
        } else {
            echo "<input type=\"text\" placeholder=\"Name\" name=\"name\" id=\"name\" required>";
        }
        
        echo "<label for=\"surname\" class=\"required\"><b>Surname</b></label>";
        if (isset($_POST['surname'])) {
            echo "<input type=\"text\" value=\"".$_POST['surname']."\" name=\"surname\" id=\"surname\" required>";
        } else {
            echo "<input type=\"text\" placeholder=\"Surname\" name=\"surname\" id=\"surname\" required>";
        }

        echo "<label for=\"email\" class=\"required\"><b>Email</b></label>";
        if (isset($_POST['email'])) {
            echo "<input type=\"text\" value=\"".$_POST['email']."\" name=\"email\" id=\"email\" required>";
        } else {
            echo "<input type=\"text\" placeholder=\"Email\" name=\"email\" id=\"email\" required>";
        }

        echo "<label for=\"phone_number\" class=\"required\"><b>Phone</b></label>";
        if (isset($_POST['phone'])) {
            echo "<input type=\"text\" value=\"".$_POST['phone']."\" name=\"phone_number\" id=\"phone_number\" required>";
        } else {
            echo "<input type=\"text\" placeholder=\"Phone number\" name=\"phone_number\" id=\"phone_number\" required>";
        }

        print
        ("
                    <hr>

                    <input name=\"hotel_id\" value=\"$hotel_id\" type=\"hidden\">
                    <input name=\"from\" value=\"$from\" type=\"hidden\">
                    <input name=\"to\" value=\"$to\" type=\"hidden\">
                    <input name=\"guests\" value=\"$guests\" type=\"hidden\">
                    <input name=\"type\" value=\"$type\" type=\"hidden\">
                    <input name=\"principal\" value=\"$principal\" type=\"hidden\">

                    <input class=\"registerbtn\" id=\"bookNew\" type=\"submit\" value=\"Book\" name=\"bookNew\"/>

                    <label for=\"password\" class=\"required\"><b>Password</b></label>
                    <input type=\"password\" placeholder=\"Enter Password\" name=\"password\" id=\"password\"  required=\"required\">
                    <label for=\"psw-repeat\" class=\"required\"><b>Repeat Password</b></label>
                    <input type=\"password\" placeholder=\"Repeat Password\" name=\"psw-repeat\" id=\"psw-repeat\"  required=\"required\">
                    
                    <input class=\"registerbtn\" type=\"submit\" value=\"Book and register\" name=\"bookRegisterNew\"/>
                </div>
            </form>
        ");

    // User logged in, can make reservation
    } else {
        
        print
        ("
            <form action=\"bookAction.php\" method=\"post\">
                
                <input name=\"user_id\" value=\"$user_id\" type=\"hidden\">
                <input name=\"hotel_id\" value=\"$hotel_id\" type=\"hidden\">
                <input name=\"from\" value=\"$from\" type=\"hidden\">
                <input name=\"to\" value=\"$to\" type=\"hidden\">
                <input name=\"guests\" value=\"$guests\" type=\"hidden\">
                <input name=\"type\" value=\"$type\" type=\"hidden\">
                <input name=\"principal\" value=\"$principal\" type=\"hidden\">
                
                <input class=\"registerbtn\" type=\"submit\" value=\"Book\" name=\"bookRegistered\"/>
            </form>
        ");
    }
}

echo "</div>";

print
("
    </body>
</html>
");