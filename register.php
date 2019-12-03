<?php
    include("db_communication.php");
    $response = "";

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        $conn = get_connection();

        $response = add_user($conn, $_POST["username"], $_POST["password"], $_POST["passwordConfirm"], $_POST["email"]);

        $conn -> close();
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
    <link href="https://fonts.googleapis.com/css?family=Oswald&display=swap" rel="stylesheet">
</head>

<body>
    <nav>
        <a href="/">Homepage</a>
        <a href="/login.php">Login</a>
    </nav>
    <div>
        <div>
            <form action="#" method="POST">
                <label for="username">Username
                    <input type="text" name="username" id="username" value="<?php echo isset($_POST["username"]) ? $_POST["username"] : '' ?>" required>
                </label>
                <label for="email">Email
                    <input type="email" name="email" id="email" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : '' ?>" required>
                </label>
                <label for="password">Password
                    <input type="password" name="password" id="password" required>
                </label>
                <label for="passwordConfirm">Confirm Password
                    <input type="password" name="passwordConfirm" id="passwordConfirm" required>
                </label>
                <input type="submit" name="submit" id="submit">
            </form>
        </div>
    </div>
</body>

</html>