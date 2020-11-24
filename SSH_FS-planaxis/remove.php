<?php
    include_once("include.php");
    sessionTimeout();
    checkRights(1);

if($_GET['table'] == "user_table"){
    $sql = "DELETE FROM User WHERE User.id = '$_GET[id]'";
} else if($_GET['table'] == "hotel_table"){
    $sql = "DELETE FROM Hotel WHERE Hotel.id = '$_GET[id]'";
} else if($_GET['table'] == "gallery" || $_GET['table']  == "title"){
    if (isset($_GET['path'])){
        // set up basic connection
        $conn_id = ftp_connect('37.9.175.3') or die("Couldn't connect to 37.9.175.3"); 
        $ftp_user_name = 'iis.planaxis.space';
        $ftp_user_pass  = 'Qp0KL(UwzZ';
        // login with username and password
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
        $target_file = $_GET['path'];
        if (file_exists($target_file)) {
            ftp_delete($conn_id, $target_file);
        }
        $sql = "DELETE FROM Photos WHERE Photos.id = '$_GET[id]'";
    } else{
        $sql = "DELETE FROM Photos WHERE Photos.id = '$_GET[id]'";
    }
}  else if($_GET['table'] == "rooms" || $_GET['table'] == "roomstype"){
    $sql = "DELETE FROM Room WHERE Room.hotel_id = '$_GET[hid]' AND Room.type = '$_GET[rtype]'";
    if (!mysqli_query($connection, $sql)) { 
        die('Error: ' . mysqli_error($connection)); 
    }
    $sql = "DELETE FROM RoomType WHERE RoomType.hotel_id = '$_GET[hid]' AND RoomType.id = '$_GET[rtype]'";
    if (!mysqli_query($connection, $sql)) { 
    die('Error: ' . mysqli_error($connection)); 
    } else{
        echo '<script type="text/javascript">'; 
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'";';
        echo '</script>';
    }
    exit;
} else if($_GET['table'] == "reservations_table"){
    $sql = "DELETE FROM Reservation WHERE Reservation.id = $_GET[id]";
}
if (!mysqli_query($connection, $sql)) { 
    die($sql.'Error: ' . mysqli_error($connection)); 
} else{
    echo '<script type="text/javascript">'; 
    echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'";';
    echo '</script>';
}

?>

