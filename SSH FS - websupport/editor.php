<?php
session_start();

if (!isset($_SESSION['loggedin']) || !($_SESSION['rights'] == 1 || $_SESSION['rights'] == 2)) {
	header('Location: index.php');
	exit; 
}
        include_once("config.php");
        include_once("functions.php");
        include_once("db.php");
        printHTMLheader( "Hotels" );
        printHeader();
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="style.css">
    <script src="scripts.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .editorInput{
            padding: 15px;
            margin : 5px 0 20px;
            display: inline-block;
            border : 0;
            background: #f1f1f1;
        }
        .content{
            margin-bottom: 100px;
        }
        .removal{
            width:20px; 
            height: 20px; 
        }
        td,th{
            vertical-align: middle;
            padding: 3px;
        }
        p {
            margin-bottom: 0;
        }
        .fa-minus{
            color: red;
            cursor: pointer;
        }

        .addRow {
            width: 500px;
            position: fixed;
            top: 200px;
            left: 500px;
            padding: 10px;
            border: 10px solid #1e90ff;
            background: #fff;
            display: none;
        }

        .submitUpdate{
            margin-top: 50px;
        }
      
       
    </style>
</head>

<body>
<div class="content">
    <?php
        if(!isset($_GET['type'])){
            echo "<h2>Creating new hotel</h2>";
        } else{
            echo "<h2>Editing ".$_GET['type']." with ID ".$_GET['id']."</h2>";
        }

        if($_GET['type'] == "User"){
            $sql = "SELECT User.id,User.name, User.surname, User.phone_number, User.email, Hotel.name AS hname, Rights.title FROM User, Hotel, Rights WHERE User.employed = Hotel.id AND User.rights = Rights.id AND User.id = $_GET[id] ";
        } else{
            $sql = "SELECT * FROM Hotel WHERE Hotel.id = $_GET[id]";
        }
        $result = $connection->query($sql);

        
        if($_GET['type'] == "User"){
            echo "<form action='update.php' method='post'>";
            $employed = 0;
            $rights = 0;
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<input class='editorInput'type='text' name='name' value='".$row["name"]."'></input>";
                    echo "<input class='editorInput'type='text' name='surname' value='".$row["surname"]."'></input>";
                    echo "<input class='editorInput'type='tel' name='phone_number' value='".$row["phone_number"]."'></input>";
                    echo "<input class='editorInput'type='email' name='email' value='".$row["email"]."'></input>";
                    echo "<input class='editorInput'type='password' name='password' placeholder='Password'></input>";
                    echo "<input type='hidden' name='id' value='".$_GET['id']."'></input>";
                }
            }
            echo "<select name='employed' class='editorInput'>";
            echo "<option value=".$employed." selected>NONE</option>";
                $sql = "SELECT id,name FROM Hotel";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row["id"]."'>".$row["name"]."</option>";
                    }
                }
            echo "</select>";
            echo "<select name='rights' class='editorInput'>";
            echo "<option value=".$rights." selected>NONE</option>";
                $sql = "SELECT id,title FROM Rights";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row["id"]."'>".$row["title"]."</option>";
                    }
                }
            ?>
            </select>
            <?php  
            echo "<input type='hidden' name='Users' value='1'></input>";
            echo "<input type='submit' value='Update'></input></form>";

        } else{
            if(!isset($_GET['type'])){
                echo "<form action='register.php' method='post'  enctype='multipart/form-data'>";
            } else{
                echo "<form action='update.php' method='post'  enctype='multipart/form-data'>";
            }
            $stars = 0;
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<input class='editorInput'type='text' name='name'  value='".$row["name"]."'></input>";
                    echo "<input class='editorInput'type='text' name='country' value='".$row["country"]."'></input>";
                    echo "<input class='editorInput'type='text' name='city'  value='".$row["city"]."'></input>";
                    echo "<input class='editorInput'type='text' name='zip'  value='".$row["zip"]."'></input>";
                    echo "<input class='editorInput'type='text' name='street'  value='".$row["street"]."'></input>";
                    echo "<input class='editorInput'type='text' name='description' value='".$row["description"]."'></input>";
                    echo "<input type='hidden' name='id' value='".$_GET['id']."'></input>";
                    $stars = $row["stars"];
                }
            }
            if ($result->num_rows == 0){
                echo "<input class='editorInput'type='text' name='name'  placeholder='Name' required></input>";
                echo "<input class='editorInput'type='text' name='country' placeholder='Country' required></input>";
                echo "<input class='editorInput'type='text' name='city'  placeholder='City' required></input>";
                echo "<input class='editorInput'type='text' name='zip'  placeholder='Zip' required></input>";
                echo "<input class='editorInput'type='text' name='street'  placeholder='Street' required></input>";
                echo "<input class='editorInput'type='text' name='description' placeholder='Description' required></input>";
            }
            echo "<select id='stars' name='stars'>";
            echo "<option value='".$stars."' selected>Stars</option>";
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
                    echo "<input type='file' id='gallery' name='gallery[]' accept='image/png, image/jpeg'  multiple='multiple' required>";
                } else{?>
                <table cellspacing="0" cellpadding="0" id="gallery">
                    <tr>
                        <th>#</th>
                        <th>Room_ID</th>
                        <th>Info</th>
                        <th>Path</th>
                        <th>Remove from FTP?</th>
                        <th></th>
                    </tr>
                <?php
                $sql = "SELECT * FROM Photos WHERE hotel_id=$_GET[id] AND type='Gallery'";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row["id"]."</td>";
                        echo "<td>".$row["room_id"]."</td>";
                        echo "<td>".$row["info"]."</td>";
                        echo "<td><a href='".$row["path"]."' target='_blank'>".$row["path"]."</a></td>";
                        echo "<td><input type='checkbox'></input></td>";
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
                    echo "<input type='file' id='titleImage' name='titleImage' accept='image/png, image/jpeg' required>";
                } else{?>
            <table cellspacing="0" cellpadding="0" id="title">
                <tr>
                    <th>#</th>
                    <th>Info</th>
                    <th>Path</th>
                    <th>Remove from FTP?</th>
                    <th></th>
                </tr>
            <?php      
                $sql = "SELECT * FROM Photos WHERE hotel_id=$_GET[id] AND type='Title'";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) { 
                        echo "<tr>";
                        echo "<td>".$row["id"]."</td>";
                        echo "<td>".$row["info"]."</td>";
                        echo "<td><a href='".$row["path"]."' target='_blank'>".$row["path"]."</a></td>";
                        echo "<td><input type='checkbox'></input></td>";
                        echo "<td class='fas-td'><i class='fas fa-minus' onclick='delRow(this)'></i></td>";
                        echo "</tr>";
                    }
                }
            echo "</table>";
            echo "<div class='faplusdiv'><i class='fas fa-plus' onclick='addRow(\"title\")'></i></div>";
        }?>


            <?php  
            echo "<input type='hidden' name='Hotels' value='1'></input>";

            if(!isset($_GET['type'])){
                echo "<h5>Rooms</h5>";
                echo "<div id='rooms'></div>";
                echo "<div class='faplusdiv'><i class='fas fa-plus' onclick='addRooms(\"rooms\",0)'></i></div>";
                echo "<input type='submit' value='Submit' class='submitUpdate'></input></form>";
            } else{
                echo "<h5>Rooms</h5>";
            ?>
            <table cellspacing="0" cellpadding="0" id="roomstype">
                <tr>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Number of rooms</th>
                    <th>Price per bed</th>
                    <th></th>
                </tr>
            <?php
                $sql = "SELECT COUNT(*) as numberofrooms, t.type as typ, t.description as descr, t.pricePerBed as ppb, r.beds, r.type as rtype FROM RoomType as t, Room as r where t.id = r.type and t.hotel_id = r.hotel_id and r.hotel_id = $_GET[id] group by typ";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) { 
                        echo "<tr>";
                        echo "<td>".$row["typ"]."</td>"; 
                        echo "<td>".$row["descr"]."</td>";
                        echo "<td>".$row["numberofrooms"]."</td>";
                        echo "<td>".$row["ppb"]."</td>";
                        echo "<td class='fas-td'><i class='fas fa-minus' data-hid='". $_GET['id']."' data-rtype='". $row["rtype"]."' onclick='delRow(this)' ></i></td>";
                        echo "</tr>";
                    }
                }
                echo "</table>";
                echo "<div class='faplusdiv'><i class='fas fa-plus' onclick='addRow(\"roomstype\",0)'></i></div>";
                echo "<input type='submit' value='Update' class='submitUpdate'></input></form>";
            }
        }
            ?>
