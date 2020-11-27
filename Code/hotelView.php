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

// ----------- Hotel general -----------

$hotel = new HotelService();
$user = new UserService();
$hotel_id = $_GET["id"];

list($hotel_name, $hotel_stars, $hotel_description) = $hotel->getHotelInfo($hotel_id);

$star = "<i style=\"font-size: 30px; color: #FDBF23;\" class=\"fas fa-star\"></i>";
$print_stars = str_repeat($star, $hotel_stars);

// --------------- Date ----------------

if ($_GET['daterange']) {
    $dateRange = explode(' - ', $_GET['daterange']);
    $dateFrom = strtotime($dateRange[0]);
    $dateTo = strtotime($dateRange[1]);
}

// Date is provided with valid format
if (($dateFrom && $dateTo) && ($dateFrom < $dateTo)) {
    $dateFrom = date("m-d-Y", $dateFrom);
    $dateTo = date("m-d-Y", $dateTo);

// Date was not provided or was in wrong format
} else {
    $dateFrom = date("Y-m-d");
    $dateTo = date("Y-m-d", strtotime($dateFrom . " + 1 days"));
    $dateFrom = date("m-d-Y", strtotime($dateFrom));
    $dateTo = date("m-d-Y", strtotime($dateTo));
}

$today = date("m/d/Y");

// -------------- Guests ---------------

$guests = $_GET['guests'];

$max_guests = 8;
for ($i = 1; $i <= $max_guests; $i++) {
    if ($guests == $i) {
        $print_guest_selector .= "<option selected>$i</option>";
    } else {
        $print_guest_selector .= "<option>$i</option>";
    }
}

// ------------- Equipment --------------

$print_room_equipment_selector = "<div id=\"list1\" class=\"dropdown-check-list\" tabindex=\"100\">
                        <span class=\"anchor\">Equipment</span>
                        <ul class=\"items\">";  

$room_equipment = $hotel->getHotelRoomsEquipment($hotel_id);
for ($i = 0; $i < count($room_equipment); $i++) {
    $equipment = $room_equipment[$i];

    // If equipment was selected, make it checked
    if (isset($_GET['equip']) && in_array($equipment, $_GET['equip'])) {
        $checked = "checked";
    } else {
        $checked = "";
    }

    $print_room_equipment_selector .= "<li><input class=\"minput\" type=\"checkbox\" name='equip[]' value='$equipment' $checked/>$equipment</li>";
}

$print_room_equipment_selector .= "</ul></div>";

// --------------- Manage ---------------

// Admin or owner of hotel can both manage hotel and reservations
if (($_SESSION['rights'] == 1) || ($_SESSION['rights'] == 2 && $user->checkOwnership($_SESSION['id'], $hotel_id))) {
    
    $manage = "<div class=\"msection\">";
    
    // Admin
    $manage .= "
        <div class=\"btn-group\">
            <a href=\"editor.php?type=Hotel&id=".$_GET['id']."\" class=\"btn btn-primary active\">
                <i class=\"glyphicon glyphicon-floppy-disk\" aria-hidden=\"true\"></i> Manage hotel
            </a>
        </div>

        <div class=\"btn-group\">
            <a href=\"employeeView.php?hid=".$_GET['id']."\" class=\"btn btn-primary active\">
                <i class=\"glyphicon glyphicon-floppy-disk\" aria-hidden=\"true\"></i> Manage reservations
            </a>
        </div>
        ";
        
    $manage .= "</div>";

// Hotel employee can only manage hotel reservations
} else if ($_SESSION['rights'] == 3 && $user->checkEmployment($_SESSION['id'], $hotel_id)) {

    $manage = "<div class=\"msection\">";
    
    // Admin
    $manage .= "
        <div class=\"btn-group\">
            <a href=\"employeeView.php?hid=".$_GET['id']."\" class=\"btn btn-primary active\">
                <i class=\"glyphicon glyphicon-floppy-disk\" aria-hidden=\"true\"></i> Manage reservations
            </a>
        </div>
        ";
        
    $manage .= "</div>";
}

// ---------------- Rooms ---------------

