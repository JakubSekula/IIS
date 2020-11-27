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
session_start();

function getUserInfo(){
    global $connection;

    $sql = "SELECT * FROM User WHERE email='".$_SESSION[ 'email' ]."'";
    $result = $connection->query( $sql );

    if( $result->num_rows > 0 ) {
        while( $row = $result->fetch_assoc() ){
            return $row;
        }
    }
}


function sessionTimeout(){
    session_start();
    if($_SESSION['loggedin']){
        if(time() - $_SESSION['timestamp'] > 300) {
            unset($_SESSION['email'], $_SESSION['id'], $_SESSION['rights']);
            $_SESSION['loggedin'] = false;
            session_destroy();
            echo"<script>alert('You have been logged out due to inactivity');</script>";
            header("Location: signinView.php");
            exit;
        } else {
            $_SESSION['timestamp'] = time();
        }
    }
}


function checkRights($rights){
    if ($rights == 1){
        if (!isset($_SESSION['loggedin']) || !($_SESSION['rights'] == 1)) {
            header('Location: signinView.php');
            exit; 
        }
    } else if($rights == 2){
        if (!isset($_SESSION['loggedin']) || !($_SESSION['rights'] == 1 || $_SESSION['rights'] == 2)) {
            header('Location: signinView.php');
            exit; 
        }
    } else if($rights == 3){
        if (!isset($_SESSION['loggedin']) || !($_SESSION['rights'] == 1 || $_SESSION['rights'] == 2 || $_SESSION['rights'] == 3)) {
            header('Location: signinView.php');
            exit; 
        }
    } else if($rights == 4){
        if (!isset($_SESSION['loggedin']) || !($_SESSION['rights'] == 1 || $_SESSION['rights'] == 2 || $_SESSION['rights'] == 3 || $_SESSION['rights'] == 4)) {
            header('Location: signinView.php');
            exit; 
        }
    }
}

