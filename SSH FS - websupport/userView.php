<?php

include_once( "config.php" );
include_once( "services.php" );

printHTMLheader( "Hotels" );
printHeader();

$user = getUserInfo();
$name = array_values( $user )[ 1 ] . " " . array_values( $user )[ 2 ];

$Hotel = new HotelService();

$userInfo = $Hotel->GetUser( $_SESSION[ 'id' ] );

print( $userInfo );

print
    ("
        <style>

        </style>
        </head>

        <body>

        <div class=\"row ktopmargin\">
            <div class=\"col-4\">
            </div>
                <div class=\"col-7\">
                    <h2>Osobní údaje uživatel $name</h2>
                </div>
            <div class=\"col-3\">
            </div>
        </div>


        <div class=\"row kmarg\">
            <div class=\"col-4\">
            </div>
            <div class=\"col-4\">
                <hr>
                <div class=\"row\">
                    <div class=\"col-6\">
                        <div class=\"input-group mb-3\">
                            <input type=\"text\" class=\"form-control\" aria-label=\"Default\" placeholder=\"Name\" aria-describedby=\"inputGroup-sizing-default\">
                        </div>
                    </div>
                        <div class=\"col-6\">
                            <div class=\"input-group mb-3\">
                                <input type=\"text\" class=\"form-control\" placeholder=\"Surname\" aria-label=\"Default\" aria-describedby=\"inputGroup-sizing-default\">
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
                    <div class=\"input-group mb-3\">
                        <input type=\"text\" class=\"form-control\" aria-label=\"Default\" placeholder=\"Email\" aria-describedby=\"inputGroup-sizing-default\">
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
                    <div class=\"input-group mb-3\">
                        <input type=\"text\" class=\"form-control\" aria-label=\"Default\" placeholder=\"Phone number\" aria-describedby=\"inputGroup-sizing-default\">
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
                    <div class=\"input-group mb-3\">
                        <input type=\"text\" class=\"form-control\" aria-label=\"Default\" placeholder=\"Address\" aria-describedby=\"inputGroup-sizing-default\">
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
                    <div class=\"input-group mb-3\">
                        <input type=\"text\" class=\"form-control\" aria-label=\"Default\" placeholder=\"Email\" aria-describedby=\"inputGroup-sizing-default\">
                    </div>
                </div>
            </div>
            <div class=\"col-4\">
            </div>
        </div>

    ");




?>

</body>
</html>