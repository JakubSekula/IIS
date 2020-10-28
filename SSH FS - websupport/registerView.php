<?php
include_once( "config.php" );
include_once( "functions.php" );
include_once( "db.php" );

printHTMLheader( "Sign in" );
printHeader();
?>
<body>

    <form action="registerUser.php" method="post">
        <div class="RegisterForm">
            <h1>Register</h1>
            <p>Please fill in this form to sign in.</p>
            <hr>

            <label for="name"><b>Name</b></label>
            <input type="text" placeholder="Enter Name" name="name" id="name" required>

            <label for="surname"><b>Surname</b></label>
            <input type="text" placeholder="Enter Surname" name="surname" id="surname" required>

            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" id="email" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" id="password" required>

            <label for="psw-repeat"><b>Repeat Password</b></label>
            <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>
            <input type="hidden" name="users" value='1'>
            <hr>

            <button type="submit" class="registerbtn">Register</button>
        </div>
    </form>
    
        <div class="RegisterForm signin">
            <p>Already have an account? <a href="signinView.php">Sign in</a>.</p>
        </div>

</body>

</html>