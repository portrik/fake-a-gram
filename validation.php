<?php
    include('db_communication.php');

    if($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $conn = get_connection();
        $value = $_POST['value'];

        switch($_POST['type'])
        {
            case 'username':
                echo(check_username($conn, $value));
            break;
            case 'email':
                if (check_email($conn, $value)) 
                {
                    echo('true');
                }
                else
                {
                    echo('false');
                }
            break;
            case 'login':
                echo(check_login($conn, $value, $_POST['secondValue'], $_POST['recaptcha']));
            break;
        }
    }
    else 
    {
        header('Location: /~dvorap74/fake-a-gram/');
    }    

    /**
     * check_username
     * Checks, if username exists and returns string 
     * @param  PDO_Connection $conn
     * @param  String $username
     *
     * @return String
     */
    function check_username($conn, $username)
    {
        if (get_user_id($conn, $username) == -1)
        {
            return 'true';
        }
        else
        {
            return 'false';
        }
    }

    /**
     * check_login
     * Checks login information with reCaptcha
     * @param  PDO_Connection $conn
     * @param  String $username
     * @param  String $pass
     *
     * @return String
     */
    function check_login($conn, $username, $pass, $recaptcha) 
    {
        if (check_recaptcha($recaptcha))
        {
            if (login($conn, $username, $pass))
            {
                session_start();
                $_SESSION["username"] = $username;

                return 'true';
            }
            else
            {
                return 'false';
            }
        }
        else 
        {
            return 'false';
        }
    }
?>