<?php
    include_once("include.php");
    sessionTimeout();

if (isset($_POST['users'])) {
    // register users

    // check mail validity
    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        echo '<script type="text/javascript">'; 
        echo 'alert("Invalid email format");'; 
        echo 'window.location.href ="'.$_SERVER['HTTP_REFERER']."?name=".$_POST['name']."&surname=".$_POST['surname'].'";';
        echo '</script>';
        exit ();
    }
    // check pass validity
    if(isset($_POST["psw-repeat"])){
        if($_POST["password"] != $_POST["psw-repeat"]){
            echo '<script type="text/javascript">'; 
            echo 'alert("Passwords dont match");'; 
            echo 'window.location.href ="'.$_SERVER['HTTP_REFERER']."?name=".$_POST['name']."&surname=".$_POST['surname']."&email=".$_POST['email'].'";';
            echo '</script>';
            exit ();
        }

    }


    $order_seq = "SELECT id FROM User WHERE email='$_POST[email]'";
    $result = $connection->query($order_seq);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $order_seq1 = "SELECT id FROM User WHERE id=$row[id] AND password is NULL";
        $result1 = $connection->query($order_seq1);
        if ($result1->num_rows > 0) {
            $row1 = $result1->fetch_assoc();
            $order_seq2 = "UPDATE User SET name='$_POST[name]',surname='$_POST[surname]',phone_number='$_POST[phone_number]',email='$_POST[email]',password=\"".password_hash($_POST[password], PASSWORD_BCRYPT)."\" WHERE id=$row1[id]";

            if (!mysqli_query($connection, $order_seq2)) { 
                die('Error: ' . mysqli_error($connection)); 
            } else{
                echo '<script type="text/javascript">'; 
                echo 'alert("You are registered once again, now u can login.");'; 
                echo 'window.location.href = "signinView.php";';
                echo '</script>';
                exit ();
            }
        }
        echo '<script type="text/javascript">'; 
        echo 'alert("User with this email address is already registered");'; 
        echo 'window.location.href ="'.$_SERVER['HTTP_REFERER']."?name=".$_POST['name']."&surname=".$_POST['surname'].'";';
        echo '</script>';
        exit ();
    }
     

    if ( !isset($_POST['employed'])) {
        $order_seq = "INSERT INTO User (name, surname, phone_number, email, password, rights) VALUES ('$_POST[name]','$_POST[surname]','$_POST[phone_number]','$_POST[email]',\"".password_hash($_POST[password], PASSWORD_BCRYPT)."\", 4 )";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        } else{
            echo '<script type="text/javascript">'; 
            echo 'alert("You are registered, now u can login.");'; 
            echo 'window.location.href = "signinView.php";';
            echo '</script>';
        }
    
    } else {
        if(!isset($_POST['rights'])){
            $order_seq = "INSERT INTO User (name, surname, email, password) VALUES ('$_POST[name]','$_POST[surname]','$_POST[email]',\"".password_hash($_POST[password], PASSWORD_BCRYPT)."\")";
        } else{
            $order_seq = "INSERT INTO User (name, surname, phone_number, email , password, rights) VALUES ('$_POST[name]','$_POST[surname]','$_POST[phone_number]','$_POST[email]',\"".password_hash($_POST[password], PASSWORD_BCRYPT)."\", '$_POST[rights]')";
        }
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        }

        $sql = "SELECT max(id) as id from User";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $uid = $row["id"];
            }
        }

        if(count($_POST['employed']) > 0){
        $order_seq = "DELETE FROM Employment WHERE iduser = $uid";
        if (!mysqli_query($connection, $order_seq)) {
            die('Error: ' . mysqli_error($connection)); 
        } 
        }
        foreach ($_POST['employed'] as $subject){
            echo $subject;
            $order_seq = "INSERT INTO Employment(iduser,idhotel,position) VALUES (".$uid.",".intval($subject).",$_POST[rights])";
            if (!mysqli_query($connection, $order_seq)) {
                die('Error: ' . mysqli_error($connection)); 
            } 
        }  

            echo '<script type="text/javascript">'; 
            echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'";';
            echo '</script>';
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
        $rnumber = 0;
        $sql = "SELECT max(number) as number from Room where Room.hotel_id = ".$id;
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $rnumber = $row["number"];
            }
        }
        if ($rnumber == '') $rnumber = 0;
        $rnumber++;
    if ($_SESSION['id'] == 1){
        $order_seq = "INSERT INTO Employment (iduser, idhotel,position) VALUES ($_SESSION[id],$id,$_SESSION[rights])";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        }
    } else{
        $order_seq = "INSERT INTO Employment (iduser, idhotel,position) VALUES ($_SESSION[id],$id,$_SESSION[rights])";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        }
        $order_seq = "INSERT INTO Employment (iduser, idhotel,position) VALUES (1,$id,$_SESSION[rights])";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        }
    }
    $hid = $id;
    $numOfRooms = $_POST['rnum'];
    $numOfBeds = $_POST['rbeds'];
    $bedPrice = $_POST['bedprice'];
    $roomTypeName = $_POST['rname'];
    $roomDescription = $_POST['descr'];
    $principal = $_POST['principal'];
    $equip = $_POST['equip'];
    $rid = 0;
    foreach( $numOfBeds as $key => $n ) {
        $order_seq = "INSERT INTO RoomType (hotel_id, type, description, pricePerBed,principal) VALUES ($hid,'$roomTypeName[$key]','$roomDescription[$key]','$bedPrice[$key]','$principal[$key]')";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Esrror: ' . mysqli_error($connection)); 
        }
        $sql = "SELECT max(id) as id from RoomType";
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $rid = $row["id"];
            }
        }
        $pieces = explode(",", $equip[$key]);
        foreach ($pieces as $subject){
            $order_seq = "INSERT INTO RoomEquipment (roomType,equipment_id) VALUES ($rid,".intval($subject).")";
            if (!mysqli_query($connection, $order_seq)) { 
                echo $order_seq;
                die('Errosr: ' . mysqli_error($connection)); 
            }
        }  
        $sql = "SELECT max(number) as number from Room where Room.hotel_id = ".$id;
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $rnumber = $row["number"];
            }
        }
        if ($rnumber == '') $rnumber = 0;
        $rnumber++;
        for ($i = $rnumber; $i < (intval($numOfRooms[$key]) + $rnumber); $i++){
            $order_seq = "INSERT INTO Room (hotel_id, number, beds, type) VALUES ($hid,$i,$numOfBeds[$key],$rid)";
            if (!mysqli_query($connection, $order_seq)) { 
                echo $order_seq;
                die('Errosssr: ' . mysqli_error($connection)); 
            }
        }
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
        $order_seq = "INSERT INTO Photos (hotel_id, path, type) VALUES ($id,'$target_file','Gallery')";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        } else{
            continue;
        }
    }
        $target_file = $target_dir. "hid_".$id ."_titleImage_".basename($_FILES['titleImage']['name']);
        $order_seq = "INSERT INTO Photos (hotel_id, path, type) VALUES ($id,'$target_file','Title')";
        if (!mysqli_query($connection, $order_seq)) { 
            die('Error: ' . mysqli_error($connection)); 
        } else{
        }
        echo '<script type="text/javascript">'; 
        echo 'window.location.href = "'.$_SERVER['HTTP_REFERER'].'";';
        echo '</script>';
    }
}
?>