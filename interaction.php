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
            case 'settings':
                $_SESSION["accentColor"] = $_POST["accentColor"];
                $_SESSION["compact"] = $_POST["compact"];
                $_SESSION["textColor"] = $_POST["textColor"];
            break;
            case 'reset':
                unset($_SESSION["accentColor"]);
                unset($_SESSION["compact"]);
                unset($_SESSION["textColor"]);
            break;
        }
    }
    else {
        header('Location: /~dvorap74/fake-a-gram/');
    }

?>