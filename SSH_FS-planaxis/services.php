<?php

class HotelService {

    // returns select for hotel on index.php page
    function createSQLForHotels( $city, $sortBy, $limitb, $limitt ){

        $sql = "";

        if( $city == "" ){
            if( $sortBy != "" ){
                $sql = "SELECT * FROM Hotel WHERE id != 0 ORDER BY $sortBy DESC LIMIT $limitb, $limitt";
            } else {
                $sql = "SELECT * FROM Hotel WHERE id != 0 LIMIT $limitb, $limitt";
            }
        } else {
            if( $sortBy != "" ){
                $sql = "SELECT * FROM Hotel WHERE id != 0 AND city = '$city' ORDER BY $sortBy DESC LIMIT $limitb, $limitt";
            } else {
                $sql = "SELECT * FROM Hotel WHERE id != 0 AND city = '$city' LIMIT $limitb, $limitt";
            }
        }

        return $sql;
    }

    function getHotelName($hotel_id) {
        
        global $connection;
        $sql = "SELECT Hotel.name FROM Hotel WHERE Hotel.id = $hotel_id";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $hotel_name = $row["name"];
            }
        }

        return $hotel_name;
    }
    
    // Returns hotel name, stars an description
    function getHotelInfo($hotel_id) {
        global $connection;
        $sql =
        "   SELECT Hotel.name, Hotel.stars, Hotel.description
            FROM Hotel WHERE Hotel.id = $hotel_id
        ";
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
        $sql = 
        "   SELECT Reviews.note, Reviews.rating, User.name
            FROM Reviews, User
            WHERE
                Reviews.hotel_id = $hotel_id
                AND Reviews.user_id = User.id
        ";
        
        return $connection->query($sql);
    }


    // Returns all room types in hotel with ID $hotel_id
    function getAllRooms($hotel_id) {
        global $connection;

        $sql = 
        "   SELECT DISTINCT RoomType.type, RoomType.pricePerBed, RoomType.description
            FROM RoomType 
            WHERE RoomType.hotel_id = $hotel_id
        ";
        
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

        return array($room_type, $room_price, $room_descr, $room_princ);
    }


    // Returns all available rooms for given no of guests and given timerange
    function getAvailableRooms( $hotel_id, $from, $to, $guests ) {
        global $connection;
        
        $from = date( 'Y-m-d',  strtotime( $from ) );
        $to = date( 'Y-m-d',  strtotime( $to ) );

        // Returns available hotel rooms
        $sql =
        "   SELECT DISTINCT RoomType.type, RoomType.pricePerBed, RoomType.description, RoomType.principal
            FROM Room, RoomType

            WHERE Room.hotel_id = $hotel_id
                AND Room.beds >= $guests
                AND RoomType.id = Room.type
                AND RoomType.hotel_id = $hotel_id
            
            AND (Room.id NOT IN (
                SELECT Room.id FROM Room, RoomType, Reservation
                WHERE Room.hotel_id = $hotel_id
                    AND Room.beds >= $guests
                    AND RoomType.id = Room.type
                    AND RoomType.hotel_id = $hotel_id
                    AND Room.id = Reservation.room_id
                    AND (
                        (
                            '$from' <= Reservation.arrival AND '$to' >= Reservation.departure
                        ) OR (
                            '$from' <= Reservation.arrival AND '$to' > Reservation.arrival
                        ) OR (
                            '$from' < Reservation.departure AND '$to' >= Reservation.departure
                        )
                    )
                )
            )
        ";

        $result = $connection->query($sql);
        
        $room_type = array();
        $room_price = array();
        $room_descr = array();
        $room_princ = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($room_type, $row["type"]);
                array_push($room_price, $row["pricePerBed"]);
                array_push($room_descr, $row["description"]);
                array_push($room_princ, $row["principal"]);
            }
        }

        return array($room_type, $room_price, $room_descr, $room_princ);
    }

    // Returns AVG user hotel rating
    function getAvgHotelRating($hotel_id) {
        global $connection;

        $sql = "SELECT COUNT(Reviews.rating) FROM Reviews WHERE Reviews.hotel_id = $hotel_id";
        $query = $connection->query($sql);
        $no_reviews = array_values($query->fetch_assoc())[0];

        if ($no_reviews == 0) {
            return '-';
        } else {
            $sql = "SELECT AVG(Reviews.rating) FROM Reviews WHERE Reviews.hotel_id = $hotel_id";
            $query = $connection->query($sql);
            $user_rating = array_values($query->fetch_assoc())[0];

            return round($user_rating, 1);
        }
    }

    // Returns path to hotel title image
    function getTitleImg($hotel_id) {
        global $connection;
        $sql = "SELECT * FROM Photos WHERE hotel_id = $hotel_id AND type = 'Title'";
        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $img_path = $row["path"];
            }
        }

        return $img_path;
    }


    // Returns paths to all hotel with $hotel_id ID photos
    function getPhotos($hotel_id) {
        global $connection;

        $sql = "SELECT * FROM Photos WHERE hotel_id = $hotel_id AND type != 'Title'";
        $result = $connection->query($sql);

        $photos = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($photos, $row["path"]);
            }
        }

        return $photos;
    }

    // Make reservation and return its ID
    function bookRoom() {
        global $connection;
        $user_id = $_SESSION['id'];

        $user_id = $_POST['user_id'];
        $hotel_id = $_POST['hotel_id']; 
        $from = $_POST['from'];
        $to = $_POST['to'];
        $guests = $_POST['guests'];
        $type = $_POST['type'];
        $principal = $_POST['principal'];

        $from = date("Y-m-d", strtotime($from));
        $to = date("Y-m-d", strtotime($to));

        $state = 1;

        if( !( $principal == '0' ) ){
            $state = 2;
        }

        $room_id = $this->getFreeRoomId();

        // Logged in
        if (isset($_SESSION['id'])) {
            
            if ($principal == '0') {
                $insert = "INSERT INTO Reservation (hotel_id, room_id, user_id, arrival, departure, stav)
                        VALUES ($hotel_id, $room_id, $user_id, '$from', '$to', '$state')";
            } else {
                $insert = "INSERT INTO Reservation (hotel_id, room_id, user_id, arrival, departure, jistina_zaplaceno, stav)
                    VALUES ($hotel_id, $room_id, $user_id, '$from', '$to', 0, '$state')";
            }

            // Execute query and check for error
            if (!mysqli_query($connection, $insert)) { 
                print('Error: ' . mysqli_error($connection));
                return "";
            }

            // Get ID of the reservation
            $selectID = "SELECT Reservation.id FROM Reservation WHERE Reservation.hotel_id = $hotel_id
                        AND Reservation.room_id = $room_id AND Reservation.user_id = $user_id
                        AND Reservation.arrival = '$from' AND Reservation.departure = '$to'";
            
            $result = $connection->query($selectID);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $reservationID = $row["id"];
                }
            }
        
        // User not logged in / registered -> user_id is set to 0
        } else {
            
            // Insert into Reservation
            $insert = "INSERT INTO Reservation (hotel_id, room_id, user_id, arrival, departure, stav)
                        VALUES ($hotel_id, $room_id, 0, '$from', '$to', '$state')";

            // Execute query and check for error
            if (!mysqli_query($connection, $insert)) { 
                die('Error: ' . mysqli_error($connection));
                return "";
            }

            // Insert into ReservationNonReg
            // Get ID of the reservation
            $selectID = "SELECT Reservation.id FROM Reservation WHERE Reservation.hotel_id = $hotel_id
                        AND Reservation.room_id = $room_id AND Reservation.user_id = 0
                        AND Reservation.arrival = '$from' AND Reservation.departure = '$to'";
            
            $result = $connection->query($selectID);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $reservationID = $row["id"];
                }
            }

            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];

            // Insert reservation info into ReservationNonReg
            $insert = "INSERT INTO ReservationNonReg (id, name, surname, email, phone_number)
                        VALUES ($reservationID, '$name', '$surname', '$email', '$phone_number')";


            // Execute query and check for error
            if (!mysqli_query($connection, $insert)) { 
                die('Error: ' . mysqli_error($connection));
                return "";
            }
        }

        return $reservationID;
    }


    // Returns ID of the room that is free within given time range
    function getFreeRoomId() {
        global $connection;

        $hotel_id = $_POST['hotel_id']; 
        $from = $_POST['from'];
        $to = $_POST['to'];
        $guests = $_POST['guests'];
        $type = $_POST['type'];

        $to = date('Y-m-d', strtotime($to));
        $from = date('Y-m-d', strtotime($from));
        
        // Finds room ID in given hotel suitable for the reservation
        $sql = 
        "
            SELECT Room.id FROM Room, RoomType

            WHERE Room.hotel_id = $hotel_id
                AND Room.beds >= $guests
                AND RoomType.type = \"$type\"
                AND RoomType.id = Room.type
                AND RoomType.hotel_id = $hotel_id
            
            AND (Room.id NOT IN (
                SELECT Room.id FROM Room, RoomType, Reservation
                WHERE Room.hotel_id = $hotel_id
                    AND Room.beds >= $guests
                    AND RoomType.type = \"$type\"
                    AND RoomType.id = Room.type
                    AND RoomType.hotel_id = $hotel_id
                    AND Room.id = Reservation.room_id
                    AND (
                        (
                            '$from' <= Reservation.arrival AND '$to' >= Reservation.departure
                        ) OR (
                            '$from' <= Reservation.arrival AND '$to' > Reservation.arrival
                        ) OR (
                            '$from' < Reservation.departure AND '$to' >= Reservation.departure
                        )
                    )
                )
            )

            ORDER BY beds ASC
            LIMIT 1
        ";

        $result = $connection->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $room_id = $row["id"];
            }
        }
        
        return $room_id;
    }


    function addReview() {
        global $connection;

        $sql = "INSERT INTO Reviews (hotel_id, user_id, note, rating)
                    VALUES ('$_POST[hotel_id]','$_POST[user_id]','$_POST[reviewText]','$_POST[reviewRating]')";

        if (!mysqli_query($connection, $sql)) { 
            die('Error: ' . mysqli_error($connection)); 
        }

        $avg_rating = $this->getAvgHotelRating($_POST['hotel_id']);
        $sql = "UPDATE Hotel SET Hotel.rating = $avg_rating WHERE Hotel.id = '$_POST[hotel_id]'";
        
        if (!mysqli_query($connection, $sql)) { 
            die('Error: ' . mysqli_error($connection)); 
        }

    }


    function payPrincipal() {
        global $connection;

        $sql = "UPDATE Reservation SET jistina_zaplaceno = 1, stav = 3 WHERE id = $_POST[reservation_id]";

        if (!mysqli_query($connection, $sql)) { 
            return false;
        }

        return true;
    }

    
    function checkRoomAvalability() {
        if (!$this->getFreeRoomId()) {
            return false;
        } else {
            return true;
        }
    }

    function getHotelRoomsEquipment($hotel_id) {
        global $connection;

        $sql = "SELECT DISTINCT Equipment.name FROM RoomEquipment, Equipment, RoomType
                WHERE RoomEquipment.equipment_id = Equipment.id
                    AND RoomEquipment.roomType = RoomType.id
                    AND RoomEquipment.roomType IN (
                        SELECT RoomType.id FROM RoomType, Hotel
                        WHERE RoomType.hotel_id = $hotel_id 
                    )";
    
        $result = $connection->query($sql);
        $equipment = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($equipment, $row["name"]);
            }
        }

        return $equipment;
    }

    function getRoomEquipment($hotel_id, $room_type) {
        global $connection;

        $sql = "SELECT Equipment.name FROM Equipment, RoomEquipment, RoomType
                WHERE Equipment.id = RoomEquipment.equipment_id
                    AND RoomEquipment.roomType = RoomType.id
                    AND RoomType.hotel_id = $hotel_id
                    AND RoomType.type = '$room_type'";
        
        $result = $connection->query($sql);
        $equipment = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push($equipment, $row["name"]);
            }
        }

        return $equipment;
    }

    function getEquipment(){
        global $connection;

        $sql = "SELECT name FROM Equipment";
        
        $result = $connection->query($sql);
        $equipment = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                array_push( $equipment, $row[ "name" ]);
            }
        }

        return $equipment;
    }

}

