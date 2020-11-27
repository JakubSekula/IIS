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
        include_once("services.php");
        include_once("functions.php");
        printHTMLheader( "Hotels" );
        
        echo "<body onload=\"brow()\">";
        
        printHeader();


        // setting for page nav

        if( isset( $_GET[ 'page' ] ) ){
            $page = $_GET[ 'page' ];
        } else {
            $page = 1;

            if( $page == "" || $page < 0 ){ 
                $page = 1;
            }
        }

        $limit = handleLimit( $page );

        $Hotel = new HotelService();

        // parameters from url
        $from = htmlspecialchars( $_GET[ 'fromDate' ] );
        $to = htmlspecialchars( $_GET[ 'toDate' ] );
        $city = htmlspecialchars( $_GET[ 'search' ] );
        $guests = htmlspecialchars( $_GET[ 'guests' ] );
        $page = htmlspecialchars( $_GET[ 'page' ] );
        $daterange = htmlspecialchars( $_GET[ 'daterange' ] );

        // selection for filter
        $filters = array( "stars", "name", "rating" );
        $checkbox = $Hotel->getEquipment();

        $today= new DateTime( 'today' );
        $today = $today->format( 'm/d/Y' );

        $tomorrow = new DateTime( 'tomorrow' );
        $tomorrow = $tomorrow->format( 'm/d/Y' );


        // filters for search
        $place = $city;

        if( $place == "" ){
            $place = "Las Vegas";
        }

        // daterange parameter for datepicker
        if( $daterange == "" ){
            $daterange = $today . " - " . $tomorrow;
        }

        // default value vor picker
        if( $guests == "" ){
            $guests = 1;
        }

        if( $_GET[ 'daterange' ] ){
            $dateRange = explode( ' - ', $_GET[ 'daterange' ] );
            $dateFrom = $dateRange[ 0 ];
            $dateTo = $dateRange[ 1 ];
        }
    
        // sql search parameters if none were given
        if( !isset( $dateFrom ) ){
            $dateFrom = "11/19/1990";
            $dateTo = "11/19/2050";
        }

        // options for guests
        $options = "";
        $i = 1;
        while( $i <= 8 ){
            if( $i != $guests ){
                $options = $options . "<option>$i</option>";
            } else {
                $options = $options . "<option selected>$i</option>";
            }
            $i++;
        }

        $sort = "";

        foreach( $filters as $value ){
            $sort = $sort . '<a class="dropdown-item" href="?sortby=' . $value .'">' . $value. '</a>';
        }


        $sortBy = htmlspecialchars( $_GET[ 'sortby' ] );

        print
        ('

            <div class="row krow">
                <div class="col-2">

                </div>
                <div class="col-8">
                    <form action="index.php">
                    <div class="row krow" style="margin-top:25px;">
                        <div class="col-5 textinputer" style="margin-right: 0%; padding-right: 0%;">
                        
                        <div class="input-group mb-3">
                            <input type="text" style="min-width: 200px;" name="search" id="ksearch" class="form-control kform-control" placeholder= "' . $place . '" aria-label="Username" aria-describedby="ksearch">
                        </div>
                        </div>
                        <div class="col-3 datepic" style="padding-left: 0%; padding-right: 0%;">
                        <input type="text" class="kdatepick" name="daterange" value="'.$daterange.'" />
                        ');
                        ?>
                            <script>
                                $(function() {
                                    $('input[name=\"daterange\"]').daterangepicker({
                                        "minDate": "<?php print($today); ?>"
                                    }, function(start, end, label) {
                                    });
                                });
                            </script>
                        <?php
                        print("
                        </div>
                        <div class=\"col-1 minwidth\" style=\"padding-left: 0%; padding-right: 0%;\">
                            <i class=\"fas fa-user-friends fa-lg\" id=\"khumanFont\"></i>
                        </div>
                        <div class=\"col-1 minwidthpicker\" style=\"padding-left: 0%; padding-right: 0%;\">
                            
                        <select id=\"guests\" name=\"guests\" class=\"form-control kform-control minwidth2\">
                                $options
                            </select>
                        </div>
                        <div class=\"col-2 viewoffer\" style=\"padding-left: 0%;\">
                            <input type=\"submit\" class=\"btn btn-primary minwidth3\" id=\"ksubbtn\" value=\"View offers\">
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class=\"dropdown\">
            <button class=\"btn btn-secondary dropdown-toggle sortbybtn\" style=\"background-color: #003580; margin-bottom: 50px; margin-left: 25px;\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                Sort by: 
            </button>
            <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                " . $sort . "
            </div>
            ");
            print('
            </div>

            <div class="hotelContainer">
            <div class="innerContainer">

        ');

        if( !in_array( $sortBy, $filters ) ){
            $sortBy = "";
        }

        $avaiableRoom = 0;

        // hotel sql query
        $sql = $Hotel->createSQLForHotels( $city, $sortBy, $limit - 10, $limit );

        $result = $connection->query( $sql );

        $daterange = str_replace( " ", "", $daterange );
        $daterange = str_replace( "/", "%2F", $daterange );
        $daterange = str_replace( "-", "+-+", $daterange );

        if( $result->num_rows > 0 ){
            $rowcount = 0;
            // for every hotel
            while( $row = $result->fetch_assoc() ){

                $path = "";

                $i = 0;
                $stars = "";

                while( $i < $row[ 'stars' ] ){
                    $stars = $stars . "<i class=\"fas fa-star\"></i>";
                    $i++;
                }

                $hotel = $row[ 'id' ];


                if( isset( $_GET[ 'daterange' ] ) ){                    
                    if( count( $Hotel->getAvailableRooms( $hotel, $dateFrom, $dateTo, $guests )[ 0 ] ) == 0 ){
                        // if hotel has none rooms left
                        continue;
                    }
                } else {
                    $avaiableRoom++;
                }

                // path to title image
                $path = $Hotel->getTitleImg( $hotel );

                // get user rating 
                $user_rating = $Hotel->getAvgHotelRating( $hotel );
                
                // only 3 hotels can be at one line
                if( $rowcount == 0 || $rowcount % 3 == 0 ){
                    echo "</div>";
                    echo "<div class=\"row krow\" id=\"danceparty\" style=\"max-width: 1300px; margin: 0 auto; margin-bottom: 5%;\">";
                }

                print
                ("
                <div class=\"col-md-4\">
                    <div class=\"card pic\">
                        <img class=\"card-img-top kcard-img-top\" src=\"$path\" alt=\"Card image cap\">
                        <div class=\"card-body\">
                            <div class=\"row krow\">
                                <div class=\"col-md-7\"><h4 class=\"konelinerheader\" onmouseover=\"showHotelName( this )\" onmouseout=\"hideHotelName( this )\">".$row[ 'name' ]."</h4></div>
                                <div class=\"col-md-5\" style=\"text-align: right;\">
                                    $stars
                                </div>
                            </div>
                            <h6>".$row[ 'city' ].", ".$row[ 'country' ]."</h6>
                            <p class=\"kcard-text\">".$row[ 'description' ]."</p>
                            <div class=\"row krow\">
                                <div class=\"col-md-9\">
                                    <a href=\"/hotelView.php?daterange=" . $daterange . "&guests=".$guests."&id=" . $hotel . "\" class=\"btn btn-primary\">Visit hotel</a>
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
        } else {
            $Hotel->noHotelsFound();
            $avaiableRoom++;
        }


        if( $avaiableRoom == 0 ) {
            $Hotel->noHotelsFound();
        } 

        $pager = createPagging( $page );

        print
        ('
        </div>
            <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <nav aria-label="Page navigation example">
                    <ul class="pagination kpaganition">
                    ' . $pager . '
                    </ul>
                </nav>
            </div>
            <div class="col-md-4">
            </div>
        ')

        ?>
        </div>
    </div>

    <?php
        printFooter();
    ?>

</body>

</html>
