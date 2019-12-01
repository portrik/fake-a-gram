<?php
    require 'db_communication.php';
    session_start();

    $conn = get_connection();

    $result = get_posts($conn, 0, 10);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Fake-a-Gram</title>
        <meta content="text/html; charset=UTF-8">

        <!-- Default CSS styling -->
        <link rel="stylesheet" href="/css/style.css">

        <!-- Loads CSS styling based on system preference -->
        <link rel="stylesheet" href="/css/darkstyle.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)">
        <link rel="stylesheet" href="/css/lightstyle.css" media="(prefers-color-scheme: light)">
    </head>
    <body>
        <nav>
            <a href="/">Homepage</a>
            <a href="/login.php">Login</a>
            <a href="/register.php">Register</a>
            <?php
                if(isset($_SESSION["username"])) {
                    echo('<a href="/post.php">Add Post</a>');
                    echo('<a>'. $_SESSION["username"] .'</a>');
                }  
            ?>
        </nav>
        <div>
            <div>
                <img src="https://i.imgur.com/rSVeOIH.jpg" alt="memecko">
                <?php
                    while ($row = $result -> fetch_row()) {
                        echo("1: ". $row[0] ." 2: ". $row[1] ."");
                    }

                ?>
            </div>
        </div>
    </body>
</html>