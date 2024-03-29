<?php
    include_once("include.php");
    sessionTimeout();
    checkRights(3);
    printHTMLheader("Manage reservations");
    printHeader();
?>
<!-- 
    <meta charset="utf-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="style.css">
    <script src="scripts.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> -->
    <style>

       
        td,th{
            vertical-align: middle;
            padding: 1px;
        }
        .content {
            max-width: 1300px;
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
        .statsel{
            
        }

        
    </style>
</head>

<body>
<?php if(isset($_GET['act'])){
        echo "<div class='confirm' id='confirm'> <h5> Data updated</h5> </div>";
        echo "<script>$(document).ready( function () {";
        echo "setTimeout(function(){ $('#confirm').css('display', 'none'); }, 2000);});</script>";
    }?>
    <div class="content">
        <?php
            if(isset($_GET['hid'])) {
                $sql = "SELECT Hotel.name as hname, Hotel.id as hid from Hotel WHERE Hotel.id = $_GET[hid]";
            // } else {
            //     $sql = "SELECT Hotel.name as hname, Hotel.id as hid from Hotel, User WHERE Hotel.id = User.employed AND User.id = " . $_SESSION['id'];
            // }
            $result = $connection->query($sql);
            $hotel_name = '';
            $hid = 0;
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $hotel_name = $row["hname"];
                    $hid = $row["hid"];
                }
            }
            echo "<h2>".$hotel_name."</h2>";
            echo "<h2>Reservations</h2>";
        ?>

        <table id="reservations_table" class="table compact">
        <thead>
        <tr>
            <th>ID</th>
            <th>Room ID</th>
            <th>Username</th>
            <th>Surname</th> 
            <th>Email</th> 
            <th>Phone</th> 
            <th>Arrival</th>
            <th>Departure</th> 
            <th>State</th> 
            <th>Principal</th>
            <th>Checkin</th>
            <th>Checkout</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
    <?php      
        $sql = "SELECT Reservation.id as rid, Reservation.room_id, User.surname, User.name, User.email, User.phone_number, Reservation.arrival, Reservation.departure, Reservation.stav, RoomType.principal, Reservation.check_in, Reservation.check_out, State.state as state
                FROM Reservation, User, State, RoomType, Room
                WHERE Reservation.hotel_id = ".$hid."
                AND Reservation.user_id = User.id
                AND Reservation.stav = State.id
                AND Reservation.room_id = Room.id
                AND Room.type = RoomType.id";
        $result = $connection->query($sql);
        $enableCheckout = false;
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["rid"]."</td>";
                echo "<td>".$row["room_id"]."</td>";
                echo "<td>".$row["name"]."</td>";
                echo "<td>".$row["surname"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "<td>".$row["phone_number"]."</td>";
                echo "<td>".$row["arrival"]."</td>";
                echo "<td>".$row["departure"]."</td>";
                echo "<td><button class='check minheight' onclick=\"changeState(this,'state','".$row["principal"]."')\">".$row["state"]."</button></td>";

                if($row["principal"] == ''){
                    echo "<td>None</td>";
                } else{
                    echo "<td>".$row["principal"]."</td>";
                }
                if($row["check_in"] == ''){
                    if( $row[ 'state' ] == "Confirmed" ){
                        echo "<td><button class='check minheight' onclick=\"addCheck(this,'checkin')\">Add checkin</button></td>";
                    } else {
                        echo "<td><button style=\"background-color: #A9A9A9;\" class='check minheight' onclick=\"\">Add checkin</button></td>";
                    }
                    $enableCheckout = false;
                } else{
                    echo "<td>".$row["check_in"]."</td>";
                    $enableCheckout = true;
                }
                if($row["check_out"] == ''){
                    if( $enableCheckout ){
                        echo "<td><button class='check minheight' onclick=\"addCheck(this,'checkout')\">Add checkout</button></td>";
                    } else {
                        echo "<td><button style=\"background-color: #A9A9A9;\" class='check minheight' onclick=\"addCheck(this,'checkout')\">Add checkout</button></td>";
                    }
                } else{
                    echo "<td>".$row["check_out"]."</td>";
                }
                echo "<td class='fas-td-arrow'><i class='fas fa-arrow-right' onclick=\"updateStatus(this,'status')\"></i></i></td>";
                echo "<td class='fas-td'><i class='fas fa-minus' onclick='delRow(this)'></i></td>";
                echo "</tr>";
            }
        }
        if ($result->num_rows == 0){
            echo "<td colspan='14' style='text-align:center'>This hotel doesnt have any ongoing reservations</td>";
        }
    ?>
    </tbody>
    </table>
    <?php
        echo "<div class=\"faplusdiv\"><i class=\"fas fa-plus\" onclick=\"window.location.href='hotelView.php?guests=1&id=".$hid."'\"></i></div>";
    } else {

        print
        ("

        <div class=\"m-main\">

        <div class=\"row ktopmargin\">
            <div class=\"col\">
                <h2>Manage reservations</h2>
            </div>
        </div>
        
        <div class=\"content\"> 
        <table id=\"reservations_table\" class=\"table compact\">
        <thead>
            <tr>
                <th>#</th>
                <th>Hotel</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>");

        $id = $_SESSION['id'];
        echo("<script>console.log('PHP: " . $id . "');</script>");
        $sql = "SELECT Hotel.id, Hotel.name
                FROM Employment, Hotel
                WHERE
                    Employment.iduser = $id
                    AND Employment.idhotel = Hotel.id";
        
        $result = $connection->query($sql);

        $hotels = array();
        $ids = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                array_push($hotels, $row["name"]);
                array_push($ids, $row["id"]);
            }
        }

        for ($i = 0; $i < count($ids); $i++) {

            $id = $ids[$i];
            $hotel = $hotels[$i];

            $button = "<form class=\"mform\" action=\"employeeView.php?hid=$id\">
                          <input name=\"hid\" value=\"$id\" type=\"hidden\">
                          <input class=\"btn btn-primary active\" type=\"submit\" aria-pressed=\"true\" value=\"Manage reservations\"/>
                       </form>";

            print
            ("
              <tr>
                  <th scope=row> $id </th>
                  <td> $hotel </td>
                  <td> $button </td>
              </tr>
            ");
        }

        print
        ("
            </tbody>
            </table>
            </div>
        ");

    }

    
    ?>
    

</div>


    <div class="addRow" id="addRow_checkin">
        <h3> Choose date and time</h3>
        <form action='update.php' method='post'>
            <input type='date' name='date' value='<?php echo date('yy-m-d');?>' id='date' required>
            <input type='time' name='time' value='<?php echo date('G:i');?>' id='time' required>
            <input type='hidden' name='rid' value='' id='checkin_rid'>
            <input type='hidden' name='checkin' value='1'>
            <input type='submit' value='Add' class="btn btn-primary">
        </form>
        <button onclick="addRow('checkin')" class="closer">Close</button>
    </div>
    <div class="addRow" id="addRow_checkout">
        <h3> Choose date and time</h3> 
        <form action='update.php' method='post'>
            <input type='date' name='date' value='<?php echo date('yy-m-d');?>'  placeholder='Date' id='date' required>
            <input type='time' name='time' value='<?php echo date('G:i');?>' placeholder='Time' id='time' required>
            <input type='hidden' name='rid' value='' id='checkout_rid'> 
            <input type='hidden' name='checkout' value='1'>
            <input type='submit' value='Add' class="btn btn-primary">
        </form>
        <button onclick="addRow('checkout')" class="closer">Close</button> 
    </div>
    <div class="addRow" id="addRow_status">
        <h3> Choose date and time</h3> 
        <form action='update.php' method='post'>
            <label for="arrival">Arrival</label>
            <input type='date' name='arrival' value='<?php echo date('yy-m-d');?>'  placeholder='Date' required>
            <label for="departure">Departure</label>
            <input type='date' name='departure' value='<?php echo date('yy-m-d');?>'  placeholder='Date' required>
            <label for="checkin">Checkin</label>
            <input type='date' name='checkin' value='<?php echo date('yy-m-d');?>'  placeholder='Date' required>
            <input type='time' name='ctime' value='<?php echo date('G:i');?>' placeholder='Time' id='ctime' required>
            <label for="checkout">Checkout</label>
            <input type='date' name='checkout' value='<?php echo date('yy-m-d');?>'  placeholder='Date' required>
            <input type='time' name='cotime' value='<?php echo date('G:i');?>' placeholder='Time' id='cotime' required>
            <label for="arrival">Status</label>
            <select id='status' name='rstatus' required> 
            <option value="" disabled selected>Status</option>
            <?php      
            $sql = "SELECT * FROM State";
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row["id"]."'>".$row["state"]."</option>";
                }
            }
            ?>  
            </select>
            <input type='hidden' name='rid' value='' id='status_rid'> 
            <input type='hidden' name='status' value='1'>
            <input type='submit' value='Update' class="btn btn-primary">
        </form>
        <button onclick="addRow('status')" class="closer">Close</button> 
    </div>
    <div class="addRow" id="addRow_reservation">
        <h3> Choose date and time</h3> 
        <form action='bookView.php' method='post'>
            <input type='hidden' name='id' value=<?php echo $hid; ?>> 
            <select id='room' name='type' required>
            <option value="" disabled selected>Room</option>
            <?php      
            $sql = "SELECT DISTINCT RoomType.type FROM RoomType WHERE RoomType.hotel_id = ".$hid;
            $result = $connection->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row["id"]."'>".$row["number"]." - ".$row["type"]."</option>";
                }
            }
            ?>
            </select>
            <label for="arrival">Arrival</label>
            <input type='date' name='from' placeholder='Date' required>
            <label for="arrival">Departure</label>
            <input type='date' name='to' placeholder='Date' required>
            <input type='hidden' name='guests' value='1'>
            <input type='submit' value='Add' class="btn btn-primary">
        </form>
        <button onclick="addRow('reservation')" class="closer">Close</button>
    </div>
    <div class="addRow" id="addRow_state">
    <h3> Choose date and time</h3> 
        <form action='update.php' method='post'>
    <?php
            echo "<select id='principal' name='rsstatus' class='statsel'>";
            echo "<option value='' disabled selected>Status</option>";
            $sql1 = "SELECT * FROM State";
            $result1 = $connection->query($sql1);
            if ($result1->num_rows > 0) {
                while($row1 = $result1->fetch_assoc()) {
                    echo "<option value='".$row1["id"]."'>".$row1["state"]."</option>";
                }
            }
            echo "</select>";
            echo "<select id='nonprincipal' name='rsstatus_non' class='statsel'>";
            echo "<option value='' disabled selected>Status</option>";
            $sql1 = "SELECT * FROM State where id != 2 and id != 3";
            $result1 = $connection->query($sql1);
            if ($result1->num_rows > 0) {
                while($row1 = $result1->fetch_assoc()) {
                    echo "<option value='".$row1["id"]."'>".$row1["state"]."</option>";
                }
            }
            echo "</select>";
    ?>
    <input type='hidden' name='rid' value='' id='state_rid'>
            <input type='hidden' name='state' value='1'>
            <input type='submit' value='Update' class="btn btn-primary">
    </form>
        <button onclick="addRow('state')" class="closer">Close</button>
    </div>
    
</body>
 <script>$(document).ready( function () {
    $('#reservations_table').DataTable({
        responsive : true
    });
} ); </script>
</html>
