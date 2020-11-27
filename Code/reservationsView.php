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
    checkRights(4);
    printHTMLheader("Confirm your booking");
    printHeader();

$User = new UserService();

$reservations = $User->GetReservations( $_SESSION[ 'id' ] );

print
("

<a href=" . $_SERVER[ 'HTTP_REFERER' ] . "><i class=\"fas fa-arrow-left fa-2x karrow\" ></i></a>

");

print
("

<div class=\"m-main\">

<div class=\"row ktopmargin\">
    <div class=\"col\">
        <h2>My reservations</h2>
    </div>
</div>
<div class=\"thetables\"> 
<table class=\"table\" id=\"ktable\">
<thead>
    <tr>
        <th>#</th>
        <th>Hotel</th>
        <th>Arrival</th>
        <th>Departure</th>
        <th>Reservation State</th>
        <th>Price</th>
    </tr>
</thead>
<tbody>");
    $i = 1;
    if( $reservations->num_rows > 0 ){
      while ( $row = $reservations->fetch_assoc() ){
          
            $button = "";
            if( $row[ 'state' ] == "Awaiting payment" ){
                $button =  "<form action=\"bookAction.php\" method=\"post\" class=\"formapayment\">
                                <input type=\"hidden\" name=\"payment\" value=\"\">
                                <input type=\"hidden\" name=\"reservation_id\" value= " . $row[ 'id' ] . ">
                                <button class=\"check checkbtn paymentbtn\" type=\"submit\">Pay</button>
                            </form>";
            } else {
                $button = "";
            }
        
            print
          ("
              <tr>
                  <th scope=row>" . $i . "</th>
                  <td>" . $row[ 'name' ] . "</td>
                  <td>" . $row[ 'arrival' ] . "</td>
                  <td>" . $row[ 'departure' ] . "</td>
                  <td>" . $row[ 'state' ] . $button . "</td>
                  <td>" . $row[ 'pricePerBed' ] . "</td>
              </tr>
          ");
          $i++;
      }
    }
    print
    ("
</tbody>
</table>
</div>
</div>
");

?>

<script>$(document).ready( function () {
        $('#ktable').DataTable();
} );
</script>