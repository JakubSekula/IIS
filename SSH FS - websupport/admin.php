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
        .addRow {
            width: 500px;
            position: fixed;
            top: 10px;
            left: 500px;
            padding: 10px;
            border: 10px solid #1e90ff;
            background: #fff
        }


        .addRow,option[value=""][disabled] {
            display: none
        }       
        td,th{
            vertical-align: middle;
            padding: 2px;
        }
        .content {
            width: 1000px;
            margin-bottom: 200px;
        }
        .fas-td{
            color: red;
            cursor: pointer;
            width: 30px;
        }
        .fas-td-arrow{
            width: 30px;

            text-align: center;
            color: dodgerblue;
            cursor: pointer;
        }

    </style>
</head>

<body>
    <!-- <div class="header">
        <h1>Hilton Hotels</h1>
        <button onclick="window.location.href='logout.php';" type="button" class="actionButtons">Logout</button> 
    </div> -->
   

    <div class="content">
    <?php
        $headertext = $_SESSION['rights']==1 ? "<h2>Admin Panel</h2><h2>Users</h2>" : "<h2>Owner Panel</h2><h2>Employeets</h2>";
        echo $headertext;
    ?>

    <table id="user_table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Employee</th>
            <th>Rights</th>
            <th></th>
            <th></th>
        </tr>
    <?php      
        if( $_SESSION['rights'] == 1){
            $sql = "SELECT User.id,User.name, User.surname, User.phone_number, User.email, Hotel.name AS hname, Rights.title FROM User, Hotel, Rights WHERE User.employed = Hotel.id AND User.rights = Rights.id";
        } else if ( $_SESSION['rights'] == 2) {
            $sql = "SELECT User.id,User.name, User.surname, User.phone_number, User.email, Hotel.name AS hname, Rights.title FROM User, Hotel, Rights, Owns WHERE Owns.idowner = $_SESSION[id] AND User.employed = Hotel.id AND User.rights = Rights.id AND Hotel.id = Owns.idhotel";
        }
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["name"]."</td>";
                echo "<td>".$row["surname"]."</td>";
                echo "<td>".$row["phone_number"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "<td>".$row["hname"]."</td>";
                echo "<td>".$row["title"]."</td>";
                echo "<td class='fas-td-arrow'><i class='fas fa-arrow-right' onclick=\"window.location.href='editor.php?type=User&id=".$row["id"]."'\"></i></i></td>";
                echo "<td class='fas-td'><i class='fas fa-minus' onclick='delRow(this)'></i></td>";
                echo "</tr>";
            }
        }
    ?>
    </table>
    <div class="faplusdiv"><i class="fas fa-plus" onclick="addRow('user')"></i></div>

     <h2>Hotels</h2>
    <table id="hotel_table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Country</th>
            <th>City</th>
            <th>Street</th>
            <th>Zip code</th>
            <th>Stars</th>
            <th>Rating</th>
            <th>Description</th>
            <th></th>
            <th></th>
        </tr>
     <?php      
        if( $_SESSION['rights'] == 1){
            $sql = "SELECT * FROM Hotel WHERE Hotel.id != 0";
        } else if ( $_SESSION['rights'] == 2) {
            $sql = "SELECT * FROM Hotel, Owns WHERE Owns.idowner = $_SESSION[id] AND Hotel.id != 0 AND Hotel.id = Owns.idhotel";
        }
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["name"]."</td>";
                echo "<td>".$row["country"]."</td>";
                echo "<td>".$row["city"]."</td>";
                echo "<td>".$row["street"]."</td>";
                echo "<td>".$row["zip"]."</td>";
                echo "<td>".$row["stars"]."</td>";
                echo "<td>".$row["rating"]."</td>";
                if(strlen($row["description"]) > 50){
                    echo "<td>".substr($row["description"],0,50)."...</td>";
                } else{
                    echo "<td>".$row["description"]."</td>";
                }
                echo "<td class='fas-td-arrow'><i class='fas fa-arrow-right' onclick=\"window.location.href='editor.php?type=Hotel&id=".$row["id"]."'\"></i></i></td>";
                echo "<td class='fas-td'><i class='fas fa-minus' onclick='delRow(this)'></i></td>";
                echo "</tr>";
            }
        }
    ?>
    </table>
    <!-- <div class="faplusdiv"><i class="fas fa-plus" onclick="addRow('hotel')"></i></div> -->
    <div class="faplusdiv"><i class="fas fa-plus" onclick="window.location.href='editor.php'"></i></div>
    </div>



    <div class="addRow" id="addRow_user">
        <form action='register.php' method='post'>
            <input type='text' name='name' placeholder='Name' id='hotel_name' required>

            <input type='text' name='surname' placeholder='Surname' id='surname' required>

            <input type='text' name='phone_number' placeholder='Phone Number' id='phone_number' required>

            <input type='text' name='email' placeholder='Email' id='email' required>

            <input type='password' name='password' placeholder='Password' id='password' required>
        
            <select id='hotel_id' name='hotel_id' required>
            <option value="" disabled selected>Employed</option>
                
    <?php      
            if( $_SESSION['rights'] == 1){
                $sql = "SELECT id,name FROM Hotel";
            } else if ( $_SESSION['rights'] == 2) {
                $sql = "SELECT DISTINCT Hotel.id,Hotel.name FROM Hotel, Owns WHERE Owns.idowner = $_SESSION[id] AND Hotel.id = Owns.idhotel";
            }
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row["id"]."'>".$row["name"]."</option>";
                }
            }
    ?>
            </select>
            

            
            
    <?php      
            if( $_SESSION['rights'] == 1){
                $sql = "SELECT id,title FROM Rights";
                echo "<select id='rights' name='rights' required>";
                echo "<option value='' disabled selected>Rights</option>";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row["id"]."'>".$row["title"]."</option>";
                    }
                }
                echo "</select>";
            } else if ( $_SESSION['rights'] == 2) {
                echo "<select id='rights' name='rights' required>";
                echo "<option value='3' selected>Receptionist</option>";
                echo "</select>";
            }

    ?>
            
            
            <input type='hidden' name='users' value='1'>
            <input type='submit' value='Add'>
            </form>
            <button onclick="addRow('user')" class="closer">Close</button>
    </div>

    <div class="addRow" id="addRow_hotel">
        <form action='register.php' method='post'  enctype="multipart/form-data">
            <input type='text' name='name' placeholder='Name' id='name' required>
        
            <input type='text' name='country' placeholder='Country' id='country' required>

            <input type='text' name='city' placeholder='City' id='city' required>

            <input type='text' name='street' placeholder='Street' id='street' required>

            <input type='text' name='zip' placeholder='Zip' id='zip' required>

            <select id='stars' name='stars' required>
            <option value="" disabled selected>Stars</option>
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='3'>3</option>
            <option value='4'>4</option>
            <option value='5'>5</option>
            </select>

            <input type='text' name='description' placeholder='Description' id='description' required>
            <label for="gallery">Gallery</label>
            <input type="file" id="gallery" name="gallery[]" accept="image/png, image/jpeg"  multiple="multiple" required>
            <label for="titleImage">Title Image</label>
            <input type="file" id="titleImage" name="titleImage" accept="image/png, image/jpeg" required>

            <input type='hidden' name='hotels' value='1'>
            <input type='submit' value='Add'>
        </form>
      
        <button onclick="addRow('hotel')" class="closer">Close</button>
    </div>

</body>

</html>