<?php
    require 'db_communication.php';
    session_start();

    $start = 0;
    $count = 10;

    $conn = get_connection();

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(isset($_SESSION["username"])) {
            if(isset($_POST["submitLike"])) {
                upvote($conn, $_SESSION["username"], $_POST["post_id"]);
            }
            elseif (isset($_POST["submitComment"])) {
                add_comment($conn, $_POST["post_id_comment"], $_SESSION["username"], $_POST["comment"]);
            }
        }

        if(isset($_POST["submitPosts"])) {
            $start = $_POST["start"] + $_POST["count"];
            $count = $_POST["count"];
        }
    }

    $result = get_posts($conn, $start, $count);
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
        
        <!-- Custom Google Font -->
        <link href="https://fonts.googleapis.com/css?family=Oswald&display=swap" rel="stylesheet">
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
                    if(sizeof($result) > 0) {
                        foreach($result as $row)
                        {
                            $img = '<img src="'. $row["imgur_address"] .'" at="'. $row["title"] .'"> '. $row["title"] .' by '. get_username($conn, $row["user"]) .'';
                            echo($img);
                            $like = '<form method="POST" action="#"><input type="text" name="post_id" value="'. $row["id"] .'" class="hidden"><input type="submit" name="submitLike" value="Like"></form>';
                            echo($like);
                            $comment = '<form method="POST" action="#"><input type="text" name="post_id_comment" value="'. $row["id"] .'" class="hidden"><input type="text" name="comment"><input type="submit" name="submitComment" value="Comment"></form>';
                            echo($comment);
                        }
                        
                        if (sizeof($result) === $count) {
                            $more_posts = '<form method="POST" action="#"><input type="text" name="start" value="'. $start .'" class="hidden"><input type="text" name="count" value="'. $count .'" class="hidden"><input type="submit" name="submitPosts" value="Load More Posts"></form>';
                            echo($more_posts);
                        }
                    }
                    else {
                        $message = '<h1>No more posts were loaded.</h4>';
                        echo($message);
                    }
                ?>
            </div>
        </div>
    </body>
</html>