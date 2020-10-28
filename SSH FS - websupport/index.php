<?php

        include "config.php";
        include "services.php";
        printHTMLheader( "Hotels" );
        printHeader();

        $from = htmlspecialchars( $_GET[ 'fromDate' ] );
        $to = htmlspecialchars( $_GET[ 'toDate' ] );
        $city = htmlspecialchars( $_GET[ 'search' ] );
        $guests = htmlspecialchars( $_GET[ 'guests' ] );

        if( $guests == "" ){
            $guests = 1;
        }

    print
    ("
    <style>

        .card-img, .card-img-top{
            max-height: 233px;
            height: 233px;
        }

        .circle {
            float: right;
            width: 40px;
            height: 40px;
            line-height: 40px;
            border-radius: 50%;
            font-size: 17px;
            color: #fff;
            text-align: center;
            background: #0069D9
        }

        .card{
            margin: 0 auto;
        }

        .fa-star{
            color: #F6D000;
        }

        .form-control{
            margin-top: 15px !important;
        }

        .row{
            margin-right: 0%;
        }

        #ksubbtn{
        }

        .card-text{
            height: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 4; /* number of lines to show */
            -webkit-box-orient: vertical;
        }

        .kdatepick{
            padding: 0% !important;
            margin-top: 15px !important;
            padding-top: 7px !important;
            padding-bottom: 7px !important;
        }

        #search{
            margin-left: 0%;
            margin: 0 auto;
            padding-right: 0%;
        }

        #ksubbtn{
            margin-top: 15px;
        }

        #khumanFont{
            margin-top: 15px;
            margin-left: 0px;
            padding: 9px;
            padding-left: 24px;
            padding-right: 20px;
        }

        </style>
        </head>

        <body>
       
        ");
        print
        ("
            <div class=\"row\">
                <div class=\"col-2\">
                </div>
                <div class=\"col-8\">
                    <form action=\"index.php\">
                    <div class=\"row\" style=\"margin-top:25px;\">
                        <div class=\"col-5\" style=\"margin-right: 0%; padding-right: 0%;\">
                        <div class=\"input-group mb-3\">
                            <input type=\"text\" style=\"min-width: 200px;\" name=\"search\" id=\"search\" class=\"form-control\" placeholder=\"Los Angeles\" aria-label=\"Username\" aria-describedby=\"basic-addon1\">
                            <input type=\"hidden\" id=\"fromDate\" name=\"fromDate\" value=\"\">
                            <input type=\"hidden\" id=\"toDate\" name=\"toDate\" value=\"\">
                        </div>
                        </div>
                        <div class=\"col-3\" style=\"padding-left: 0%; padding-right: 0%;\">
                        <input type=\"text\" class=\"kdatepick\" name=\"daterange\" value=\"Select date\" />
                        ");
                        ?>
                            <script>
                                $(function() {
                                    $('input[name=\"daterange\"]').daterangepicker({
                                        opens: 'left'
                                    }, function(start, end, label) {
                                        var x = start.format( 'YYYY-MM-DD' );
                                        var y = end.format( 'YYYY-MM-DD' );
                                        var str1 = "index.php?from=";
                                        var str2 = "&to=";
                                        
                                        var link = str1.concat( x );
                                        link = link.concat( str2 );
                                        link = link.concat( y );
                                        
                                        document.getElementById( "fromDate" ).value = x;
                                        document.getElementById( "toDate" ).value = y;
                                    });
                                });
                            </script>
                        <?php
                        print("
                        </div>
                        <div class=\"col-1\" style=\"padding-left: 0%; padding-right: 0%;\">
                            <i class=\"fas fa-user-friends fa-lg\" id=\"khumanFont\"></i>
                        </div>
                        <div class=\"col-1\" style=\"padding-left: 0%; padding-right: 0%;\">
                            <select id=\"guests\" name=\"guests\" class=\"form-control\">
                                <option selected>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                            </select>
                        </div>
                        <div class=\"col-2\" style=\"padding-left: 0%;\">
                            <input type=\"submit\" class=\"btn btn-primary\" id=\"ksubbtn\" value=\"View offers\"> 
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class=\"hotelContainer\">
            <div class=\"innerContainer\">
        ");

        $Hotel = new HotelService();

        $today= new DateTime( 'today' );
        $today = $today->format( 'Y-m-d' );

        if( $from == "" ){
            $from = $today;
        }

        $tomorrow = new DateTime( 'tomorrow' );
        $tomorrow = $tomorrow->format( 'Y-m-d' );
        
        if( $to == "" ){
            $to = $tomorrow;
        }

        if( $city == "" ){
            $sql = "SELECT * FROM Hotel WHERE id != 0";
        } else {
            $sql = "SELECT * FROM Hotel WHERE id != 0 AND city = '$city'";
        }

        $result = $connection->query( $sql );

        if( $result->num_rows > 0 ){
            $rowcount = 0;
            while( $row = $result->fetch_assoc() ){

                $path = "";

                $i = 0;
                $stars = "";
                while( $i < $row[ 'stars' ] ){
                    $stars = $stars . "<i class=\"fas fa-star\"></i>";
                    $i++;
                }

                $hotel = $row[ 'id' ];

                $path = $Hotel->getTitleImg( $hotel );

                $user_rating = $Hotel->getAvgHotelRating( $hotel );

                $sql = "SELECT MIN( price ) AS price FROM Room WHERE hotel_id = $hotel";

                $price = $connection->query( $sql );
                $lowest_price = 0;

                if( $price->num_rows > 0 ){
                    while( $thisprice = $price->fetch_assoc() ){
                        $lowest_price = $thisprice[ 'price' ];
                    }
                }

                $lowest_price = $lowest_price ." ". Eur;
                
                if( $rowcount == 0 || $rowcount % 3 == 0 ){
                    echo "</div>";
                    echo "<div class=\"row\" style=\"max-width: 1300px; margin: 0 auto; margin-bottom: 5%;\">";
                }

                print
                ("
                <div class=\"col-md-4\">
                    <div class=\"card\" style=\"width: 22rem;\">
                        <img class=\"card-img-top\" src=\"$path\" alt=\"Card image cap\">
                        <div class=\"card-body\">
                            <div class=\"row\">
                                <div class=\"col-md-7\"><h4>".$row[ 'name' ]."</h2></div>
                                <div class=\"col-md-5\" style=\"text-align: right;\">
                                    $stars
                                </div>
                            </div>
                            <h6>".$row[ 'city' ].", ".$row[ 'country' ]."</h6>
                            <p class=\"card-text\">".$row[ 'description' ]."</p>
                            <div class=\"row\">
                                <div class=\"col-md-9\">
                                    <a href=\"/hotelView.php?id=".$row[ 'id' ]."&from=".$from."&to=".$to."&guests=".$guests."\" class=\"btn btn-primary\">Visit hotel</a>
                                </div>
                                <div class=\"col-md-3\">
                                    <div class=\"circle\">
                                        ".$user_rating."
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ");
                $rowcount++;
            }
        }
        ?>
        </div>
    </div>

    <?php
        printFooter();
    ?>

</body>

</html>