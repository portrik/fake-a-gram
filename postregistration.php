<!DOCTYPE html>
<html>
<?php
    require 'db_communication.php';

    $conn = get_connection();

    $result = add_user($conn, $_POST["username"], $_POST["password"], $_POST["passwordConfirm"], $_POST["email"]);

    $conn->close();
?>
<head>
    <title>Success! - Fake-a-gram</title>
    <meta content="text/html; charset=UTF-8">

    <!-- Default CSS styling -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Loads CSS styling based on system preference -->
    <link rel="stylesheet" href="/css/darkstyle.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)">
    <link rel="stylesheet" href="/css/lightstyle.css" media="(prefers-color-scheme: light)">
</head>
    <nav>
        <a href="/">Homepage</a>
    </nav>
    <div>
        <h1>Hello there, <?php echo $_POST["username"]; ?></h1>
        <h2><?php echo($result) ?></h2>
    </div>
</html>