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
    <form action="authenticate.php" method="post">
        <div class="RegisterForm">
            <h1>Sign in</h1>
            <p>Please fill in this form to Sign in.</p>
            <hr>

            <label class="required" for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" id="email" required>

            <label class="required" for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" id="password" required>

            <hr>

            <button type="submit" class="registerbtn">Sign in</button>
        </div>
    
        <div class="RegisterForm signin">
            <p>Don't have an account? <a href="registerView.php">Register</a>.</p>
        </div>
    </form>  

</body>

</html>