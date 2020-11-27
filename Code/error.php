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

<body onload="returnBack()">
    <!-- <div class="header">
        <h1>Hilton Hotels</h1>
        <button onclick="window.location.href='logout.php';" type="button" class="actionButtons">Logout</button> 
    </div> -->
   

    <div class="content">
        <h1> You probably didnt want to enter that :(</h1>
        <h6> Redirecting to index page...</h6>
    <?php
    echo "<script>";
    echo "function returnBack(){";
    echo "setTimeout(function(){ window.location.replace(\"index.php\"); }, 1000);";
    echo "}</script>";
    ?>
    </div>
</body>