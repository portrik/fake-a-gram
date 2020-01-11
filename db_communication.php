<?php
    function get_connection() 
    {
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

        try 
        {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        }
        catch (PDOException $e) 
        {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function add_user($conn, $username, $password, $passwordCheck, $email) 
    {
        $sql = $conn -> query('SELECT username FROM users');
        $usernames = $sql -> fetchAll();

        if($username > 31 || in_array($username, $usernames)) 
        {
            return 'Username is not valid';
        }

        if (!check_email($conn, $email))
        {
            return 'Email is not valid';
        }
        
        if($password != $passwordCheck) 
        {
            return 'Passwords do not match';
        }

        $pass = password_hash($password, PASSWORD_BCRYPT);
        
        $sql = $conn -> prepare('INSERT INTO users (username, pass, email) VALUES (?, ?, ?)');
        $sql -> execute([$username, $pass, $email]);

        return 'Success';
    }

    function check_email($conn, $email) 
    {
        $sql = $conn -> query('SELECT email FROM users');
        $emails = $sql -> fetchAll(PDO::FETCH_ASSOC);

        if($email > 255 || check_array($emails, $email, "email"))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    function login($conn, $username, $password) 
    {
        $sql = $conn -> prepare('SELECT pass FROM users WHERE username=?');
        $sql -> execute([$username]);

        $pass_check = $sql -> fetch();

        if($pass_check) 
        {
            if(password_verify($password, $pass_check['pass'])) 
            {
                return true;
            }
        }
        
        return false;
    }

    function add_post($conn, $title, $imgur_address, $username)
    {
        $user = get_user_id($conn, $username);

        if($title === '') 
        {
            return 'Invalid title';
        }

        if($imgur_address === '') 
        {
            return 'Invalid url';
        }

        if($user < 1) 
        {
            return 'Invalid user';
        }

        $sql = $conn -> prepare('INSERT INTO posts (imgur_address, title, user) VALUES (?, ?, ?)');
        $sql -> execute([$imgur_address, $title, $user]);

        return 'Success';
    }

    function get_user_id($conn, $username) 
    {
        $sql = $conn -> prepare('SELECT id FROM users WHERE username=?');
        $sql -> execute([$username]);

        $result = $sql -> fetch();

        if($result > 0) 
        {
            return $result['id'];
        }

        return -1;
    }   

    function get_posts($conn, $start, $count) 
    {
        if($start < 0 || $count < 1) 
        {
            return 0;
        }

        $all_rows = get_posts_total($conn);
        $number = $count;

        if($count > $all_rows) 
        {
            $number = $all_rows;
        }

        $sql = $conn -> prepare('SELECT * FROM posts ORDER BY id DESC LIMIT ? , ?');
        $sql -> execute([$start, $number]);
        $result = $sql -> fetchAll();

        return $result;
    } 

    function get_posts_total($conn)
    {
        $all_rows = $conn -> query('SELECT id FROM posts') -> fetchAll();
        $result = sizeof($all_rows);

        return $result;
    }

    function get_username($conn, $user_id) 
    {
        $sql = $conn -> prepare('SELECT username FROM users WHERE id=? LIMIT 1');
        $sql -> execute([$user_id]);

        $username = $sql -> fetch();
        return $username['username'];
    }

    function upvote($conn, $username, $post_id) 
    {
        $user_id = get_user_id($conn, $username);

        $sql = $conn -> prepare('SELECT * FROM likes WHERE post=? AND user=?');
        $sql -> execute([$post_id, $user_id]);
        $num_rows = sizeof($sql -> fetchAll());

        if($user_id > 0 && $post_id > 0) 
        {
            if($num_rows === 0)
            {
                $sql = $conn -> prepare('INSERT INTO likes (post, user) VALUES (?, ?)');
                $sql -> execute([$post_id, $user_id]);
            }
            else 
            {
                $sql = $conn -> prepare('DELETE FROM likes WHERE post=? AND user=?');
                $sql -> execute([$post_id, $user_id]);
            }
        }
    }

    function add_comment($conn, $post_id, $username, $comment) 
    {
        $user_id = get_user_id($conn, $username);

        if ($comment !== '' && $user_id > 0 && $post_id > 0) 
        {
            $sql = $conn -> prepare('INSERT INTO comments (comment, post, user) VALUES (?, ?, ?)');
            $sql -> execute([$comment, $post_id, $user_id]);
        }
    }

    function get_likes($conn, $post_id) 
    {
        if ($post_id > 0)
        {
            $sql = $conn -> prepare('SELECT * FROM likes WHERE post=?');
            $sql -> execute([$post_id]);

            $result = sizeof($sql -> fetchAll());

            return $result;
        }
    }

    function get_comments($conn, $post_id) 
    {
        if ($post_id > 0)
        {
            $sql = $conn -> prepare('SELECT * FROM comments WHERE post=?');
            $sql -> execute([$post_id]);

            $result = $sql -> fetchAll();

            return $result;
        }
    }

    function check_array($array, $value, $key) 
    {
        $result = false;

        foreach($array as $item) 
        {
            if($item[$key] === $value) 
            {
                $result = true;
            }
        }

        return $result;
    }

    function check_captcha($token)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => '6LfnQ84UAAAAAJvj2yj0RoUWNaoavnVQenTGiC1x',
            'response' => $token,
        );

        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $result = json_decode($result);

        return $result -> success;
    }
?>