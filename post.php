<?php
    include("db_communication.php");
    session_start();
    if(!isset($_SESSION["username"])) 
    {
        header("Location: /");
    }
    else 
    {
        if($_SERVER["REQUEST_METHOD"] === "POST") 
        {
            $conn = get_connection();
            $response = add_post($conn, $_POST["title"], $_POST["imgur_address"], $_SESSION["username"]);
            
            if($response == "Success") 
            {
                header("Location: /");
            }
        }
    }
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <title>Add Post - Fake-a-Gram</title>
    <meta content="text/html; charset=UTF-8">

    <!-- Default CSS styling -->
    <link rel="stylesheet" href="/css/style.php">

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
        <a href="/settings.php">Settings</a>
        <?php
            if (isset($_SESSION["username"]))
            {
                echo('<a id="username">'. $_SESSION["username"] .'</a>');
                echo('<a href="logout.php">Logout</a>');
            }
        ?>
    </nav>
    <div>
        <div>
            <form action="#" method="POST" id="postForm">
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

    <script>
        initPost();
    </script>
</body>

</html>