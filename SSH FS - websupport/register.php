<?php
include "config.php";


if (isset($_POST['users'])) {
    
    // register users
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        echo '<script type="text/javascript">'; 
        echo 'alert("Invalid email format");'; 
        echo 'window.location.href = "index.php";';
        echo '</script>';
        exit ();
    }

    $order_seq = "SELECT id FROM User WHERE email='$_POST[email]'";
    $result = $connection->query($order_seq);
    if ($result->num_rows > 0) {
        echo '<script type="text/javascript">'; 
        echo 'alert("Email is used by other user");'; 
        echo 'window.location.href = "index.php";';
        echo '</script>';
        exit ();
    }
     

    if ( !isset($_POST['hotel_id'])){
        $order_seq = "INSERT INTO User (name, surname, phone_number, email, password, rights) VALUES ('$_POST[name]','$_POST[surname]','$_POST[phone_number]','$_POST[email]',\"".password_hash($_POST[password], PASSWORD_BCRYPT)."\", 4 )";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        } else{
            echo '<script type="text/javascript">'; 
            echo 'alert("You are registered, now u can login.");'; 
            echo 'window.location.href = "index.php";';
            echo '</script>';
        }
    
    } else{
        $order_seq = "INSERT INTO User (name, surname, phone_number, email, employed , password, rights) VALUES ('$_POST[name]','$_POST[surname]','$_POST[phone_number]','$_POST[email]','$_POST[hotel_id]',\"".password_hash($_POST[password], PASSWORD_BCRYPT)."\", '$_POST[rights]')";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        } else{
            echo '<script type="text/javascript">'; 
            echo 'window.location.href = "admin.php";';
            echo '</script>';
        }
    }
} else {
    // register hotel
    $order_seq = "INSERT INTO Hotel (name, country, city, zip, street, stars, description) VALUES ('$_POST[name]','$_POST[country]','$_POST[city]','$_POST[zip]','$_POST[street]','$_POST[stars]','$_POST[description]')";
    if (!mysqli_query($connection, $order_seq)) { 
        die('Error: ' . mysqli_error($connection)); 
    } else{
        $sql = "SELECT max(id) as id from Hotel";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $id = $row["id"];
            }
        }
    $order_seq = "INSERT INTO Owns (idowner, idhotel) VALUES ($_SESSION[id],$id)";
    if (!mysqli_query($connection, $order_seq)) { 
        die('Error: ' . mysqli_error($connection)); 
    }
    $hid = $id;
    $numOfRooms = $_POST['rnum'];
    $numOfBeds = $_POST['rbeds'];
    $bedPrice = $_POST['bedprice'];
    $roomTypeName = $_POST['rname'];
    $roomDescription = $_POST['descr'];
    $rid = 0;
    $rnum = 0;
    foreach( $numOfBeds as $key => $n ) {
        $order_seq = "INSERT INTO RoomType (id, hotel_id, type, description, pricePerBed) VALUES ($rid,$hid,'$roomTypeName[$key]','$roomDescription[$key]','$bedPrice[$key]')";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        }
        for ($i = 0; $i < intval($numOfRooms[$key]); $i++){
            $order_seq = "INSERT INTO Room (hotel_id, number, beds, type) VALUES ($hid,$rnum,$numOfBeds[$key],$rid)";
            if (!mysqli_query($connection, $order_seq)) { 
                die('Error: ' . mysqli_error($connection)); 
            }
            $rnum++;
        }
        $rid++;
    }


    // set up basic connection
    $conn_id = ftp_connect('37.9.175.3') or die("Couldn't connect to 37.9.175.3"); 
    $ftp_user_name = 'iis.planaxis.space';
    $ftp_user_pass  = 'Qp0KL(UwzZ';
    // login with username and password
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
    $target_dir = "uploads/";
    for($i = 0; $i < count($_FILES['gallery'][tmp_name]); $i++){
        $target_file = $target_dir. "hid_".$id ."_gallery_".basename($_FILES['gallery']['name'][$i]);
        if (file_exists($target_file)) {
            $target_file = $target_file."_copy.jpg";
        }
        if ($_FILES['gallery']['size'][$i] > 10000000) {
            die("Sorry, there was an error uploading your files. Too big");
        }
        if (move_uploaded_file($_FILES["gallery"]["tmp_name"][$i], $target_file)) {
            
        } else {
            die("Sorry, there was an error uploading your files.Cant move them to the server");
        }
    }
    $target_file = $target_dir. "hid_".$id ."_titleImage_".basename($_FILES['titleImage']['name']);
    if (file_exists($target_file)) {
        $target_file = $target_file."_copy.jpg";
    }
    if ($_FILES['titleImage']['size'] > 10000000) {
        die("Sorry, there was an error uploading your file.Too big");
    }
    if (move_uploaded_file($_FILES["titleImage"]["tmp_name"], $target_file)) {
        
    } else {
        die("Sorry, there was an error uploading your file. Cant move them to the server");
    }

    for($i = 0; $i < count($_FILES['gallery'][tmp_name]); $i++){
        $target_file = $target_dir. "hid_".$id ."_gallery_".basename($_FILES['gallery']['name'][$i]);
        $order_seq = "INSERT INTO Photos (hotel_id, info, path, type) VALUES ($id,'','$target_file','Gallery')";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        } else{
            continue;
        }
    }
        $target_file = $target_dir. "hid_".$id ."_titleImage_".basename($_FILES['titleImage']['name']);
        $order_seq = "INSERT INTO Photos (hotel_id, info, path, type) VALUES ($id,'','$target_file','Title')";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        } else{
        }
        echo '<script type="text/javascript">'; 
        echo 'window.location.href = "admin.php";';
        echo '</script>';
    }
}
?>