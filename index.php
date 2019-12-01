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
            <?php
                if(isset($_SESSION["username"])) {
                    echo('<a href="/post.php">Add Post</a>');
                    echo('<a>'. $_SESSION["username"] .'</a>');
                    echo('<a href="logout.php">Logout</a>');
                }  
                else {
                    echo('<a href="/login.php">Login</a>');
                    echo('<a href="/register.php">Register</a>');
                }
            ?>
        </nav>
        <div>
            <div>
                <img src="https://i.imgur.com/rSVeOIH.jpg" alt="memecko">
                <?php
                    while ($row = $result -> fetch_row()) {
                        $img = "<img src=\"". $row[0] ."\" at=\"". $row[1] ."\"> ". $row[1] ." by ". get_username($conn, $row[2]) ."";

                        echo($img);
                    }

                ?>
            </div>
        </div>
    </body>
</html>