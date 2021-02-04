<?php
include_once("pages/classes.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header class="row"></header>
    <nav class="row">
        <div class="col-12">
            <?php
            include_once("pages/menu.php");
            ?>
        </div>
        <section class="row">
            <div class="col-12 mx-5">
                <?php
                if (isset($_GET["page"])) {
                    $page = $_GET["page"];
                    if ($page == 1) include_once("pages/catalog.php");
                    if ($page == 2) include_once("pages/cart.php");
                    if ($page == 3) include_once("pages/registration.php");
                    if ($page == 4) include_once("pages/admin.php");
                    if ($page == 6) include_once("pages/private.php");
                } else include_once("pages/catalog.php");
                ?>
            </div>
            <footer class="row fixed-bottom">
                <div class="col-12 mx-4">
                VHordiienko &copy;
                </div>
            </footer>
        </section>
    </nav>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>