list($room_type, $room_price, $room_descr, $principal) = $hotel->getAvailableRooms($hotel_id, $dateFrom, $dateTo, $guests);
list($all_room_type, $all_room_price, $all_room_descr) = $hotel->getAllRooms($hotel_id);

// Generate code for printing rooms
$room_begin = "<div class=\"msection\">
               <h2 style=\"font-weight: bold;\">Rooms</h2>";

// Print all rooms in the hotel
if (count($all_room_type) > 0) {
    for ($i = 0; $i < count($all_room_type); $i++) {
        
        $type = $all_room_type[$i];
        $price = $all_room_price[$i] * $guests;
        $descr = $all_room_descr[$i];
        $equip_arr = $hotel->getRoomEquipment($hotel_id, $type);
        $equipment = implode(', ', $equip_arr);

        // Check if room is available
        // If it is not, make the Book button disabled
        if (!in_array($type, $room_type) || count(array_intersect($equip_arr, $_GET['equip'])) != count($_GET['equip'])) {
            $disabled = "disabled";
            $title = "title = \"Room is in this date and/or with wanted equipment unavailable\"";
        } else {
            $disabled = "";
            $title = "title = \"Book your room now!\"";
        }
        
        $principalCurr = $principal[$i];
        
        $room_main .= "
        <div class=\"row\">
            <div class=\"col-6\">
                <h3 style=\"font-weight: bold;\">$type</h3>
                <p class=\"mb-0 mjustify-text\">$descr</p>
                <p class=\"mb-0 mjustify-text\"><i class=\"fas fa-hot-tub\"></i> : $equipment</p>
            </div>
        
            <div class=\"col\">
                <h5 style=\"font-weight: bold;\">Price</h5>
                <p class=\"mb-0\">$price € / night</p>
                
                <form action=\"bookView.php\" method=\"post\">
                <div>
                    <input name=\"hotel_id\" value=\"$hotel_id\" type=\"hidden\">
                    <input name=\"from\" value=\"$dateFrom\" type=\"hidden\">
                    <input name=\"to\" value=\"$dateTo\" type=\"hidden\">
                    <input name=\"guests\" value=\"$guests\" type=\"hidden\">
                    <input name=\"type\" value=\"$type\" type=\"hidden\">
                    <input name=\"principal\" value=\"$principalCurr\" type=\"hidden\">

                    <input class=\"btn btn-primary active\" type=\"submit\" aria-pressed=\"true\" value=\"Book\" $title $disabled/>
                </div>
                </form>
            </div>
        </div>";

        $equipment = "";
    }

// There are no available rooms
} else {
    $room_main = "
        <div class=\"row\">
            <div class=\"col\">
                <h5>Unfortunately, there are no available rooms for this date</h5>
            </div>
        </div>";
}

$print_rooms = $room_begin . $room_main . "</div>";


// -------------- Reviews --------------

// -------- Print hotel reviews --------

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

for ($i = 0; $i < count($reviews_rating); $i++) {
    $name = array_values($reviews_name)[$i];
    $note = array_values($reviews_note)[$i];
    $rating = array_values($reviews_rating)[$i];
    $review = $review . "<blockquote class=\"blockquote text-center\">
                        <p class=\"mb-0\">$note</p>
                        <p class=\"mb-0\">$rating/10</p>
                        <footer class=\"blockquote-footer\">$name</footer>
                        </blockquote>";
}

// There are no reviews
if (count($reviews_rating) == 0) {
    $review = "
        <div class=\"row\">
            <div class=\"col\">
                <h5>There are no reviews yet</h5>
            </div>
        </div>";
}

$hotel_image = $hotel->getTitleImg($hotel_id);
$hotel_user_rating = $hotel->getAvgHotelRating($hotel_id);

// --------- Add user review ---------

