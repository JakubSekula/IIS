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

$employed = getUserInfo();

if( $userInfo[ 'address' ] == "" ){
    $address = "";
} else {
    $address = $userInfo[ 'address' ];
}

$phone = "";

if( $userInfo[ 'phone_number' ] == "" ){
    $phone = "";
} else {
    $phone = $userInfo[ 'phone_number' ];
}


if(isset($_GET['act'])){
    echo "<div class='confirm' id='confirm'> <h5> Data updated</h5> </div>";
    echo "<script>$(document).ready( function () {";
    echo "setTimeout(function(){ $('#confirm').css('display', 'none'); }, 2000);});</script>";
        
}

print
    ("
        <style>

        </style>
        </head>

        <body>

        <div class=\"row ktopmargin\">
            <div class=\"col-4\">
            </div>
                <div class=\"col-5\">
                    <h2>$name</h2>
                </div>
            <div class=\"col-3\">
            </div>
        </div>

        <form class=\"kformspecial\" action=\"update.php\" method=\"post\">
        <div class=\"row kmarg\">
            <div class=\"col-4\">
            </div>
            <div class=\"col-4\">
                <hr>
                <div class=\"row\">
                    <div class=\"col-6\">
                        <label for=\"exampleInputEmail1\">Name</label>
                        <div class=\"input-group mb-3\">
                            <input type=\"text\" class=\"form-control\" name=\"name\" aria-label=\"Default\" value=".$userInfo[ 'name' ]." aria-describedby=\"inputGroup-sizing-default\">
                        </div>
                    </div>
                        <div class=\"col-6\">
                            <label for=\"exampleInputEmail1\">Surname</label>
                            <div class=\"input-group mb-3\">
                                <input type=\"text\" class=\"form-control\" name=\"surname\" value=".$userInfo[ 'surname' ]." aria-label=\"Default\" aria-describedby=\"inputGroup-sizing-default\">
                            </div>
                        </div>
                </div>
            </div>
            <div class=\"col-4\">
            </div>
        </div>

        <div class=\"row kmarg\">
            <div class=\"col-4\">
            </div>
                <div class=\"col-4\">
                    <label for=\"exampleInputEmail1\">E-mail</label>
                    <div class=\"input-group mb-3\">
                        <input type=\"text\" class=\"form-control\" aria-label=\"Default\" name=\"email\" value=".$userInfo[ 'email' ]." aria-describedby=\"inputGroup-sizing-default\" readonly>
                    </div>
                </div>
            </div>
            <div class=\"col-4\">
            </div>
        </div>

        <div class=\"row kmarg\">
            <div class=\"col-4\">
            </div>
                <div class=\"col-4\">
                    <label for=\"exampleInputEmail1\">Phone number</label>
                    <div class=\"input-group mb-3\">
                        <input type=\"text\" class=\"form-control\" aria-label=\"Default\" name=\"phone_number\" value=\"$phone\" aria-describedby=\"inputGroup-sizing-default\">
                    </div>
                </div>
            </div>
            <div class=\"col-4\">
            </div>
        </div>

        <div class=\"row kmarg\">
            <div class=\"col-4\">
            </div>
                <div class=\"col-4\">
                    <label for=\"exampleInputEmail1\">Address</label>
                    <div class=\"input-group mb-3\">
                        <input type=\"text\" class=\"form-control\" aria-label=\"Default\" name=\"address\" value=\"$address\" aria-describedby=\"inputGroup-sizing-default\">
                    </div>
                </div>
            </div>
            <div class=\"col-4\">
            </div>
        </div>

        <div class=\"row kmarg\">
            <div class=\"col-4\">
            </div>
            <div class=\"col-4\">
            <input type=\"hidden\" id=\"custId\" name=\"Users\" value=\"Update\">
            <input type=\"hidden\" name=\"id\" value=" . $_SESSION[ 'id' ] . ">
            <input type=\"hidden\" name=\"rights\" value=" . $_SESSION[ 'rights' ] . ">
            <input type=\"hidden\" name=\"employed\" value=" . $employed[ 'employed' ] . ">
            <button type=\"submit\" class=\"btn btn-primary kupdate\">Update</button>
            </div>
            <div class=\"col-4\">
            </div>
        </div>
        </form>

        <hr>

        <form class=\"kformspecial\" action=\"update.php\" method=\"post\">

        <div class=\"row ktopmargin\">
            <div class=\"col-4\">
            </div>
                <div class=\"col-5\">
                    <h2>Change password</h2>
                </div>
            <div class=\"col-3\">
            </div>
        </div>

        <div class=\"row kmarg\">
            <div class=\"col-4\">
            </div>
                <div class=\"col-4\">
                    <label for=\"exampleInputEmail1\">Current Password</label>
                    <div class=\"input-group mb-3\">
                        <input type=\"password\" class=\"form-control\" aria-label=\"Default\" name=\"oldpasswrd\" value=\"\" aria-describedby=\"inputGroup-sizing-default\">
                    </div>
                </div>
            </div>
            <div class=\"col-4\">
            </div>
        </div>

        <div class=\"row kmarg\">
            <div class=\"col-4\">
            </div>
                <div class=\"col-4\">
                    <label for=\"exampleInputEmail1\">New Password</label>
                    <div class=\"input-group mb-3\">
                        <input type=\"password\" class=\"form-control\" aria-label=\"Default\" name=\"newpasswrd\" value=\"\" aria-describedby=\"inputGroup-sizing-default\">
                    </div>
                </div>
            </div>
            <div class=\"col-4\">
            </div>
        </div>

        <div class=\"row kmarg\">
            <div class=\"col-4\">
            </div>
            <div class=\"col-4\">
            <input type=\"hidden\" id=\"custId\" name=\"Users\" value=\"psw\">
            <input type=\"hidden\" name=\"id\" value=" . $_SESSION[ 'id' ] . ">
            <input type=\"hidden\" name=\"rights\" value=" . $_SESSION[ 'rights' ] . ">
            <input type=\"hidden\" name=\"employed\" value=" . $employed[ 'employed' ] . ">
            <button type=\"submit\" class=\"btn btn-primary kupdate\">Change password</button>
            </div>
            <div class=\"col-4\">
            </div>
        </div>
        </form>
    ");


?>

</body>
</html>