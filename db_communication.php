<?php
    ini_set('display_errors', false);
    /**
     * get_connection
     * Creates PDO connection to preset database.
     *
     * @return PDO_Connection
     */
    function get_connection() 
    {
        $host = 'localhost';
        $user = 'dvorap74';
        $db = 'dvorap74';
        $pass = 'SuperTajneHeslo69';
        $charset = 'utf8';

        $dsn = 'mysql:dbname='. $db . ';host='. $host .';charset='. $charset .'';
        $options = [
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

    /**
     * add_user
     * Adds user to the databse if submitted data is valid
     * @param  PDO_Connection $conn
     * @param  String $username
     * @param  String $password
     * @param  String $passwordCheck
     * @param  String $email
     *
     * @return String - Either error message or Success
     */
    function add_user($conn, $username, $password, $passwordCheck, $email) 
    {
        $username = strtolower($username);
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

    /**
     * check_email
     * Checks if email is valid and if it is not already registered
     * @param  PDO_Connection $conn
     * @param  String $email
     *
     * @return Boolean - true on valid
     */
    function check_email($conn, $email) 
    {
        $sql = $conn -> query('SELECT email FROM users');
        $emails = $sql -> fetchAll(PDO::FETCH_ASSOC);
        $format = '/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i';

        if(!preg_match($format, $email) || $email > 255 || check_array($emails, $email, "email"))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * login
     * Checks values and logs user in on success
     * @param  PDO_Connection $conn
     * @param  String $username
     * @param  String $password
     *
     * @return Boolean - true on successfull login
     */
    function login($conn, $username, $password) 
    {
        $username = strtolower($username);
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

    /**
     * add_post
     * Checks submitted data and adds them to databse if valid.
     * @param  PDO_Connection $conn
     * @param  String $title
     * @param  String-URL $imgur_address
     * @param  String $username
     *
     * @return String - Error message or Success 
     */
    function add_post($conn, $title, $imgur_address, $username)
    {
        $user = get_user_id($conn, $username);
        $hostname = parse_url($imgur_address, PHP_URL_HOST);
        $path = substr(parse_url($imgur_address, PHP_URL_PATH), -3);

        $formats = array('jpg', 'png');

        if($title === '') 
        {
            return 'Invalid title';
        }

        if($imgur_address === '' || $hostname !== 'i.imgur.com' || !in_array($path, $formats)) 
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

    /**
     * get_user_id
     * Retrieves ID assigned to submitted username
     * @param  PDO_Connection $conn
     * @param  String $username
     *
     * @return Int - Non-existent user returned as -1
     */
    function get_user_id($conn, $username) 
    {
        $sql = $conn -> prepare('SELECT id FROM users WHERE username=?');
        $sql -> execute([strtolower($username)]);

        $result = $sql -> fetch();

        if($result > 0) 
        {
            return $result['id'];
        }

        return -1;
    }   

    /**
     * get_posts
     * Retrieves post from the database. Posts are ordered in descending order by ID.
     * Start is counted from post with highest ID.
     * @param  PDO_Connection $conn
     * @param  Int $start
     * @param  Int $count
     *
     * @return PDO_SQL_Result - All values from the database are returned
     */
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

    /**
     * get_posts_total
     * Retrieves total number of posts in the database
     * @param  PDO_Connection $conn
     *
     * @return Int
     */
    function get_posts_total($conn)
    {
        $all_rows = $conn -> query('SELECT id FROM posts') -> fetchAll();
        $result = sizeof($all_rows);

        return $result;
    }

    /**
     * get_username
     * Retrieves username assigned to submitted ID.
     * @param  PDO_Connection $conn
     * @param  Int $user_id
     *
     * @return String
     */
    function get_username($conn, $user_id) 
    {
        $sql = $conn -> prepare('SELECT username FROM users WHERE id=? LIMIT 1');
        $sql -> execute([$user_id]);

        $username = $sql -> fetch();
        return $username['username'];
    }

    /**
     * upvote
     * Adds or removes like from a post by a user
     * @param  PDO_Connection $conn
     * @param  String $username
     * @param  Int $post_id
     */
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

    /**
     * add_comment
     * Adds comment to a post if submitted data are valid
     * @param  PDO_Connection $conn
     * @param  Int $post_id
     * @param  String $username
     * @param  String $comment
     */
    function add_comment($conn, $post_id, $username, $comment) 
    {
        $user_id = get_user_id($conn, $username);

        if ($comment !== '' && $user_id > 0 && $post_id > 0) 
        {
            $sql = $conn -> prepare('INSERT INTO comments (comment, post, user) VALUES (?, ?, ?)');
            $sql -> execute([$comment, $post_id, $user_id]);
        }
    }

    /**
     * get_likes
     * Retrieves number of likes on a post
     * @param  PDO_Connection $conn
     * @param  String $post_id
     *
     * @return Int
     */
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

    /**
     * get_comments
     * Retrieves comments on a post
     * @param  PDO_Connection $conn
     * @param  Int $post_id
     *
     * @return Array_of_Strings
     */
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

    /**
     * check_array
     * Checks if a value is present in array. Only used to check emails.
     * @param  Array $array
     * @param  mixed $value - searched value
     * @param  mixed $key - key, under which searched value could be hidden
     *
     * @return Boolean - whether value is present in array
     */
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

    /**
     * check_recaptcha
     * Checks reCaptcha with reCaptcha servers if user interaction was valid.
     * @param  String $token - token generated by JavaScript code
     *
     * @return String - 'true' or 'false'
     */
    function check_recaptcha($token)
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