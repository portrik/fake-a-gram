<?php 
    include('db_communication.php');

    /**
     * Only responds to valid POST requests sent by AJAX.
     */
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        session_start();
        $conn = get_connection();
        
        switch($_POST['type'])
        {
            /**
             * Adds or removes like and returns new like count
             */
            case 'like':
                upvote($conn, $_SESSION["username"], $_POST["post"]);
                echo(get_likes($conn, $_POST["post"]));
            break;
            /**
             * Adds comment
             */
            case 'comment':
                add_comment($conn, $_POST["post"], $_SESSION["username"], $_POST["text"]);
                echo('true');
            break;
            /**
             * Saves new settings to Session
             */
            case 'settings':
                $_SESSION["accentColor"] = $_POST["accentColor"];
                $_SESSION["compact"] = $_POST["compact"];
                $_SESSION["textColor"] = $_POST["textColor"];
            break;
            /**
             * Removes settings from Session
             */
            case 'reset':
                unset($_SESSION["accentColor"]);
                unset($_SESSION["compact"]);
                unset($_SESSION["textColor"]);
            break;
        }
    }
    else {
        header('Location: /');
    }

?>