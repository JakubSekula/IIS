<?php
include_once("config.php");
include_once("functions.php");
include_once("db.php");

class HotelService {
    
    // Returns hotel name, stars an description
    function getHotelInfo($hotel_id) {
        global $connection;
        $sql = "SELECT Hotel.name, Hotel.stars, Hotel.description
                FROM Hotel WHERE Hotel.id = $hotel_id";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $hotel_name = $row["name"];
                $hotel_stars = $row["stars"];
                $hotel_description = $row["description"];
            }
        }
        
        return array($hotel_name, $hotel_stars, $hotel_description);
    }

    // Returns hotel reviews - name of reviewer, his rating and review itself
    function getHotelReviews($hotel_id) {
        global $connection;
        $sql = "SELECT Reviews.note, Reviews.rating, User.name
                FROM Reviews NATURAL JOIN User
                WHERE Reviews.hotel_id = $hotel_id";
        
        return $connection->query($sql);
    }

    // Returns all the 
    function getHotelRooms($hotel_id, $dateFrom, $dateTo, $guests) {
        global $connection;
        $sql = "SELECT RoomType.type, RoomType.pricePerBed, RoomType.description
                FROM RoomType WHERE RoomType.hotel_id = $hotel_id";
        // $sql = "SELECT Room.type, Room.beds, Room.price, Room.description
        //         FROM Room, Reservation 
        //         WHERE Room.hotel_id = $hotel_id
        //             AND Room.hotel_id
        //             AND Reservation.arrival NOT BETWEEN $dateFrom AND $dateTo
        //             AND Reservation.departure NOT BETWEEN $dateFrom AND $dateTo";
        $result = $connection->query($sql);
        
        $room_type = array();
        $room_price = array();
        $room_descr = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($room_type, $row["type"]);
                array_push($room_price, $row["pricePerBed"]);
                array_push($room_descr, $row["description"]);
            }
        }

        return array($room_type, $room_price, $room_descr);
    }

    // Transforms room type from int to string
    function transformRoomType($room_type) {
        if ($room_type == "1") {
            return "Economy";
        } else if ($room_type == "2") {
            return "Basic";
        } else if ($room_type == "3") {
            return "Business";
        }
    }

    // Returns AVG user hotel rating
    function getAvgHotelRating($hotel_id) {
        global $connection;
        $sql = "SELECT AVG(Reviews.rating) FROM Reviews WHERE Reviews.hotel_id = $hotel_id";
        $query = $connection->query($sql);
        $user_rating = array_values($query->fetch_assoc())[0];
        return round($user_rating, 1);
    }

    // Returns path to hotel title image
    function getTitleImg($hotel_id) {
        global $connection;
        $sql = "SELECT * FROM Photos WHERE hotel_id = $hotel_id AND type='Title'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $img_path = $row["path"];
            }
        }

        return $img_path;
    }

    function bookRoom() {
        global $connection;

        $hotel = $_GET['id']; 
        $from = $_GET['from'];
        $to = $_GET['to'];
        $guests = $_GET['guests'];
        $type = $_GET['type'];
        $room_id = $this->getFreeRoomId();
        
        $user_id = $_SESSION['id'];
        $to = change_date_format($to);
        $from = change_date_format($from);

        if (isset($_SESSION['id'])) {
            $sql = "INSERT INTO Reservation (hotel_id, room_id, arrival, departure)
                    VALUES ($hotel, $room_id, '$from', '$to')";
        } else {
            $sql = "INSERT INTO Reservation (hotel_id, room_id, user_id, arrival, departure)
                    VALUES ($hotel, $room_id, $user_id, '$from', '$to')";
        }

        $result = $connection->query($sql);

        if (!mysqli_query($connection, $sql)) { 
            die('Error:'. mysqli_error($connection)); 
        }
    }

    function checkOwnership( $user_id, $hotel_id ){
        global $connection;
        $sql = "SELECT * FROM Owns WHERE idowner = $user_id AND idhotel = $hotel_id";
        $res = $connection->query( $sql );
        
        if( $res->num_rows > 0 ){
            while ( $row = $res->fetch_assoc() ){
                return true;
            }
        }

        return false;

    }

    function checkEmployment( $user_id, $hotel_id ){
        global $connection;
        $sql = "SELECT * FROM user WHERE id = $user_id AND employed = $hotel_id";
        $res = $connection->query( $sql );
        
        if( $res->num_rows > 0 ){
            while ( $row = $res->fetch_assoc() ){
                return true;
            }
        }

        return false;

    }

    // Returns ID of the room that is free
    // Within given time range
    function getFreeRoomId() {
        global $connection;

        $hotel = $_GET['id']; 
        $from = $_GET['from'];
        $to = $_GET['to'];
        $guests = $_GET['guests'];
        $type = $_GET['type'];
        
        // Not very ellegant but doing its job
        // Finds room ID in given hotel suitable for the reservation
        $sql = 
        "
            SELECT Room.id FROM Room, RoomType

            -- Wanted room attributes
            WHERE Room.hotel_id = $hotel
                AND Room.beds >= $guests
                AND RoomType.type = \"$type\"
                AND RoomType.id = Room.type
                AND RoomType.hotel_id = $hotel
            
            -- Room should not be booked
            AND (Room.id NOT IN (
                SELECT Room.id FROM Room, RoomType, Reservation
                WHERE Room.hotel_id = $hotel 
                    AND Room.beds >= $guests
                    AND RoomType.type = \"$type\"
                    AND RoomType.id = Room.type
                    AND RoomType.hotel_id = $hotel
                    AND Room.id = Reservation.room_id
                    AND ((Reservation.arrival BETWEEN '$from' AND '$to'
                        OR Reservation.departure BETWEEN '$from' AND '$to')
                        OR ('$from' BETWEEN Reservation.arrival AND Reservation.departure
                        OR '$to' BETWEEN Reservation.arrival AND Reservation.departure)
                    )
                )
            )

            -- Lowes no. of beds possible
            ORDER BY beds ASC
            -- Only 1 room is needed
            LIMIT 1
        ";

        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $room_id = $row["id"];
            }
        }
        
        echo "<script>console.log('--- $room_id ----' );</script>";

        return $room_id;
    }

}

// For making reservations
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['bookRoom']))
{
    $hotel = new HotelService();
    $hotel->bookRoom();
}

function swap_values($arr, $i, $j) {
    
    $tmp = $arr[$i];
    $arr[$i] = $arr[$j];
    $arr[$j] = $tmp;

    return $arr;
}

function array_swap(&$array,$swap_a,$swap_b){
    list($array[$swap_a],$array[$swap_b]) = array($array[$swap_b],$array[$swap_a]);
}

function change_date_format($date) {

    $date = explode('-', $date);
    
    // 10 29 2020 -> 2020 29 10
    $date = swap_values($date, 0, 2);
    // 2020 29 10 -> 2020 10 29
    $date = swap_values($date, 1, 2);
    //array_swap($date, $date[0], $date[2]);
    //array_swap($date, $date[1], $date[2]);
    
    return implode("-", $date);
}

function GetUser( $user_id ){
    global $connection;
    $sql = "SELECT * FROM user WHERE id = $user_id";
    $res = $connection->query( $sql );
    
    if( $res->num_rows > 0 ){
        while ( $row = $res->fetch_assoc() ){
            return $row;
        }
    }

    return false;

}

?>