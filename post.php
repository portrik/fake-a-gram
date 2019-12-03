<?php
    include("db_communication.php");
    session_start();
    $response = "";

    if(!isset($_SESSION["username"])) {
        header("Location: /");
    }
    else {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            $conn = get_connection();
            $response = add_post($conn, $_POST["title"], $_POST["imgur_address"], $_SESSION["username"]);
            
            if($response == "Success") {
                header("Location: /");
            }
        }
    }

    $conn -> close();
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <title>Add Post - Fake-a-Gram</title>
    <meta content="text/html; charset=UTF-8">

    <!-- Default CSS styling -->
    <link rel="stylesheet" href="/css/style.css">

    <!-- Loads CSS styling based on system preference -->
    <link rel="stylesheet" href="/css/darkstyle.css"
        media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)">
    <link rel="stylesheet" href="/css/lightstyle.css" media="(prefers-color-scheme: light)">
</head>

<body>
    <nav>
        <a href="/">Homepage</a>
    </nav>
    <div>
        <div>
            <form action="#" method="POST">
                <label for="username">Title
                    <input type="text" name="title" id="title" value="<?php echo isset($_POST["title"]) ? $_POST["title"] : "" ?>" required>
                </label>
                <label for="imgur_address">Image Address
                    <input type="url" name="imgur_address" id="imgur_address" value="<?php echo isset($_POST["imgur_address"]) ? $_POST["imgur_address"] : "" ?>" required>
                </label>
                <input type="submit" name="submit" id="submit">
            </form>
        </div>
    </div>
</body>

</html>