class UserService {
    
    function GetReservations( $user_id ){
        global $connection;
        $sql = "SELECT
            Hotel.name,
            Reservation.arrival,
            Reservation.id,
            Reservation.departure,
            State.state,
            RoomType.pricePerBed
        FROM
            Reservation,
            Hotel,
            RoomType,
            Room,
            State
        WHERE
            Reservation.user_id = $user_id AND Reservation.hotel_id = Hotel.id AND RoomType.hotel_id = Reservation.hotel_id AND RoomType.id = Room.type 
            AND Room.id = Reservation.room_id AND Room.hotel_id = Reservation.hotel_id AND State.id = Reservation.stav";

        
        $res = $connection->query( $sql );
        
        if( $res->num_rows > 0 ){
            return $res;
        }
    
        return false;
    
    }

    function GetUser( $user_id ) {
        global $connection;
        $sql = "SELECT * FROM User WHERE id = $user_id";
        $res = $connection->query( $sql );
        
        if( $res->num_rows > 0 ){
            while ( $row = $res->fetch_assoc() ){
                return $row;
            }
        }
    
        return false;
    
    }

    function checkEmployment( $user_id, $hotel_id ){
        global $connection;
        $sql = "SELECT * FROM Employment WHERE iduser = $user_id AND idhotel = $hotel_id AND position = 3";
        $res = $connection->query( $sql );
        
        if( $res->num_rows > 0 ){
            while ( $row = $res->fetch_assoc() ){
                return true;
            }
        }

        return false;

    }

