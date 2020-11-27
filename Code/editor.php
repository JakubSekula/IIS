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
    checkRights(2);
    
    printHTMLheader("Edit hotel");
    echo "<body onload=\"multiselect()\">";

    printHeader();
?>


<!--     <meta charset="utf-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="style.css">
    <script src="scripts.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
    </style> -->

    <?php if(isset($_GET['act'])){
        echo "<div class='confirm' id='confirm'> <h5> Data updated</h5> </div>";
        echo "<script>$(document).ready( function () {";
        echo "setTimeout(function(){ $('#confirm').css('display', 'none'); }, 2000);});</script>";
            
    }?>
     
<div class="content">
    <?php
        if(!isset($_GET['type'])){
            echo "<h2>Creating new hotel</h2>";
        } else{
            if(!($_GET['type'] == 'Hotel' || $_GET['type'] == 'User')){
                header('Location: error.php');
            }
            echo "<h2>Editing ".$_GET['type']." with ID ".$_GET['id']."</h2>";
        }

        if($_GET['type'] == "User"){
            if($_SESSION['id'] != 1){
                $sql = "SELECT DISTINCT User.id, User.name, User.surname, User.phone_number, User.email, Rights.title
                        FROM User, Rights, Employment
                        WHERE (
                                User.rights = Rights.id
                                AND User.rights = 4
                            ) OR ( 
                                User.rights = Rights.id
                                AND User.rights = 3
                                AND Employment.position = 3
                                AND Employment.iduser = User.id
                                AND Employment.idhotel IN (
                                        SELECT Employment.idhotel
                                        FROM User, Employment
                                        WHERE
                                            User.id = Employment.iduser
                                            AND Employment.iduser = ".$_SESSION['id']."
                                            AND Employment.position = 2
                                    )
                            )  AND User.password IS NOT NULL AND User.id = ".$_GET['id']." AND User.id != 1
                        ORDER BY User.id";
                        
                $result = $connection->query($sql);
                if ($result->num_rows == 0) {
                    header('Location: error.php');
                } else {
                    $tr = true;
                    while( $row = $result->fetch_assoc() ) {
                        if( $row[ 'id' ] == $_GET['id'] ){
                            $tr = false;
                        }
                    }

                    if( $tr == true ){
                        header('Location: error.php');
                    }
                }
            }
            $sql = "SELECT User.id,User.name, User.surname, User.phone_number, User.email,User.rights as urights, Rights.title as rtitle FROM User, Rights WHERE User.rights = Rights.id AND User.id = $_GET[id] ";

        } else if($_GET['type'] == "Hotel"){
            if($_SESSION['id'] != 1){
                $sql = "SELECT *,Hotel.id as hid FROM Hotel, Employment WHERE Employment.iduser = $_SESSION[id] AND Hotel.id != 0 AND Hotel.id = Employment.idhotel AND Hotel.id = $_GET[id]";
                $result = $connection->query($sql);
                if ($result->num_rows == 0) {
                    header('Location: error.php');
                }
            }
            $sql = "SELECT * FROM Hotel WHERE Hotel.id = $_GET[id]";
        } 
        $result = $connection->query($sql);

        
        if($_GET['type'] == "User"){
            echo "<form action='update.php' method='post'>";
            $rights = 0;
            $rights_title = '';
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<input class='editorInput' type='text' name='name' value='".$row["name"]."'>";
                    echo "<input class='editorInput' type='text' name='surname' value='".$row["surname"]."'>";
                    echo "<input class='editorInput' type='tel' name='phone_number'  placeholder='Phone number' value='".$row["phone_number"]."'>";
                    echo "<input class='editorInput' type='email' name='email' value='".$row["email"]."' readonly>";
                    echo "<input type='hidden' name='id' value='".$_GET['id']."'>";
                    $rights = $row["urights"];
                    $rights_title = $row["rtitle"];
                }
            } else{
                header('Location: error.php');
            }
            echo "<select name='employed[]' class='editorInput' multiple>";
            echo "<option value=\"0\" disabled>Select Hotels that belong to this user</option>";
                if($_SESSION['rights'] == 1)
                    $sql = "SELECT id,name FROM Hotel WHERE Hotel.id != 0";
                else{
                    $sql = "SELECT DISTINCT Hotel.id as id,Hotel.name as name
                    FROM User, Employment, Hotel
                    WHERE User.id = Employment.iduser AND User.id = $_SESSION[id] AND Hotel.id = Employment.idhotel";
                }
                //SELECT Hotel.id,Hotel.name FROM `Hotel`,`Employment` WHERE Hotel.id != 0 AND Hotel.id = Employment.idhotel AND Employment.iduser = 81
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row["id"]."'>".$row["name"]."</option>";
                    }
                }
            echo "</select>";
            echo "<select name='rights' class='editorInput'>";
            echo "<option value=".$rights." selected>".$rights_title."</option>";
            if($_SESSION['rights'] == 1)
                $sql = "SELECT id,title FROM Rights WHERE Rights.id != $rights AND Rights.id != 0";
            else
                $sql = "SELECT id,title FROM Rights WHERE Rights.id != $rights AND Rights.id > 1";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row["id"]."'>".$row["title"]."</option>";
                    }
                }
            ?>
            </select>
            <?php  
            echo "<input type='hidden' name='Users' value='1'>";
            echo "<input type='submit' value='Update'></form>";

        } else{
            if(!isset($_GET['type'])){
                echo "<form action='register.php' method='post'  enctype='multipart/form-data' id='regform' onsubmit=\"return confSubmit();\">";
            } else{
                echo "<form action='update.php' method='post'  enctype='multipart/form-data'>";
            }
            $stars = 0;
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<input class='editorInput' type='text' name='name'  value='".$row["name"]."'>";
                    echo "<input class='editorInput' type='text' name='country' value='".$row["country"]."'>";
                    echo "<input class='editorInput' type='text' name='city'  value='".$row["city"]."'>";
                    echo "<input class='editorInput' type='text' name='zip'  value='".$row["zip"]."'>";
                    echo "<input class='editorInput' type='text' name='street'  value='".$row["street"]."'>";
                    echo "<input class='editorInput' type='text' name='description' value='".$row["description"]."'>";
                    echo "<input type='hidden' name='id' value='".$_GET['id']."'>";
                    $stars = $row["stars"];
                }
            }else if ($result->num_rows == 0 && !isset($_GET['type'])){
                echo "<input class='editorInput' type='text' name='name'  placeholder='Name' required>";
                echo "<input class='editorInput' type='text' name='country' placeholder='Country' required>";
                echo "<input class='editorInput' type='text' name='city'  placeholder='City' required>";
                echo "<input class='editorInput' type='text' name='zip'  placeholder='Zip' required>";
                echo "<input class='editorInput' type='text' name='street'  placeholder='Street' required>";
                echo "<input class='editorInput' type='text' name='description' placeholder='Description' required>";
            } else{
                header('Location: error.php');
            }
            echo "<select id='stars' name='stars' class='editorInput' required>";
            echo "<option value='".$stars."' selected disabled>Stars</option>";
            ?>

            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
            <option value='4'>4</option>
            <option value='5'>5</option>
            </select>



            <h5>Gallery</h5>
            <?php
                if(!isset($_GET['type'])){
                    echo "<input type='file' id='gallery_id' name='gallery[]' accept='.png, .jpg, .jpeg'  multiple='multiple' required>";
                } else{?>
                <table cellspacing="0"  id="gallery">
                    <tr>
                        <th style='display:none'>#</th>
                        <th>Path</th>
                        <th></th>
                    </tr>
                <?php
                $sql = "SELECT * FROM Photos WHERE hotel_id=$_GET[id] AND type='Gallery'";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td style='display:none'>".$row["id"]."</td>";
                        echo "<td><a href='".$row["path"]."' target='_blank'>".$row["path"]."</a></td>";
                        echo "<td class='fas-td'><i class='fas fa-minus' onclick='delRow(this)'></i></td>";
                        echo "</tr>";
                    }
                }
            
            echo "</table>";
            echo "<div class='faplusdiv'><i class='fas fa-plus' onclick='addRow(\"gallery\")'></i></div>";
            }?>
            <h5>Title image</h5>
             <?php
                if(!isset($_GET['type'])){
                    echo "<input type='file' id='titleImage_id' name='titleImage' accept='.png, .jpg, .jpeg' required>";
                } else{?>
            <table cellspacing="0"  id="title">
                <tr>
                    <th style='display:none'>#</th>
                    <th>Path</th>
                    <th></th>
                </tr>
            <?php      
                $sql = "SELECT * FROM Photos WHERE hotel_id=$_GET[id] AND type='Title'";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) { 
                        echo "<tr>";
                        echo "<td style='display:none'>".$row["id"]."</td>";
                        echo "<td><a href='".$row["path"]."' target='_blank'>".$row["path"]."</a></td>";
                        echo "<td class='fas-td'><i class='fas fa-minus' onclick='delRow(this)'></i></td>";
                        echo "</tr>";
                    }
                }
            echo "</table>";
            echo "<div class='faplusdiv'><i class='fas fa-plus' onclick='addRow(\"title\")'></i></div>";
        }?>


            <?php  
            echo "<input type='hidden' name='Hotels' value='1'>";

            if(!isset($_GET['type'])){
                echo "<h5>Rooms</h5>";
                echo "<div id='rooms'></div>";
                echo "<div class='faplusdiv'><i class='fas fa-plus' onclick='addRooms(\"rooms\",0)'></i></div>";
                echo "<div class=\"bttn\">";
                echo "<input type='submit' value='Submitt' id='submiter' class='btn btn-primary'></div></form>";
            } else{
                echo "<h5>Rooms</h5>";
            ?>
            <table cellspacing="0"  id="roomstype">
                <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Number of rooms</th>
                    <th>Price per bed</th>
                    <th>Principal</th>
                    <th></th>
                </tr>
            <?php
                $sql = "SELECT COUNT(*) as numberofrooms, t.type as typ, t.description as descr, t.pricePerBed as ppb, r.beds, r.type as rtype, t.principal as princ FROM RoomType as t, Room as r where t.id = r.type and t.hotel_id = r.hotel_id and r.hotel_id = $_GET[id] group by typ";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) { 
                        echo "<tr>";
                        echo "<td>".$row["typ"]."</td>"; 
                        echo "<td>".$row["descr"]."</td>";
                        echo "<td>".$row["numberofrooms"]."</td>";
                        echo "<td>".$row["ppb"]."</td>";
                        echo "<td>".$row["princ"]."</td>";
                        echo "<td class='fas-td'><i class='fas fa-minus' data-hid='". $_GET['id']."' data-rtype='". $row["rtype"]."' onclick='delRow(this)' ></i></td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
                echo "<div class='faplusdiv'><i class='fas fa-plus' onclick='addRow(\"roomstype\",0)'></i></div>";
                echo "<input type='submit' value='Update' class='submitUpdate'></form>";
            }
        }
            ?>