</div>

<div class="addRow" id="addRow_gallery">
        <h3>Add files to gallery</h3>
        <form action='update.php' method='post'  enctype="multipart/form-data">
            <input type="file" id="gallery" name="gallery[]" accept="image/png, image/jpeg"  multiple="multiple" required>
            <select id='room_id' name='room_id'>
            <option value="" disabled selected>Room_ID</option>
    <?php      
            $sql = "SELECT id FROM Room ORDER BY id";
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row["id"]."'>".$row["id"]."</option>";
                }
            }
    ?>
            </select>
            <input type='text' name='info'>
            <input type='hidden' name='Gallery' value='1'>
            <input type='hidden' name='hotel_id' value='<?php echo $_GET['id']; ?>'>
            <input type='submit' value='Add'>
        </form>
      
        <button onclick="addRow('gallery')" class="closer">Close</button>
    </div>

<div class="addRow" id="addRow_title">
        <h3>Update Title image</h3>
        <p>Warning, removes any previous title images for this hotel</p>
        <form action='update.php' method='post'  enctype="multipart/form-data">
            <input type="file" id="titleImage" name="titleImage" accept="image/png, image/jpeg" required>
            <input type='text' name='info'>
            <p>Remove any previous title images ? (Default is yes)</p>
            <input type='checkbox' name='removeTitles' checked>
            <hr>
            <input type='hidden' name='Title' value='1'>
            <input type='hidden' name='hotel_id' value='<?php echo $_GET['id']; ?>'>
            <input type='submit' value='Add'>
        </form>
       
        <button onclick="addRow('title')" class="closer">Close</button>
    </div>