// Only logged in users can write reviews
if (isset($_SESSION['id'])) {

    $make_review = "
        <div class=\"row\">
            <div class=\"col\">
            <form method=\"post\">
            
                <label class=\"mr-sm-2\">Points</label>
                <select class=\"custom-select mr-sm-2\" name=\"reviewRating\">
                    <option selected>Choose...</option>
                    <option value=\"1\">1</option>
                    <option value=\"2\">2</option>
                    <option value=\"3\">3</option>
                    <option value=\"4\">4</option>
                    <option value=\"5\">5</option>
                    <option value=\"6\">6</option>
                    <option value=\"7\">7</option>
                    <option value=\"8\">8</option>
                    <option value=\"9\">9</option>
                    <option value=\"10\">10</option>
                </select>

                <input name=\"review_submitted\" value='1' type=\"hidden\">
                <input name=\"hotel_id\" value=\"$hotel_id\" type=\"hidden\">
                <input name=\"user_id\" value=\"$_SESSION[id]\" type=\"hidden\">
                
                <label class=\"mr-sm-2\">Your review</label>
                <textarea name=\"reviewText\" rows=\"5\" style=\"width:100%; background-color:#f5f5f5; padding: 15px;\"></textarea>
                <input class=\"btn btn-primary active\" type=\"submit\" aria-pressed=\"true\" value=\"Submit\"/>
            
            </form>
        </div>
    </div>";

}

if (isset($_POST['review_submitted'])) {
    $hotel->addReview();

    // Do not resubmit after hitting refresh and refresh to make review appear
    print
    ("
        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
            window.location.reload()
        </script>
    ");
}

// ------------- Photos -------------

$photos_paths = $hotel->getPhotos($hotel_id);

for ($i = 0; $i < count($photos_paths); $i++) {
    $photo = $photos_paths[$i];
    $photos .= "
        <div class=\"row\">
            <img alt=\"$photo\" src=\"$photo\" class=\"mimg mimg-fluid container-fluid no-padding\">
        </div>";
}



// ----------------------------------
// -------------- HTML --------------
// ----------------------------------

printHTMLheader($hotel_name);

print
("
<body>
");

printHeader();

print
("
    <div class=\"container-fluid m-main\">
        
        <div class=\"mhotel-img-container\">
            <div class=\"mhotel-img\">
                <img alt=\"$photo\" src=\"$hotel_image\" class=\"mimg img-fluid container-fluid no-padding\">
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
                    <div class=\"mhotel-rating\">
                        <h2>".$hotel_user_rating."</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <hr>
        
        <div class=\"msection\">
            <p class=\"lead mjustify-text\">
                $hotel_description
            </p>
        </div>
");   

print($manage);

// msection check availability
print
("
<div class=\"row\">
    <form class=\"mform\" action=\"hotelView.php\">
    <div class=\"row\" style=\"margin-top:25px;\">
    
        <input type=\"hidden\" id=\"id\" name=\"id\" value=\"$hotel_id\">
        
        <div class=\"col-5\">
        <input type=\"text\" name=\"daterange\" value=\"$dateFrom - $dateTo\" />
            <script>
                $(function() {
                    $('input[name=\"daterange\"]').daterangepicker({
                        \"minDate\": \"$today\"
                    });
                });
            </script>
        </div>
        
        <div class=\"col-2\">
            $print_room_equipment_selector
        </div>
            
        <div class=\"col-2\">
            <select id=\"guests\" name=\"guests\" class=\"form-control kform-control\">
            $print_guest_selector
            </select>
        </div>

        <div class=\"col-2\" style=\"padding-left: 0%;\">
            <input type=\"submit\" class=\"btn btn-primary\" id=\"ksubbtn\" name=\"checkAvailability\" value=\"Check\"> 
        </div>
        
        </div>
    </form>
</div>

<script>
var checkList = document.getElementById('list1');
    checkList.getElementsByClassName('anchor')[0].onclick = function(evt) {
      if (checkList.classList.contains('visible'))
        checkList.classList.remove('visible');
      else
        checkList.classList.add('visible');
    }
</script>
");

// msection rooms
print($print_rooms);

// msection reviews
print
("
<div class=\"msection\">
    <h2 style=\"font-weight: bold;\">Reviews</h2>
    $review
    $make_review
</div>

<div class=\"msection\">
    <h2 style=\"font-weight: bold;\">Photos</h2>
    $photos
</div>
</div>
");

printFooter();

print
("
</body>
</html>
");

?>