</div>

<div class="addRow" id="addRow_gallery">
        <h3>Add files to gallery</h3>
        <form action='update.php' method='post'  enctype="multipart/form-data">
            <input type="file"  name="gallery[]" accept="image/png, image/jpeg"  multiple="multiple" required>
            
            <input type='hidden' name='Gallery' value='1'>
            <input type='hidden' name='hotel_id' value='<?php echo $_GET['id']; ?>'>
            <input type='submit' value='Add' class='btn btn-primary'>
        </form>
      
        <button onclick="addRow('gallery')" class="closer">Close</button>
    </div>

<div class="addRow" id="addRow_title">
        <h3>Update Title image</h3>
        <p>Warning, removes any previous title images for this hotel</p>
        <form action='update.php' method='post'  enctype="multipart/form-data">
            <input type="file" id="titleImage" name="titleImage" accept="image/png, image/jpeg" required>
            <hr>
            <input type='hidden' name='Title' value='1'>
            <input type='hidden' name='hotel_id' value='<?php echo $_GET['id']; ?>'>
            <input type='submit' value='Add' class='btn btn-primary'>
        </form>
       
        <button onclick="addRow('title')" class="closer">Close</button>
    </div>
<div class="addRow" id="addRow_rooms">
        <h5>Enter room type name and number of beds in this type</h5>
        <input type='text' name='rtype' placeholder='Room type name' id='rtypename' required>
        <input type='number' name='rbeds' min='1' max='8' placeholder='Number of beds' id='rbeds' required>
        <input type='number' name='bedprice' min='1' placeholder='Price per bed' id='bedprice' required>
        <input type='text' name='descr' placeholder='Room description' id='rdescr' required>
        <input type='number' name='principal' min='0' placeholder='Principal' id='principal' required>
        <input type='number' name='numofrooms' min='1' placeholder='Number of rooms of this type' id='numofrooms' required>

        <select id='equip' name='equip' multiple required>
            <option value="" disabled selected>Equip</option>
    <?php      
            $sql = "SELECT * FROM Equipment ORDER BY id";
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row["id"]."'>".$row["name"]."</option>";
                }
            }
    ?>
            </select>
        <input type='hidden' name='rooms' value='1'>
        <input type='hidden' name='hotel_id' value='<?php echo $_GET['id']; ?>'>
        <div style="text-align: center">
        <button onclick="addRooms('rooms',1)"  class='btn btn-primary'>Add</button>
        </div>
        <button onclick="addRooms('rooms',0)" class="closer">Close</button>
    </div>
