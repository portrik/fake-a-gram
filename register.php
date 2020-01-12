<?php
    include('db_communication.php');
    $response = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $conn = get_connection();

        if (check_recaptcha($_POST['g-recaptcha-response']))
        {
            $response = add_user($conn, strtolower($_POST["username"]), $_POST["password"], $_POST["passwordCheck"], strtolower($_POST["email"]));
            
            if ($response == "Success")
            {
                header("Location: /login.php");
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register - Fake-a-Gram</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" type="image/png" href="/favicon.png"/>

    <!-- Default CSS styling -->
    <link rel="stylesheet" href="/css/style.php">
    
    <!-- Loads CSS styling based on system preference -->
    <link rel="stylesheet" href="/css/darkstyle.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)">
    <link rel="stylesheet" href="/css/lightstyle.css" media="(prefers-color-scheme: light)">
    <link rel="stylesheet" href="/css/print.css" media="print">
    
    <!-- Custom Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

    <!-- Custom JavaScript -->
    <script src="/js/userValidation.js"></script>

    <!-- reCaptcha code -->
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaLoad&render=explicit" async defer></script>
</head>

<body>
    <nav>
        <a href="/">Homepage</a>
        <a href="/login.php">Login</a>
    </nav>
    <div class="main">
        <div class="post">
            <form action="#" method="POST" id="registerForm">
                <label for="username">Username
                    <input type="text" name="username" id="username" tabindex="1" autocomplete="off" value="<?php echo isset($_POST["username"]) ? $_POST["username"] : '' ?>" required>
                </label>
                <label for="email">Email
                    <input type="email" name="email" id="email" tabindex="2" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : '' ?>" required>
                </label>
                <label for="password">Password
                    <input type="password" name="password" tabindex="3" id="password" autocomplete="new-password" required>
                </label>
                <label for="passwordCheck">Confirm Password
                    <input type="password" name="passwordCheck" tabindex="4" id="passwordCheck" autocomplete="new-password" required>
                </label>
                <div id="recaptcha"></div>
                <input type="submit" name="submit" id="submit" tabindex="5">
            </form>
        </div>

        <div class="post">
            <h3>Please note:</h3>
            <ul>
                <li><b>All values are required</b></li>
                <li>Username cannot be empty and has to be shorter than 255 characters.</li>
                <li>Only valid emails adresses are accepted, unless already registered..</li>
                <li>Password has to be at least 8 characters long.</li>
            </ul>
        </div>
    </div>

    <script>
        initRegister();
    </script>
</body>

</html>