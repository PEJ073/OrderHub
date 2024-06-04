<?php
session_start();

include("api/functions.php");
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width-device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>OrderHub | Carrello</title>
    <link rel="stylesheet" href="./styleSrc.css">
    <link rel="shortcut icon" href="../media/icons/favicon.ico" type="image/x-icon" />
</head>

<body onload="load()">
    <div id="navBarDiv">
        <?php
        printNavBar();
        ?>
    </div>
    <div id="contentDiv">
    </div>

</body>

</html>