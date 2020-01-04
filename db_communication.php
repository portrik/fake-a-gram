<?php
    function get_connection() {
        $host = 'localhost';
        $user = 'root';
        $db = 'fakeagram';
        $pass = '';
        $charset = 'utf8';

        $dsn = 'mysql:dbname='. $db . ';host='. $host .';charset='. $charset .'';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
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
        $sql = $conn -> query("SELECT username FROM users");
        $usernames = $sql -> fetchAll();

        if($username > 31 || in_array($username, $usernames)) {
            return "Username is not valid";
        }

        $sql = $conn -> query("SELECT email FROM users");
        $emails = $sql -> fetchAll();

        if($email > 255 || in_array($email, $emails)) {
            return "Email is not valid";
        }
        
        if($password != $passwordCheck) {
            return "Passwords do not match";
        }

        $pass = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = $conn -> prepare("INSERT INTO users (username, pass, email) VALUES (?, ?, ?)");
        $sql -> execute([$username, $pass, $email]);

        return "Success";
    }

    function login($conn, $username, $password) {
        $sql = $conn -> prepare("SELECT pass FROM users WHERE username=?");
        $sql -> execute([$username]);

        $pass_check = $sql -> fetch();

        if($pass_check) {
            if(password_verify($password, $pass_check["pass"])) {
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
        $sql -> execute([$username]);

        $result = $sql -> fetch();

        if($result > 0) {
            return $result["id"];
        }

        return -1;
    }   

    function get_posts($conn, $start, $count) {
        if($start < 0 || $count < 1) {
            return 0;
        }

        $all_rows = $conn -> query("SELECT id FROM posts") -> fetchAll();
        $number = $count;

        if($count > sizeof($all_rows)) {
            $number = sizeof($all_rows);
        }

        $sql = $conn -> prepare("SELECT * FROM posts ORDER BY id LIMIT ? , ?");
        $sql -> execute([$start, $number]);
        $result = $sql -> fetchAll();

        return $result;
    } 

    function get_username($conn, $user_id) {
        $sql = $conn -> prepare("SELECT username FROM users WHERE id=? LIMIT 1");
        $sql -> execute([$user_id]);

        $username = $sql -> fetch();
        return $username["username"];
    }

    function upvote($conn, $username, $post_id) {
        $user_id = get_user_id($conn, $username);

        $sql = $conn -> prepare("SELECT * FROM likes WHERE post=? AND user=?");
        $sql -> execute([$post_id, $user_id]);
        $num_rows = sizeof($sql -> fetchAll());

        if($user_id > 0 && $post_id > 0) {
            if($num_rows === 0)
            {
                $sql = $conn -> prepare("INSERT INTO likes (post, user) VALUES (?, ?)");
                $sql -> execute([$post_id, $user_id]);
            }
            else 
            {
                $sql = $conn -> prepare("DELETE FROM likes WHERE post=? AND user=?");
                $sql -> execute([$post_id, $user_id]);
            }
        }
    }

    function add_comment($conn, $post_id, $username, $comment) {
        $user_id = get_user_id($conn, $username);

        $sql = $conn -> prepare("INSERT INTO comments (comment, post, user) VALUES (?, ?, ?)");
        $sql -> execute([$comment, $post_id, $user_id]);
    }
?>