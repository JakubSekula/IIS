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
include "config.php";


if (isset($_POST['Users'])) {

    if($_POST['Users'] == "psw"){
        $order_seq = "SELECT password from User where id=$_POST[id]";
        $result = $connection->query($order_seq);
        $hash = '';
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $hash = $row['password'];
            }
        }
        if(password_verify($_POST['oldpasswrd'], $hash)){
            $order_seq = "UPDATE User SET password=\"".password_hash($_POST[newpasswrd], PASSWORD_BCRYPT)."\" WHERE id=$_POST[id]";
            if (!mysqli_query($connection, $order_seq)) {
                print( $order_seq );  
                die('Error: ' . mysqli_error($connection)); 
            } else{
                echo '<script type="text/javascript">'; 
                if( preg_match( '/\?/', $_SERVER['HTTP_REFERER'] ) ){
                    echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'&act";';
                } else {
                    echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'?act";';
                }
                echo '</script>';
            }
        } else{
            echo '<script type="text/javascript">'; 
            echo 'alert("Wrong current password");'; 
            echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'";';
            echo '</script>';
        }
        exit ();

    }
    
    // update users
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        echo '<script type="text/javascript">'; 
        echo 'alert("Invalid email format");'; 
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'";';
        echo '</script>';
        exit ();
    }
    if($_POST['password'] != ""){
        $order_seq = "UPDATE User SET name='$_POST[name]',surname='$_POST[surname]',phone_number='$_POST[phone_number]',address='$_POST[address]',email='$_POST[email]',password=\"".password_hash($_POST[password], PASSWORD_BCRYPT)."\",rights='$_POST[rights]' WHERE id=$_POST[id]";
    } else{
        $order_seq = "UPDATE User SET name='$_POST[name]',surname='$_POST[surname]',phone_number='$_POST[phone_number]',address='$_POST[address]',email='$_POST[email]',rights='$_POST[rights]' WHERE id=$_POST[id]";
    }
    if (!mysqli_query($connection, $order_seq)) {
        die('Error: ' . mysqli_error($connection)); 
    }
    if(count($_POST['employed']) > 0){
        $order_seq = "DELETE FROM Employment WHERE iduser = $_POST[id]";
        if (!mysqli_query($connection, $order_seq)) {
            die('Error: ' . mysqli_error($connection)); 
        } 
    }
    foreach ($_POST['employed'] as $subject){
        $order_seq = "INSERT INTO Employment(iduser,idhotel,position) VALUES ($_POST[id],".intval($subject).",$_POST[rights])";
        if (!mysqli_query($connection, $order_seq)) {
            die('Error: ' . mysqli_error($connection)); 
        } 
    }  
    echo '<script type="text/javascript">'; 
    if( preg_match( '/\?/', $_SERVER['HTTP_REFERER'] ) ){
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'&act";';
    } else {
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'?act";';
    }
    echo '</script>';

} else if (isset($_POST['Hotels'])) {
    $order_seq = "UPDATE Hotel SET name='$_POST[name]',country='$_POST[country]',city='$_POST[city]',zip='$_POST[zip]',street='$_POST[street]',stars='$_POST[stars]',description='$_POST[description]' WHERE id=$_POST[id]";
    if (!mysqli_query($connection, $order_seq)) { 
        die('Error:'.$_POST['stars'] . mysqli_error($connection)); 
    } else{
        echo '<script type="text/javascript">'; 
        if( preg_match( '/\?/', $_SERVER['HTTP_REFERER'] ) ){
            echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'&act";';
        } else {
            echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'?act";';
        }
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
            $order_seq = "INSERT INTO Photos (hotel_id, path, type) VALUES ('$_POST[hotel_id]','$target_file','Gallery')";
        } else{
            $order_seq = "INSERT INTO Photos (hotel_id,room_id, path, type) VALUES ('$_POST[hotel_id]','$_POST[room_id]','$target_file','Gallery')";
        }
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        } else{
            continue;
        }
    }
    echo '<script type="text/javascript">'; 
    if( preg_match( '/\?/', $_SERVER['HTTP_REFERER'] ) ){
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'&act";';
    } else {
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'?act";';
    }
    echo '</script>';
} else if (isset($_POST['Rooms'])){
    $order_seq = "INSERT INTO RoomType (hotel_id, type, description, pricePerBed,principal) VALUES ('$_POST[hotel_id]','$_POST[rtype]','$_POST[descr]','$_POST[bedprice]','$_POST[principal]')";
    if (!mysqli_query($connection, $order_seq)) { 
        die('Error:' . mysqli_error($connection)); 
    }
    $sql = "SELECT max(id) as id from RoomType where hotel_id = $_POST[hotel_id]";
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $rid = $row["id"];
        }
    }
     $sql = "SELECT max(number) as number from Room where Room.hotel_id = $_POST[hotel_id]";
    $result = $connection->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $rnumber = $row["number"];
        }
    }
    if ($rnumber == '') $rnumber = 0;
    $rnumber++;
    foreach ($_POST['equip'] as $subject){
        $order_seq = "INSERT INTO RoomEquipment (roomType,equipment_id) VALUES ($rid,".intval($subject).")";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Errosr: ' . mysqli_error($connection)); 
        }
    }  
    for($i = $rnumber; $i < (intval($_POST['numofrooms']) + $rnumber); $i++){
        $order_seq = "INSERT INTO Room (hotel_id, number, beds, type) VALUES ('$_POST[hotel_id]',$i,'$_POST[rbeds]',$rid)";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error:' . mysqli_error($connection)); 
        }
    }
    echo '<script type="text/javascript">'; 
    if( preg_match( '/\?/', $_SERVER['HTTP_REFERER'] ) ){
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'&act";';
    } else {
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'?act";';
    }
    echo '</script>';
}  else if ((isset($_POST['checkin']) || isset($_POST['checkout']) ) && !isset($_POST['status'])){
    $date = $_POST['date'];
    $time = $_POST['time'].":00";
    if(isset($_POST['checkin'])){
        $sql = "UPDATE Reservation SET check_in = '".$date." ".$time. "' WHERE id = $_POST[rid]";
    } else{
        $sql = "UPDATE Reservation SET check_out = '".$date." ".$time."' WHERE id = $_POST[rid]";
    }
    if (!mysqli_query($connection, $sql)) { 
        die('Error:' . mysqli_error($connection)); 
    }
    echo '<script type="text/javascript">'; 
    if( preg_match( '/\?/', $_SERVER['HTTP_REFERER'] ) ){
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'&act";';
    } else {
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'?act";';
    }
    echo '</script>';

}  else if (isset($_POST['status'])){
    $arrival = $_POST['arrival'];
    $departure = $_POST['departure'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $ctime = $_POST['ctime'].":00"; 
    $cotime = $_POST['cotime'].":00";
    $status = $_POST['rstatus'];
    $sql = "UPDATE Reservation SET arrival='".$arrival."', departure='".$departure."', check_in='".$checkin." ".$ctime."', check_out='".$checkout." ".$cotime."',stav=".intval($status)." WHERE id = $_POST[rid]";
    if (!mysqli_query($connection, $sql)) { 
        die('Error:' . mysqli_error($connection)); 
    }
    echo '<script type="text/javascript">'; 
    if( preg_match( '/\?/', $_SERVER['HTTP_REFERER'] ) ){
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'&act";';
    } else {
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'?act";';
    }
    echo '</script>';

}  else if (isset($_POST['state'])){
    $status = isset($_POST['rsstatus']) ? $_POST['rsstatus'] : $_POST['rsstatus_non'];
    $sql = "UPDATE Reservation SET stav=".intval($status)." WHERE id = $_POST[rid]";
    if (!mysqli_query($connection, $sql)) { 
        die('Error:' . mysqli_error($connection)); 
    }
    echo '<script type="text/javascript">'; 
    if( preg_match( '/\?/', $_SERVER['HTTP_REFERER'] ) ){
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'&act";';
    } else {
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'?act";';
    }
    echo '</script>';

}  else if (isset($_GET['userRemove'])){
    $status = $_POST['rsstatus'];
    $sql = "UPDATE User SET password=NULL,rights=0 WHERE id=$_GET[id]";

    if (!mysqli_query($connection, $sql)) { 
        die('Error:' . mysqli_error($connection)); 
    }
    exit();
    echo '<script type="text/javascript">'; 
    if( preg_match( '/\?/', $_SERVER['HTTP_REFERER'] ) ){
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'&act";';
    } else {
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'?act";';
    }
    echo '</script>';

}else{ // title
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
    if (file_exists($path)) {
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
    $order_seq = "INSERT INTO Photos (hotel_id, path, type) VALUES ('$_POST[hotel_id]','$target_file','Title')";
    if (!mysqli_query($connection, $order_seq)) { 
        die('Error: ' . mysqli_error($connection)); 
    } else{
    }
    echo '<script type="text/javascript">'; 
    if( preg_match( '/\?/', $_SERVER['HTTP_REFERER'] ) ){
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'&act";';
    } else {
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'?act";';
    }
    echo '</script>';
}
?>