    function checkOwnership( $user_id, $hotel_id ){
        global $connection;
        $sql = "SELECT * FROM Employment WHERE iduser = $user_id AND idhotel = $hotel_id AND position = 2";
        $res = $connection->query( $sql );
        
        if( $res->num_rows > 0 ){
            while ( $row = $res->fetch_assoc() ){
                return true;
            }
        }

        return false;

    }

    // Check if email address is valid and non existent in DB
    function validateEmail() {
        global $connection;

        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            echo '<script type="text/javascript">'; 
            echo 'alert("Invalid email format");'; 
            echo 'window.location.href = "index.php";';
            echo '</script>';
            return false;
        }

        $order_seq = "SELECT id FROM User WHERE email='$_POST[email]'";
        $result = $connection->query($order_seq);
        
        if ($result->num_rows > 0) {
            echo '<script type="text/javascript">'; 
            echo 'alert("User with this email address is already registered");'; 
            echo 'window.location.href = "index.php";';
            echo '</script>';
            return false;
        }

        return true;
    }

    // Registers user and returns his ID
    function registerUser() {
        global $connection;

        $this->validateEmail();

        $order_seq = "INSERT INTO User (name, surname, phone_number, email , password, rights)
        VALUES ('$_POST[name]','$_POST[surname]','$_POST[phone_number]','$_POST[email]', \"".password_hash($_POST[password], PASSWORD_BCRYPT)."\", 4)";

        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        }

        return $this->getIdByEmail($_POST['email']);
    }

    // Returns ID of the user registered with $email email address
    function getIdByEmail($email) {
        global $connection;

        $order_seq = "SELECT id FROM User WHERE email='$email'";
        $result = $connection->query($order_seq);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["id"];
            }
        }

        return "";
    }

    function updateUser( $vals, $user_id ){
        global $connection;

        $name = htmlspecialchars( $_POST[ 'name' ] );
        $surname = htmlspecialchars( $_POST[ 'surname' ] );
        $email = htmlspecialchars( $_POST[ 'email' ] );
        $number = htmlspecialchars( $_POST[ 'number' ] );
        $address = htmlspecialchars( $_POST[ 'address' ] );

        $sql = "UPDATE User SET name = '$name', surname = '$surname', phone_number = '$number', email='$email', address='$address' WHERE id = $user_id";
    
        print( $sql );

        $result = $connection->query( $sql );

        if( !mysqli_query( $connection, $sql ) ){ 
            die( 'Error:'. mysqli_error( $connection ) ); 
        }

        return;

    }

}

?>