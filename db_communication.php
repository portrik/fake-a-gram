<?php
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_DATABASE', 'fakeagram');    

    function get_connection() {
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            return false;
        }
        else {
            return $conn;
        }
    }

    function add_user($conn, $username, $password, $passwordCheck, $email) {
        $sql = "SELECT username FROM users";
        $result = $conn->query($sql); 
        $usernames = $result->fetch_array();

        if($username > 31 || in_array($username, $usernames)) {
            return "Username is not valid";
        }

        $sql = "SELECT email FROM users";
        $emails = $conn->query($sql)->fetch_array();

        if($email > 255 || in_array($email, $emails)) {
            return "Email is not valid";
        }
        
        if($password != $passwordCheck) {
            return "Passwords do not match";
        }

        $pass = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (username, pass, email) VALUES ('". $username ."', '". $pass ."', '". $email ."')";

        $conn->query($sql);

        return "Success";
    }

    function login($conn, $username, $password) {
        $sql = "SELECT pass FROM users WHERE username='". $username ."'";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            if(password_verify($password, $result->fetch_object()->pass)) {
                return true;
            }
        }
        
        return false;
    }

    function add_post($conn, $title, $imgur_address, $username) {
        $user = get_user_id($conn, $username);

        if($user >= 0) {
            $sql = "INSERT INTO posts (imgur_address, title, user) VALUES ('". $imgur_address ."', '". $title ."', '". $user ."')";

            $conn -> query($sql);

            return "Success";
        }
        else {
            return "Invalid user";
        }
    }

    function get_user_id($conn, $username) {
        $sql = "SELECT id FROM users WHERE username='". $username ."'";
        $result = $conn->query($sql);

        return $result->fetch_object()->id;
    }   

    function get_posts($conn, $start, $count) {
        $all_rows = $conn -> query("SELECT id FROM posts");
        $number = $count;

        if($count > ($all_rows -> num_rows)) {
            $number = $all_rows -> num_rows;
        }

        $sql = "SELECT imgur_address, title, user, id FROM posts ORDER BY id LIMIT ". $start .", ". $count ."";
        $result = $conn -> query($sql);

        return $result;
    } 

    function get_username($conn, $user_id) {
        $sql = "SELECT username FROM users WHERE id=". $user_id ." LIMIT 1";
        $result = $conn -> query($sql);
        return $result -> fetch_object() -> username;
    }

    function upvote($conn, $username, $post_id) {
        $sql = 'INSERT INTO likes (post, user) VALUES ('. $post_id .', '. get_user_id($conn, $username) .')';

        $conn -> query($sql);
    }

    function add_comment($conn, $post_id, $username, $comment) {
        $user = get_user_id($conn, $username);
        $sql = 'INSERT INTO comments (comment, post, user) VALUES ("'. $comment .'", '. $post_id .', '. $user .')';

        $conn -> query($sql);
    }
?>