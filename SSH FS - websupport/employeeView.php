<?php
session_start();
    if (!isset($_SESSION['loggedin']) || !($_SESSION['rights'] == 3)) {
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
    <div class="content">
        <?php
            $sql = "SELECT Hotel.name as hname from Hotel, User WHERE Hotel.id = User.employed AND User.id = " . $_SESSION['id'];
            $result = $connection->query($sql);
            $hotel_name = '';
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $hotel_name = $row["hname"];
                }
            }
            echo "<h2>".$hotel_name."</h2>";
            echo "<h2>Reservations</h2>";
        ?>

        <table id="reservations_table">
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

</div>

</body>

</html>