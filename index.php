<?php
    require 'db_communication.php';
    session_start();

    $conn = get_connection();

    $result = get_posts($conn, 0, 10);
    $what = "";

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $what = "POSTED";
        $what = add_comment($conn, $_POST["post_id_comment"], $_SESSION["username"], $_POST["comment"]);
        upvote($conn, $_SESSION["username"], $_POST["post_id"]);
    }
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
                <?php
                    echo($what);
                    if($result -> num_rows > 0) {
                        while ($row = $result -> fetch_row()) {
                            $img = '<img src="'. $row[0] .'" at="'. $row[1] .'"> '. $row[1] .' by '. get_username($conn, $row[2]) .'';
                            echo($img);
                            $like = '<form method="POST" action="#"><input type="text" name="post_id" value="'. $row[3] .'" disabled><input type="submit"></form>';
                            echo($like);
                            $comment = '<form method="POST" action="#"><input type="text" name="post_id_comment" value="'. $row[3] .'" disabled><input type="text" id="comment" name="comment"><input type="submit"></form>';
                            echo($comment);
                        }
                    }
                    else {
                        $message = '<h1>No posts are present in the database. Please upload some.</h4>';
                        echo($message);
                    }
                ?>
            </div>
        </div>
    </body>
</html>