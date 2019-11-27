<?php
    function get_connection() {
        $conn = new mysqli("localhost", "root", "", "fakeagram");

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
?>