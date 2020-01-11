<?php
    require 'db_communication.php';
    session_start();

    $start = 0;
    $count = 10;
    $showComments = true;

    $conn = get_connection();

    if($_SERVER["REQUEST_METHOD"] === "POST") 
    {
        if(isset($_SESSION["username"])) 
        {
            if(isset($_POST["submitLike"])) 
            {
                upvote($conn, $_SESSION["username"], $_POST["post_id"]);
            }
            elseif (isset($_POST["submitComment"])) 
            {
                add_comment($conn, $_POST["post_id_comment"], $_SESSION["username"], $_POST["comment"]);
            }
        }

        if(isset($_POST["submitPosts"])) 
        {
            $start = $_POST["start"] + $_POST["count"];
            $count = $_POST["count"];

            header('Location: /?start='. $start .'');
        }
        else
        {
            header('Location: /');
        }
    }

    if (isset($_SESSION["compact"]))
    {
        if ($_SESSION["compact"] === 'on')
        {
            $showComments = false;
        }
    }

    if(isset($_GET["start"]))
    {
        $start = $_GET["start"];
    }

    $result = get_posts($conn, $start, $count);
    $pages = ceil(get_posts_total($conn) / $count);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Fake-a-Gram</title>
        <meta content="text/html; charset=UTF-8">

        <!-- Default CSS styling -->
        <link rel="stylesheet" href="/css/style.php">
        
        <!-- Loads CSS styling based on system preference -->
        <link rel="stylesheet" href="/css/darkstyle.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)">
        <link rel="stylesheet" href="/css/lightstyle.css" media="(prefers-color-scheme: light)">
        <link rel="stylesheet" href="/css/print.css" media="print">
        
        <!-- Custom Google Font -->
        <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

        <!-- Custom JS -->
        <script src="js/mainPage.js"></script>

    </head>
    <body>
        <nav>
            <a href="/">Homepage</a>
            <a href="/settings.php">Settings</a>
            <?php
                if(isset($_SESSION["username"])) 
                {
                    echo('<a href="/post.php">Add Post</a>');
                    
                    echo('<span class="right">');
                        echo('<a id="username">'. $_SESSION["username"] .'</a>');
                        echo('<a href="logout.php">Logout</a>');
                    echo('</span>');
                }  
                else 
                {
                    echo('<a href="/login.php">Login</a>');
                    echo('<a href="/register.php">Register</a>');
                }
            ?>
        </nav>
        <div class="main">
            <?php
                if(sizeof($result) > 0) 
                {
                    foreach($result as $row)
                    {
                        echo('<div class="post">');
                            echo('<div class="postMain">');
                                $img = '<img class="imgPost" src="'. $row["imgur_address"] .'" at="'. $row["title"] .'">';
                                echo($img);
                            echo('</div>');
                            echo('<div class="postSecondary">');
                                $title = '<h3>'. $row["title"] .' by '. get_username($conn, $row["user"]) .'</h3>';
                                echo($title);
                                echo('<div class="likeCounter">');
                                    $num_of_likes = '<p>Likes: <span id="likesOf'. $row["id"] .'">'. get_likes($conn, $row["id"]) .'</span></p>';
                                    echo($num_of_likes);
                                echo('</div>');

                                if (isset($_SESSION["username"]))
                                {
                                    $like = '<form method="POST" class="likeForm" action="/"><input type="text" name="post_id" value="'. $row["id"] .'" class="hidden"><input type="submit" name="submitLike" value="Like/Unlike"></form>';
                                    echo($like);
                                }

                                if ($showComments)
                                {
                                    echo('<div class="comments" id="commentsOf'. $row["id"] .'">');
                                    $comments = get_comments($conn, $row["id"]);
                            
                                    foreach($comments as $comm)
                                    {
                                        $text = '<p>From '. get_username($conn, $comm["user"]) .': '. $comm["comment"] .'<br>';
                                        echo($text);
                                    }

                                    echo('</div>');

                                    if (isset($_SESSION["username"]))
                                    {
                                        $comment = '<form method="POST" class="commentForm" action="/"><input type="text" name="post_id_comment" value="'. $row["id"] .'" class="hidden"><input type="text" name="comment" id="comment'. $row["id"] .'"><input type="submit" name="submitComment" value="Add Comment"></form>';
                                        echo($comment);
                                    }
                                }

                            echo('</div>');
                        echo('</div>');
                    }
                }
                else 
                {
                    $message = '<h1>No posts were loaded.</h4>';
                    echo($message);
                }
            ?>
        </div>
        <?php
            echo('<footer>');

                echo('<div class="pagination">');
                for($i = 0; $i < $pages; ++$i)
                {
                    $page = '<a href="/?start='. $i * $count .'"';

                    if ($start == $count * $i)
                    {
                        $page = $page.'class="active"';
                    }

                    $page = $page.'>'. ($i + 1) .'</a>';

                    echo($page);
                }

                echo('</div>');
            echo('</footer>');
        ?>
    </body>

    <script>
        initMainPage();
    </script>
</html>