<div class="addRow" id="addRow_rooms">
        <h5>Enter room type name and number of beds in this type</h5>
        <input type='text' name='rtype' placeholder='Room type name' id='rtypename'>
        <input type='number' name='rbeds' min='1' max='8' placeholder='Number of beds' id='rbeds'>
        <input type='number' name='bedprice' placeholder='Price per bed' id='bedprice'>
        <input type='text' name='descr' placeholder='Room description' id='rdescr'>
        <input type='hidden' name='rooms' value='1'>
        <input type='hidden' name='hotel_id' value='<?php echo $_GET['id']; ?>'>
        <div style="text-align: center">
        <button onclick="addRooms('rooms',1)" >Add</button>
        </div>
        <button onclick="addRooms('rooms',0)" class="closer">Close</button>
    </div>
<div class="addRow" id="addRow_roomstype">
        <h5>Enter room type name and number of beds in this type</h5>
        <form action='update.php' method='post'  enctype="multipart/form-data">
            <input type='text' name='rtype' placeholder='Room type name' id='rtypename'>
            <input type='number' name='rbeds' min='1' max='8' placeholder='Number of beds' id='rbeds'>
            <input type='number' name='bedprice' placeholder='Price per bed' id='bedprice'>
            <input type='text' name='descr' placeholder='Room description' id='rdescr'>
            <input type='number' name='numofrooms' placeholder='Number of rooms of this type' id='numofrooms'>
            <input type='hidden' name='Rooms' value='1'>
            <input type='hidden' name='hotel_id' value='<?php echo $_GET['id']; ?>'>
            <input type='submit' value='Add'>
        </form>
       
        <button onclick="addRow('roomstype')" class="closer">Close</button>
    </div>
</body>

</html>