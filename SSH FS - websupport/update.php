<?php
include "config.php";
if (isset($_POST['Users'])) {
    
    // update users
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        echo '<script type="text/javascript">'; 
        echo 'alert("Invalid email format");'; 
        echo 'window.location.href = "admin.php";';
        echo '</script>';
        exit ();
    }
    if($_POST['password'] != ""){
        $order_seq = "UPDATE User SET name='$_POST[name]',surname='$_POST[surname]',phone_number='$_POST[phone_number]',email='$_POST[email]',password=\"".password_hash($_POST[password], PASSWORD_BCRYPT)."\",rights='$_POST[rights]',employed='$_POST[employed]' WHERE id=$_POST[id]";
    } else{
        $order_seq = "UPDATE User SET name='$_POST[name]',surname='$_POST[surname]',phone_number='$_POST[phone_number]',email='$_POST[email]',rights='$_POST[rights]',employed='$_POST[employed]' WHERE id=$_POST[id]";
    }
    if (!mysqli_query($connection, $order_seq)) { 
        die('Error: ' . mysqli_error($connection)); 
    } else{
        echo '<script type="text/javascript">'; 
        echo 'alert("Data updated");'; 
        echo 'window.location.href = "admin.php";';
        echo '</script>';
    }

} else if (isset($_POST['Hotels'])) {
    $order_seq = "UPDATE Hotel SET name='$_POST[name]',country='$_POST[country]',city='$_POST[city]',zip='$_POST[zip]',street='$_POST[street]',stars='$_POST[stars]',description='$_POST[description]' WHERE id=$_POST[id]";
    if (!mysqli_query($connection, $order_seq)) { 
        die('Error:'.$_POST['stars'] . mysqli_error($connection)); 
    } else{
        echo '<script type="text/javascript">'; 
        echo 'alert("Data updated");'; 
        echo 'window.location.href = "admin.php";';
        echo '</script>';
    }
} else if (isset($_POST['Gallery'])){
     // set up basic connection
    $conn_id = ftp_connect('37.9.175.3') or die("Couldn't connect to 37.9.175.3"); 
    $ftp_user_name = 'iis.planaxis.space';
    $ftp_user_pass  = 'Qp0KL(UwzZ';
    // login with username and password
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
    $target_dir = "uploads/";
    for($i = 0; $i < count($_FILES['gallery'][tmp_name]); $i++){
        $target_file = $target_dir. "hid_$_POST[hotel_id]_gallery_".basename($_FILES['gallery']['name'][$i]);
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
    for($i = 0; $i < count($_FILES['gallery'][tmp_name]); $i++){
        $target_file = $target_dir. "hid_".$_POST['hotel_id']."_gallery_".basename($_FILES['gallery']['name'][$i]);
        if($_POST['room_id'] == ''){
            $order_seq = "INSERT INTO Photos (hotel_id, info, path, type) VALUES ('$_POST[hotel_id]','$_POST[info]','$target_file','Gallery')";
        } else{
            $order_seq = "INSERT INTO Photos (hotel_id,room_id, info, path, type) VALUES ('$_POST[hotel_id]','$_POST[room_id]','$_POST[info]','$target_file','Gallery')";
        }
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        } else{
            continue;
        }
    }
    echo '<script type="text/javascript">'; 
    echo 'alert("Data updated");'; 
    echo 'window.location.href ="editor.php?type=Hotel&id='.$_POST['hotel_id'].'";';
    echo '</script>';
} else if (isset($_POST['Rooms'])){
    $rid = 0;
    $sql = "SELECT max(id) as id from RoomType where hotel_id = $_POST[hotel_id]";
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $rid = $row["id"];
        }
    }
    $rid++;
    $order_seq = "INSERT INTO RoomType (id, hotel_id, type, description, pricePerBed) VALUES ($rid,'$_POST[hotel_id]','$_POST[rtype]','$_POST[descr]','$_POST[bedprice]')";
    if (!mysqli_query($connection, $order_seq)) { 
        die('Error:' . mysqli_error($connection)); 
    }
    for($i = 0; $i < intval($_POST['numofrooms']); $i++){
        $order_seq = "INSERT INTO Room (hotel_id, number, beds, type) VALUES ('$_POST[hotel_id]',$i,'$_POST[rbeds]',$rid)";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error:' . mysqli_error($connection)); 
        }
    }
    echo '<script type="text/javascript">'; 
    echo 'alert("Data updated");'; 
    echo 'window.location.href = "admin.php";';
    echo '</script>';
} else{
    // set up basic connection
    $conn_id = ftp_connect('37.9.175.3') or die("Couldn't connect to 37.9.175.3"); 
    $ftp_user_name = 'iis.planaxis.space';
    $ftp_user_pass  = 'Qp0KL(UwzZ';
    // login with username and password
    $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
    $sql = "SELECT id,path FROM Photos WHERE type='Title' AND hotel_id=".$_POST['hotel_id'];
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $path = $row["path"];
        }
    }
    if (file_exists($path) && $_POST['removeTitles']) {
        ftp_delete($conn_id, $path);
        $sql = "DELETE FROM Photos WHERE Photos.id =" . $id;
        if (!mysqli_query($connection, $sql)) { 
            die('Error: ' . mysqli_error($connection)); 
        } else{
        }
    }
    
    $target_dir = "uploads/";
    $target_file = $target_dir. "hid_".$_POST['hotel_id'] ."_titleImage_".basename($_FILES['titleImage']['name']);
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
    $order_seq = "INSERT INTO Photos (hotel_id, info, path, type) VALUES ('$_POST[hotel_id]','$_POST[info]','$target_file','Title')";
    if (!mysqli_query($connection, $order_seq)) { 
        die('Error: ' . mysqli_error($connection)); 
    } else{
    }
    echo '<script type="text/javascript">'; 
    echo 'alert("Data updated");'; 
    echo 'window.location.href ="editor.php?type=Hotel&id='.$_POST['hotel_id'].'";';
    echo '</script>';
}
?>