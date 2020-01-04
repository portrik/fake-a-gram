<?php
    function get_connection() {
        $host = 'localhost';
        $user = 'root';
        $db = 'fakeagram';
        $pass = '';
        $charset = 'utf8';

        $dsn = 'mysql:dbname='. $db . ';host='. $host .';charset='. $charset .'';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        }
        catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function add_user($conn, $username, $password, $passwordCheck, $email) {
        $sql = "SELECT username FROM users";
        $usernames = $conn -> query($sql) -> fetch_array();

        if($username > 31 || in_array($username, $usernames)) {
            return "Username is not valid";
        }

        $sql = "SELECT email FROM users";
        $emails = $conn -> query($sql) -> fetch_array();

        if($email > 255 || in_array($email, $emails)) {
            return "Email is not valid";
        }
        
        if($password != $passwordCheck) {
            return "Passwords do not match";
        }

        $pass = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = $conn -> prepare("INSERT INTO users (username, pass, email) VALUES (?, ?, ?)");
        $sql -> execute([$user, $pass, $email]);

        return "Success";
    }

    function login($conn, $username, $password) {
        $sql = $conn -> prepare("SELECT pass FROM users WHERE username=?");
        $result = $sql -> execute([$username]);

        if($result -> num_rows > 0) {
            if(password_verify($password, $result -> fetch_object() -> pass)) {
                return true;
            }
        }
        
        return false;
    }

    function add_post($conn, $title, $imgur_address, $username) {
        $user = get_user_id($conn, $username);

        if($title === "") {
            return "Invalid title";
        }

        if($imgur_address === "") {
            return "Invalid url";
        }

        if($user < 1) {
            return "Invalid user";
        }

        $sql = $conn -> prepare("INSERT INTO posts (imgur_address, title, user) VALUES (?, ?, ?)");
        $sql -> execute([$imgur_address, $title, $user]);

        return "Success";
    }

    function get_user_id($conn, $username) {
        $sql = $conn -> prepare("SELECT id FROM users WHERE username=?");
        $result = $sql -> exceute([$username]) -> fetch();

        if($result > 0) {
            return $result;
        }

        return -1;
    }   

    function get_posts($conn, $start, $count) {
        if($start < 0 || $count < 1) {
            return 0;
        }

        $all_rows = $conn -> query("SELECT id FROM posts");
        $number = $count;

        if($count > ($all_rows -> num_rows)) {
            $number = $all_rows -> num_rows;
        }

        $sql = $conn -> prepare("SELECT imgur_address, title, user, id FROM posts ORDER BY id LIMIT ?, ?");
        $result = $sql -> execute([$start, $count]);

        return $result;
    } 

    function get_username($conn, $user_id) {
        $sql = $conn -> prepare("SELECT username FROM users WHERE id=? LIMIT 1");
        $username = $sql -> execute([$user_id]) -> fetch_object() -> username;
        return $username;
    }

    function upvote($conn, $username, $post_id) {
        $user_id = get_user_id($conn, $username);

        $sql = $conn -> prepare("SELECT * FROM likes WHERE post=? AND user=?");
        $num_rows = $sql -> execute([$post_id, $user_id]) -> num_rows;

        if($num_rows > 1 && $username !== "" && $post_id > 0) {
            $sql = $conn -> prepare("INSERT INTO likes (post, user) VALUES (?, ?)");
            $sql -> execute([$post_id, $user_id]);
        }
    }

    function add_comment($conn, $post_id, $username, $comment) {
        $user_id = get_user_id($conn, $username);

        $sql = $conn -> prepare("INSERT INTO comments (comment, post, user) VALUES (?, ?, ?)");
        $sql -> execute([$comment, $post_id, $user_id]);
    }
?>