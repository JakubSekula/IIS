<?php
include_once("services.php");

$user = getUserInfo();

$hotel = $_GET['id']; 
$from = $_GET['from'];
$to = $_GET['to'];
$guests = $_GET['guests'];
$type = $_GET['type'];

// ----------------------
// -------- HTML --------
// ----------------------

printHTMLheader("Confirm your booking");
printHeader();

// User not logged in, need to make a account
// Or provide necessary info
if ($_SESSION['loggedin'] != 1) {
    print
    ("
        <form action=\"index.php\">
            <div class=\"RegisterForm\">
                <p>Please fill in this form to book your room.</p>
                <hr>

                <label for=\"name\"><b>Name</b></label>
                <input type=\"text\" placeholder=\"Enter Name\" name=\"name\" id=\"name\" required>

                <label for=\"surname\"><b>Surname</b></label>
                <input type=\"text\" placeholder=\"Enter Surname\" name=\"surname\" id=\"surname\" required>

                <label for=\"email\"><b>Email</b></label>
                <input type=\"text\" placeholder=\"Enter Email\" name=\"email\" id=\"email\" required>

                <label for=\"psw\"><b>Password</b></label>
                <input type=\"password\" placeholder=\"Enter Password\" name=\"psw\" id=\"psw\" required>

                <label for=\"psw-repeat\"><b>Repeat Password</b></label>
                <input type=\"password\" placeholder=\"Repeat Password\" name=\"psw-repeat\" id=\"psw-repeat\" required>
                <hr>

                <button type=\"submit\" class=\"registerbtn\">Book and register</button>
            </div>
        </form>
    ");

// User logged in, can make reservation
} else {
    print
    ("
        <div>$hotel</div>
        <div>$from</div>
        <div>$to</div>
        <div>$guests</div>
        <div>$type</div>

        <form action=\"/bookView.php?id=".$hotel."&from=".$from."&to=".$to."&guests=".$guests."&type=".$type."\" method=\"post\">
            <button type=\"submit\" name=\"bookRoom\" class=\"registerbtn\">Book</button>
        </form>
    ");
}

print
("
    </body>
</html>
");