<div class="addRow" id="addRow_roomstype">
        <h5>Enter room type name and number of beds in this type</h5>
        <form action='update.php' method='post'  enctype="multipart/form-data">
            <input type='text' name='rtype' placeholder='Room type name' id='rtypename_update' required>
            <input type='number' name='rbeds' min='1' max='8' placeholder='Number of beds' id='rbeds_update' required> 
            <input type='number' name='bedprice' min='1' placeholder='Price per bed' id='bedprice_update' required>
            <input type='text' name='descr' placeholder='Room description' id='rdescr_update' required>
            <input type='number' name='principal' min='0' placeholder='Select principal' id='principal_update' required>
            <input type='number' name='numofrooms' min='1' placeholder='Number of rooms of this type' id='numofrooms_update' required>
            <select id='equip_update' name='equip[]' multiple required>
            <option value="" disabled selected>Equip</option>
    <?php      
            $sql = "SELECT * FROM Equipment ORDER BY id";
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row["id"]."'>".$row["name"]."</option>";
                }
            }
    ?>
            </select>
            <input type='hidden' name='Rooms' value='1'>
            <input type='hidden' name='hotel_id' value='<?php echo $_GET['id']; ?>'>
            <input type='submit' value='Add' class='btn btn-primary'>
        </form>
       
        <button onclick="addRow('roomstype')" class="closer">Close</button>
    </div>
</body>

</html>