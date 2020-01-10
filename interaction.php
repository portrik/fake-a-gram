<?php 
    include('db_communication.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $conn = get_connection();
        
        switch($_POST['type'])
        {
            case 'user':
                session_start();
                echo($_SESSION["username"]);
            break;
            case 'like':
                upvote($conn, $_POST["user"], $_POST["post"]);
                echo(get_likes($conn, $_POST["post"]));
            break;
            case 'comment':
                add_comment($conn, $_POST["post"], $_POST["user"], $_POST["text"]);
                echo('true');
            break;
        }
    }
    else {
        header('Location: /');
    }

?>