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

    printHTMLheader( "Sign in" );
    printHeader();
?>
    <form action="register.php" method="post">
        <div class="RegisterForm">
            <h1>Register</h1>
            <p>Please fill in this form to sign in.</p>
            <hr>

            <label for="name" class="required"><b>Name</b></label>
            <?php 
            
            if( isset( $_GET[ 'name' ] ) ){
                echo "<input type=\"text\" value=\"".$_GET['name']."\" name=\"name\" id=\"name\" required>";
            } else {
                echo "<input type=\"text\" placeholder=\"Enter Name\" name=\"name\" id=\"name\" required>";
            }
            
            echo "<label for=\"surname\" class=\"required\"><b>Surname</b></label>";
            
            if( isset( $_GET[ 'surname' ] ) ){
                echo "<input type=\"text\" value=\"".$_GET['surname']."\" name=\"surname\" id=\"surname\" required>";
            } else {
                echo "<input type=\"text\" placeholder=\"Enter surname\" name=\"surname\" id=\"surname\" required>";
            }
            
            echo "<label for=\"email\" class=\"required\"><b>Email</b></label>";
            
            if( isset( $_GET[ 'email' ] ) ){
                echo "<input type=\"text\" value=\"".$_GET['email']."\" name=\"email\" id=\"email\" required>";
            } else {
                echo "<input type=\"text\" placeholder=\"Enter Email\" name=\"email\" id=\"email\" required>";
            }
            
            ?>
            
            
            <label for="password" class="required"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" id="password" required>

            <label for="psw-repeat" class="required"><b>Repeat Password</b></label>
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