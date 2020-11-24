<?php
    include_once("include.php");
    sessionTimeout();
    checkRights(2);

    printHTMLheader("Admin page");
    printHeader();
?>



    <meta charset="utf-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        

    </style>
</head>

<body onload="multiselect()">
    <!-- <div class="header">
        <h1>Hilton Hotels</h1>
        <button onclick="window.location.href='logout.php';" type="button" class="actionButtons">Logout</button> 
    </div> -->
   

    <div class="content">
    <?php
        $headertext = $_SESSION['rights']==1 ? "<h2>Admin page</h2><h2>Users</h2>" : "<h2>Owner Panel</h2><h2>Users</h2>";
        echo $headertext;
    ?>
    <div class="thetables"> 
    <table id="user_table" style="width:100%" class="table dt-responsive compact">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Surname</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Rights</th>
            <th></th>
            <?php 
                if($_SESSION['rights'] == 1)
                echo "<th></th>";
            ?>
            
        </tr>
        </thead>
        <tbody>
    <?php      
        if( $_SESSION['rights'] == 1){
            $sql = "SELECT User.id,User.name, User.surname, User.phone_number, User.email, Rights.title FROM User, Rights WHERE User.rights = Rights.id AND User.id != 0 AND User.password IS NOT NULL ORDER BY User.id";
        } else if ( $_SESSION['rights'] == 2) {
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
                        )  AND User.password IS NOT NULL
                    ORDER BY User.id";
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
                echo "<td>".$row["title"]."</td>";
                echo "<td class='fas-td-arrow'><i class='fas fa-arrow-right' onclick=\"window.location.href='editor.php?type=User&id=".$row["id"]."'\"></i></i></td>";
                if($_SESSION['rights'] == 1)
                echo "<td class='fas-td'><i class='fas fa-minus' onclick='delRow(this)'></i></td>";
                echo "</tr>";
            }
        }
    ?>
    </tbody>
    </table>
    </div>
    <div class="faplusdiv"><i class="fas fa-plus" onclick="addRow('user')"></i></div>
    <h2>Employees</h2>
    <div class="thetables"> 
    <table id="employees_table" style="width:100%" class="table dt-responsive compact">
        <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Hotel</th>
            <th>Position</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
    <?php      
        if( $_SESSION['rights'] == 1){
            $sql = "SELECT User.id, User.email, Hotel.name as hname ,Rights.title FROM User, Rights,Hotel,Employment WHERE User.rights = Rights.id AND User.id != 0 AND Employment.iduser = User.id AND Employment.idhotel = Hotel.id AND User.id != 1 AND User.password is not NULL ORDER BY User.id";
        } else if ( $_SESSION['rights'] == 2) {
            $sql = "SELECT DISTINCT User.id, User.name, User.surname, User.phone_number, User.email,Hotel.name as hname, Rights.title
FROM User, Rights, Employment, Hotel
WHERE ( 
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
    ) AND Employment.idhotel = Hotel.id  AND User.password IS NOT NULL
ORDER BY User.id";
        }
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["id"]."</td>";
                echo "<td>".$row["email"]."</td>";
                echo "<td>".$row["hname"]."</td>";
                echo "<td>".$row["title"]."</td>";
                echo "<td class='fas-td-arrow'><i class='fas fa-arrow-right' onclick=\"window.location.href='editor.php?type=User&id=".$row["id"]."'\"></i></i></td>";
                echo "<td class='fas-td'><i class='fas fa-minus' onclick='delRow(this)'></i></td>";
                echo "</tr>";
            }
        }
    ?>
    </tbody>
    </table>
    </div>
    <div class="faplusdiv"><i class="fas fa-plus" onclick="addRow('user')"></i></div>

     <h2>Hotels</h2>
     <div class="thetables"> 
    <table id="hotel_table"  style="width:100%"  class="table dt-responsive compact">
        <thead>
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
        </thead>
        <tbody>
     <?php      
        if( $_SESSION['rights'] == 1){
            $sql = "SELECT *,Hotel.id as hid FROM Hotel WHERE Hotel.id != 0";
        } else if ( $_SESSION['rights'] == 2) {
            $sql = "SELECT *,Hotel.id as hid FROM Hotel, Employment WHERE Employment.iduser = $_SESSION[id] AND Hotel.id != 0 AND Hotel.id = Employment.idhotel";
        }
        $result = $connection->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["hid"]."</td>";
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
                echo "<td class='fas-td-arrow'><i class='fas fa-arrow-right' onclick=\"window.location.href='editor.php?type=Hotel&id=".$row["hid"]."'\"></i></i></td>";
                echo "<td class='fas-td'><i class='fas fa-minus' onclick='delRow(this)'></i></td>";
                echo "</tr>";
            }
        }
    ?>
    </tbody>
    </table>
    </div>
    <!-- <div class="faplusdiv"><i class="fas fa-plus" onclick="addRow('hotel')"></i></div> -->
    <div class="faplusdiv"><i class="fas fa-plus" onclick="window.location.href='editor.php'"></i></div>



    <div class="addRow" id="addRow_user">
        <form action='register.php' method='post'>
            <input type='text' name='name' placeholder='Name' id='hotel_name' required> 

            <input type='text' name='surname' placeholder='Surname' id='surname' required>

            <input type='text' name='phone_number' placeholder='Phone Number' id='phone_number' required>

            <input type='text' name='email' placeholder='Email' id='email' required>

            <input type='password' name='password' placeholder='Password' id='password' required>

            <?php
            echo "<select name='employed[]' class='editorInput' multiple>";
            if($_SESSION['rights'] == 1){
                $sql = "SELECT id,name FROM Hotel WHERE Hotel.id != 0";
            }
            else{
                $sql = "SELECT DISTINCT Hotel.id as id,Hotel.name as name
                    FROM User, Employment, Hotel
                    WHERE User.id = Employment.iduser AND User.id = $_SESSION[id] AND Hotel.id = Employment.idhotel";
            }
                
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row["id"]."'>".$row["name"]."</option>";
                    }
                }
            echo "</select>";
            
            if( $_SESSION['rights'] == 1){
                $sql = "SELECT id,title FROM Rights WHERE id != 0";
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
                $sql = "SELECT id,title FROM Rights WHERE id > 2";
                echo "<select id='rights' name='rights' required>";
                echo "<option value='' disabled selected>Rights</option>";
                $result = $connection->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row["id"]."'>".$row["title"]."</option>";
                    }
                }
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
    <script>$(document).ready( function () {
    $('#hotel_table').DataTable({
        responsive : true
    });
    $('#user_table').DataTable({
        responsive : true
    });
    $('#employees_table').DataTable({
        responsive : true
    });
} ); </script>

</html>