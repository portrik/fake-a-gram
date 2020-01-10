<?php
    include('db_communication.php');
    $response = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $conn = get_connection();

        $response = add_user($conn, strtolower($_POST["username"]), $_POST["password"], $_POST["passwordCheck"], strtolower($_POST["email"]));

        if ($response == "Success")
        {
            header("Location: /login.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register - Fake-a-Gram</title>
    <meta content="text/html; charset=UTF-8">

    <!-- Default CSS styling -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Loads CSS styling based on system preference -->
    <link rel="stylesheet" href="/css/darkstyle.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)">
    <link rel="stylesheet" href="/css/lightstyle.css" media="(prefers-color-scheme: light)">

    <!-- Custom Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

    <script src="js/userValidation.js"></script>
</head>

<body>
    <nav>
        <a href="/">Homepage</a>
        <a href="/login.php">Login</a>
    </nav>
    <div>
        <div class="mainWrapper">
            <form action="#" method="POST" id="registerForm">
                <label for="username">Username
                    <input type="text" name="username" id="username" value="<?php echo isset($_POST["username"]) ? $_POST["username"] : '' ?>" required>
                </label>
                <label for="email">Email
                    <input type="email" name="email" id="email" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : '' ?>" required>
                </label>
                <label for="password">Password
                    <input type="password" name="password" id="password" required>
                </label>
                <label for="passwordCheck">Confirm Password
                    <input type="password" name="passwordCheck" id="passwordCheck" required>
                </label>
                <input type="submit" name="submit" id="submit" disabled>
            </form>
        </div>
    </div>

    <script>
        initRegister();
    </script>
</body>

</html>