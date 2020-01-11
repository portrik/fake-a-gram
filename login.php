<?php
    include("db_communication.php");
    session_start();

    if(isset($_SESSION["username"]))
    {
        header('Location: /');
    }

    $conn = get_connection();

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(login($conn, $_POST["username"], $_POST["password"])) 
        {
            $_SESSION["username"] = $_POST["username"];
            header("Location: /");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - Fake-a-Gram</title>
    <meta content="text/html; charset=UTF-8">

    <!-- Default CSS styling -->
    <link rel="stylesheet" href="/css/style.php">

    <!-- Loads CSS styling based on system preference -->
    <link rel="stylesheet" href="/css/darkstyle.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)">
    <link rel="stylesheet" href="/css/lightstyle.css" media="(prefers-color-scheme: light)">

    <!-- Custom JavaScript -->
    <script src="js/userValidation.js"></script>

    <!-- reCaptcha code -->
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaLoad&render=explicit" async defer></script>
</head>

<body>
    <nav>
        <a href="/">Homepage</a>
        <a href="/register.php">Register</a>
    </nav>
    <div class="mainWrapper">
        <form action="#" method="POST" id="loginForm">
            <label for="username">Username
                <input type="text" name="username" id="username" autocomplete="username" value="<?php echo isset($_POST["username"]) ? $_POST["username"] : "" ?>" required>
            </label>
            <label for="password">Password
                <input type="password" name="password" id="password" autocomplete="current-password" required>
            </label>
            <div id="recaptcha"></div>
            <label>
                <button type="submit" name="submit" id="submit">Login</button>
            </label>
        </form>
    </div>

    <script>
        initLogin();
    </script>
</body>

</html>