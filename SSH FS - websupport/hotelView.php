<?php

include_once( "services.php" );

// ----------- Hotel general -----------

$hotel = new HotelService();
$hotel_id = htmlspecialchars($_GET["id"]);

list($hotel_name, $hotel_stars, $hotel_description) = $hotel->getHotelInfo($hotel_id);

// --------------- Stars ---------------

$star = "<i style=\"font-size: 30px; color: #FDBF23;\" class=\"fas fa-star\"></i>";
$print_stars = str_repeat($star, $hotel_stars);

// ---------- Date and guests ----------

$dateFrom = htmlspecialchars($_GET['from']);
$dateTo = htmlspecialchars($_GET['to']);
$guests = htmlspecialchars($_GET['guests']);

if ($dateFrom && $dateTo) {
    $dateFrom = date("m-d-Y", strtotime($dateFrom));
    $dateTo = date("m-d-Y", strtotime($dateTo));
} else {
    $dateFrom = date("Y-m-d");
    $dateTo = date("Y-m-d", strtotime($dateFrom . " + 1 days"));
    $dateFrom = date("m-d-Y", strtotime($dateFrom));
    $dateTo = date("m-d-Y", strtotime($dateTo));
}

// ---------------- Rooms ---------------

list($room_type, $room_price, $room_descr) = $hotel->getHotelRooms($hotel_id, $dateFrom, $dateTo, $guests);

// Generate code for printing rooms
$a = count($room_type);
$room_begin = "<div class=\"section\">
               <h2 style=\"font-weight: bold;\">Rooms</h2>";

for ($i = 0; $i < count($room_type); $i++) {
    $type = $room_type[$i];
    $beds = $room_beds[$i];
    $price = $room_price[$i] * $guests;
    $descr = $room_descr[$i];
    $room_main .= "
    <div class=\"row\">
        <div class=\"col-6\">
            <h3 style=\"font-weight: bold;\">$type</h3>
            <p class=\"mb-0 justify-text\">$descr</p>
        </div>
    
        <div class=\"col\">
            <h5 style=\"font-weight: bold;\">Price</h5>
            <p class=\"mb-0\">$price € / night</p>
            <a href=\"/bookView.php?id=".$hotel_id."&from=".$dateFrom."&to=".$dateTo."&guests=".$guests."&type=".$type."\" class=\"btn btn-primary active\" role=\"button\" aria-pressed=\"true\">Book</a>
        </div>
    </div>";
}

$print_rooms = $room_begin . $room_main . "</div>";


// -------------- Reviews --------------

$result = $hotel->getHotelReviews($hotel_id);

$reviews_note = array();
$reviews_rating = array();
$reviews_name = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        array_push($reviews_note, $row["note"]);
        array_push($reviews_rating, $row["rating"]);
        array_push($reviews_name, $row["name"]);
    }
}

$star_full = "<i style=\"font-size: 10px;\" class=\"fas fa-star\"></i>\n";
$star_empty = "<i style=\"font-size: 10px;\" class=\"far fa-star\"></i>\n";

for ($i = 0; $i < count($reviews_rating); $i++) {
    $name = array_values($reviews_name)[$i];
    $note = array_values($reviews_note)[$i];
    $rating = str_repeat($star_full, array_values($reviews_rating)[$i]);
    $rating = $rating . str_repeat($star_empty, 5 - array_values($reviews_rating)[$i]);
    $review = $review . "<blockquote class=\"blockquote text-center\">
                        <p class=\"mb-0\">$note</p>
                        <p class=\"mb-0\">$rating</p>
                        <footer class=\"blockquote-footer\">$name</cite></footer>
                        </blockquote>";
}

$hotel_image = $hotel->getTitleImg($hotel_id);
$hotel_user_rating = $hotel->getAvgHotelRating($hotel_id);

// ----------------------------------
// -------------- HTML --------------
// ----------------------------------

printHTMLheader($hotel_name);

print
("
        <style>

        .hotel-main {
            margin: 0px auto;
            max-width: 990px;
        }

        .hotel-img-container {
            margin-top: 20px;
            margin-bottom: 20px;

        }

        .hotel-img {
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            height: 100%;
        }

        .img {
            max-height: 400px;
            object-fit: cover;
        }

        .hotelRating {
            text-align: center;
            float: right;
            background-color: #003580;
            height: 80px;
            width: 80px;
            border-radius: 50%;
        }

        .hotelRating h2 {
            margin-top: 25%;
            color: white;
        }

        .section {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .justify-text {
            text-align: justify;
            text-justify: inter-word;
        }

    </style>
</head>

<body>
");

printHeader();

print
("
    <div class=\"container-fluid hotel-main\">
        
        <div class=\"hotel-img-container\">
            <div class=\"hotel-img\">
                <img src=\"$hotel_image\" class=\"img img-fluid container-fluid no-padding\">
            </div>
        </div>

        <div class=\"container\">
            <div class=\"row\">
                <div class=\"col-6\">
                    <div class=\"hotel-name\">
                        <h1 style=\"font-weight: bold;\">$hotel_name</h1>
                        $print_stars
                    </div>
                </div>
                <div class=\"col\">
                    <div class=\"hotelRating\">
                        <h2>".$hotel_user_rating."</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <hr>
        
        <div class=\"section\">
            <p class=\"lead justify-text\">
                $hotel_description
            </p>
        </div>
");   

// Check availability
print
("
        <div class=\"row\">
            <div class=\"col-6\">
                <input type=\"text\" name=\"daterange\" value=\"$dateFrom - $dateTo\"/>
                <script>
                    $(function() {
                        $('input[name=\"daterange\"]').daterangepicker({
                            opens: 'left'
                        }, function(start, end, label) {
                            console.log(\"A new date selection was made: \" + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                        });
                    });
                </script>
            </div>
            
            <div class=\"col\">
                <button type=\"button\" class=\"btn btn-blue btn-dark\">Check avalability</button>
            </div>
        </div>
            
");

print($print_rooms);

print
("
                <div class=\"section\">
                    <h2 style=\"font-weight: bold;\">Reviews</h2>
                    $review
                </div>
            </div>
        </body>
</html>
");

printFooter();

?>