function printHTMLheader($title) {
    
    print("
    <!DOCTYPE html>
    <html lang=\"en\">

    <head>

    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">

    <!-- CSS -->
    <link rel=\"stylesheet\" href=\"style.css\">
    
    <!-- Bootstrap -->
    <link rel=\"stylesheet\" href=\"https://use.fontawesome.com/releases/v5.7.1/css/all.css\">
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css\" integrity=\"sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2\" crossorigin=\"anonymous\">
    <script src=\"jquery-3.5.1.slim.min.js\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx\" crossorigin=\"anonymous\"></script>

    <link rel=\"stylesheet\" href=\"//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css\">
    <link rel=\"stylesheet\" href=\"style.css\">
    <script src=\"scripts.js\"></script>
    <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>
    <script src=\"//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js\"></script>

    <!-- Daterange picker (https://www.daterangepicker.com/) -->
    <script src=\"https://cdn.jsdelivr.net/momentjs/latest/moment.min.js\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js\"></script>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css\" />
    <script src=\"scripts.js\"></script> 

    <link rel=\"stylesheet\" href=\"//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css\">

    <title>Hilton Hotels | $title</title>

    </head>

    ");
}

// Adjusts header visual according to user rights
function printHeader() {

    $user = getUserInfo();
    $name = array_values($user)[1] . " " . array_values($user)[2];
    $hotel_id = htmlspecialchars( $_GET[ "id" ] );
    $hotel = new UserService();

    print
    ("
        <nav class=\"navbar navbar-expand-lg navbar-dark\" style=\"background-color: #003580;\">
        <button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarTogglerDemo01\" aria-controls=\"navbarTogglerDemo01\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
        <span class=\"navbar-toggler-icon\"></span>
        </button>

        <div class=\"collapse navbar-collapse\" id=\"navbarTogglerDemo01\">
            <a class=\"navbar-brand\" href=\"index.php\">Hilton Hotels</a>
            <ul class=\"navbar-nav mr-auto mt-2 mt-lg-0\">
    ");

    // Not logged in
    if( $_SESSION[ 'loggedin' ] != 1 ){
        print
        ("
                </ul>
                <ul class=\"nav navbar-nav navbar-right\">
                    <li><button onclick=\"window.location.href='signinView.php';\" type=\"button\" class=\"btn btn-light\"><i class=\"fas fa-sign-in-alt\"></i> Sign in</button></li>
                    <li><button onclick=\"window.location.href='registerView.php';\" type=\"button\" class=\"btn btn-light\"><i class=\"fas fa-user\"></i> Register</button></li>
                </ul>
        ");
    
    // Logged in
    } else {

        // Admin
        if( $_SESSION['rights'] == 1 ) {
            print
            ("
                <li class=\"nav-item active\">
                    <a class=\"nav-link\" href=\"employeeView.php\">Manage reservations<span class=\"sr-only\">(current)</span></a>
                </li>
                <li class=\"nav-item active\">
                    <a class=\"nav-link\" href=\"admin.php\">Admin page<span class=\"sr-only\">(current)</span></a>
                </li>
            ");
        
        // Hotel owner
        } else if( $_SESSION[ 'rights' ] == 2  ){
            print
            (" 
                <li class=\"nav-item active\">
                    <a class=\"nav-link\" href=\"employeeView.php\">Manage reservations<span class=\"sr-only\">(current)</span></a>
                </li>
                <li class=\"nav-item active\">
                    <a class=\"nav-link\" href=\"admin.php\">Manage my hotels<span class=\"sr-only\">(current)</span></a>
                </li>
            ");
        
        // Receptionist
        } else if( $_SESSION[ 'rights' ] == 3 ){
            print
            ("
                <li class=\"nav-item active\">
                    <a class=\"nav-link\" href=\"employeeView.php\">Manage reservations<span class=\"sr-only\">(current)</span></a>
                </li>
            ");
        }

        print
        ("
            <li class=\"nav-item active\">
                <a class=\"nav-link\" href=\"reservationsView.php\">My reservations<span class=\"sr-only\">(current)</span></a>
            </li>
            </ul>
            <ul class=\"nav navbar-nav navbar-right\">
                <li class=\"nav-item\">
                    <a style=\"font-weight: bold; line-height: 27px; color: white; margin-top: 2%; margin-bottom: 2%;\" class=\"nav-link\" href=\"userView.php\">$name</a>
                </li>
                <li><button onclick=\"window.location.href='logout.php';\" type=\"button\" class=\"btn btn-light\"><i class=\"fas fa-sign-out-alt\"></i> Logout</button></li>
            </ul>
        ");
    }

    print
    ("
            </div>
        </nav>
    ");
}


function printFooter() {
    print
    ("
        <footer class=\"footer\">
            <div class=\"container\">
                <span class=\"text-muted\">Copyright &COPY; IIS 2020</span>
            </div>
        </footer>
    ");
}


function handleLimit( $page ){
    return $page * 10;
}


// pagging for multipage
function createPagging( $page ){

    $pager = "";
    $copy = $page;

    if( $page <= 1 ){
        $pager = $pager . '<li class="page-item"><a class="page-link" href="index.php?page=' . $page . '">Previous</a></li>';
    } else {
        $copy--;
        $pager = $pager . '<li class="page-item"><a class="page-link" href="index.php?page=' . $copy  . '">Previous</a></li>';
        $copy++;
    }

    if( $copy <= 0 ){
        $copy = 1;
        $pager = $pager . '<li class="page-item"><a class="page-link" href="index.php?page=' . $copy . '">' . $copy . '</a></li>';
        $copy ++;
        $pager = $pager . '<li class="page-item"><a class="page-link" href="index.php?page=' . $copy . '">' . $copy . '</a></li>';
        $copy ++;
        $pager = $pager . '<li class="page-item"><a class="page-link" href="index.php?page=' . $copy . '">' . $copy . '</a></li>';
        $copy--;
        $pager = $pager . '<li class="page-item"><a class="page-link" href="index.php?page=' . $copy . '">next</a></li>';
    } else {
        $pager = $pager . '<li class="page-item"><a class="page-link" href="index.php?page=' . $copy . '">' . $copy . '</a></li>';
        $copy ++;
        $pager = $pager . '<li class="page-item"><a class="page-link" href="index.php?page=' . $copy . '">' . $copy . '</a></li>';
        $copy++;
        $pager = $pager . '<li class="page-item"><a class="page-link" href="index.php?page=' . $copy . '">' . $copy . '</a></li>';
        $copy--;
        $pager = $pager . '<li class="page-item"><a class="page-link" href="index.php?page=' . $copy . '">next</a></li>';
    }

    return $pager;

}

?>