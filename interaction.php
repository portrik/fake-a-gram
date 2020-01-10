<?php 
    include('db_communication.php');

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        session_start();
        $conn = get_connection();
        
        switch($_POST['type'])
        {
            case 'like':
                upvote($conn, $_SESSION["username"], $_POST["post"]);
                echo(get_likes($conn, $_POST["post"]));
            break;
            case 'comment':
                add_comment($conn, $_POST["post"], $_SESSION["username"], $_POST["text"]);
                echo('true');
            break;
        }
    }
    else {
        header('Location: /');
    }

?>