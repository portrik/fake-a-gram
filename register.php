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
                header("Location: /~dvorap74/fake-a-gram/login.php");
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register - Fake-a-Gram</title>
    <meta content="text/html; charset=UTF-8">

    <!-- Default CSS styling -->
    <link rel="stylesheet" href="/~dvorap74/fake-a-gram/css/style.php">

    <!-- Loads CSS styling based on system preference -->
    <link rel="stylesheet" href="/~dvorap74/fake-a-gram/css/darkstyle.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)">
    <link rel="stylesheet" href="/~dvorap74/fake-a-gram/css/lightstyle.css" media="(prefers-color-scheme: light)">

    <!-- Custom Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

    <!-- Custom JavaScript -->
    <script src="/~dvorap74/fake-a-gram/js/userValidation.js"></script>

    <!-- reCaptcha code -->
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaLoad&render=explicit" async defer></script>
</head>

<body>
    <nav>
        <a href="/~dvorap74/fake-a-gram/">Homepage</a>
        <a href="/~dvorap74/fake-a-gram/login.php">Login</a>
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
    </div>

    <script>
        initRegister();
    </script>
</body>

</html>