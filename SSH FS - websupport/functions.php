<?php

include_once( "config.php" );
include_once( "services.php" );

function printHTMLheader($title) {
    
    print("
    <!DOCTYPE html>
    <html>

    <head>

    <meta charset=\"utf-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, shrink-to-fit=no\">

    <!-- CSS -->
    <link rel=\"stylesheet\" href=\"style.css\">
    
    <!-- Bootstrap -->
    <link rel=\"stylesheet\" href=\"https://use.fontawesome.com/releases/v5.7.1/css/all.css\">
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css\" integrity=\"sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2\" crossorigin=\"anonymous\">
    <script src=\"https://code.jquery.com/jquery-3.5.1.slim.min.js\" integrity=\"sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj\" crossorigin=\"anonymous\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js\" integrity=\"sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx\" crossorigin=\"anonymous\"></script>

    <!-- Daterange picker (https://www.daterangepicker.com/) -->
    <script type=\"text/javascript\" src=\"https://cdn.jsdelivr.net/jquery/latest/jquery.min.js\"></script>
    <script type=\"text/javascript\" src=\"https://cdn.jsdelivr.net/momentjs/latest/moment.min.js\"></script>
    <script type=\"text/javascript\" src=\"https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js\"></script>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css\" />

    <title>Hilton Hotels | $title</title>
    ");
}

function printHeader() {

    $user = getUserInfo();
    $name = array_values($user)[1] . " " . array_values($user)[2];
    $hotel_id = htmlspecialchars( $_GET[ "id" ] );
    $hotel = new HotelService();

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

    if( $_SESSION[ 'loggedin' ] != 1 ){
        print
        ("
                </ul>
                <ul class=\"nav navbar-nav navbar-right\">
                    <button onclick=\"window.location.href='signinView.php';\" type=\"button\" class=\"btn btn-light\"><i class=\"fas fa-sign-in-alt\"></i> Sign in</button>
                    <button onclick=\"window.location.href='registerView.php';\" type=\"button\" class=\"btn btn-light\"><i class=\"fas fa-user\"></i> Register</button>
                </ul>
        ");
    } else {
        if (!$_SESSION['rights']){
            print
            ("
                </ul>
                <ul class=\"nav navbar-nav navbar-right\">
                    <li class=\"nav-item\">
                        <a style=\"font-weight: bold; color: white; margin-top: 2%; margin-bottom: 2%;\" class=\"nav-link\" href=\"userView.php\">$name</a>
                    </li>
                    <button onclick=\"window.location.href='logout.php';\" type=\"button\" class=\"btn btn-light\"><i class=\"fas fa-sign-out-alt\"></i> Logout</button>
                </ul>
            ");

        } else if( $_SESSION['rights'] == 1 ) {
            print
            ("
                <li class=\"nav-item active\">
                    <a class=\"nav-link\" href=\"admin.php\">Admin page<span class=\"sr-only\">(current)</span></a>
                </li>
            ");
            if( $hotel_id != "" ){
                print
                (" 
                    <li class=\"nav-item active\">
                        <a class=\"nav-link\" href=\"editor.php?type=Hotel&id=".$_GET['id']."\">Manage this hotel<span class=\"sr-only\">(current)</span></a>
                    </li>
                ");
            }
        } else if( $_SESSION[ 'rights' ] == 2 ){
            if( $hotel_id == "" ){
                print
                (" 
                    <li class=\"nav-item active\">
                        <a class=\"nav-link\" href=\"admin.php\">Manage my hotels<span class=\"sr-only\">(current)</span></a>
                    </li>
                ");
            } else {
                if( $hotel->checkOwnership( $_SESSION[ 'id' ], $hotel_id ) ){
                    print
                    ("
                        <li class=\"nav-item active\">
                            <a class=\"nav-link\" href=\"editor.php?type=Hotel&id=".$_GET['id']."\">Manage this hotel<span class=\"sr-only\">(current)</span></a>
                        </li>
                    ");
                } else {

                }
            }
        } else if( $_SESSION[ 'rights' ] == 3 ){
            if( $hotel_id == "" ){
                print
                (" 
                    <li class=\"nav-item active\">
                        <a class=\"nav-link\" href=\"employeeView.php\">Manage hotel<span class=\"sr-only\">(current)</span></a>
                    </li>
                ");
            } else {
                if( $hotel->checkEmployment( $_SESSION[ 'id' ], $hotel_id ) ){
                    print
                    ("
                        <li class=\"nav-item active\">
                            <a class=\"nav-link\" href=\"editor.php?type=Hotel&id=".$_GET['id']."\">Manage this hotel<span class=\"sr-only\">(current)</span></a>
                        </li>
                    ");
                } else {

                }
            }
        }

        print
        ("
            </ul>
            <ul class=\"nav navbar-nav navbar-right\">
                <li class=\"nav-item\">
                    <a style=\"font-weight: bold; color: white; margin-top: 2%; margin-bottom: 2%;\" class=\"nav-link\" href=\"userView.php\">$name</a>
                </li>
                <button onclick=\"window.location.href='logout.php';\" type=\"button\" class=\"btn btn-light\"><i class=\"fas fa-sign-out-alt\"></i> Logout</button>
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
            <span class=\"text-muted\">IIS 2020</span>
        </div>
    </footer>
    ");
}

?>