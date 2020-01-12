<?php
    include("db_communication.php");
    session_start();
    
    /**
     * Non-logged in users are redirected to index
     */
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

    <!-- Custom JS -->
    <script src="/js/userValidation.js"></script>
</head>

<body>
    <nav>
        <a href="/">Homepage</a>
        <a href="/settings.php">Settings</a>
        <?php
            if (isset($_SESSION["username"]))
            {
                echo('<span class="right">');
                    echo('<a id="username">'. $_SESSION["username"] .'</a>');
                    echo('<a href="/logout.php">Logout</a>');
                echo('</span>');
            }
        ?>
    </nav>
    <div class="main">
        <div class="post">
            <form action="#" method="POST" id="postForm">
                <label for="username">Title
                    <input type="text" name="title" id="title" value="<?php echo isset($_POST["title"]) ? $_POST["title"] : "" ?>" required>
                </label>
                <label for="imgur_address">Image Address (imgur)
                    <input type="url" name="imgur_address" id="imgur_address" value="<?php echo isset($_POST["imgur_address"]) ? $_POST["imgur_address"] : "" ?>" required>
                </label>
                <input type="submit" name="submit" id="submit">
            </form>
        </div>

        <div class="post">
            <h3>Please note:</h3>
            <ul>
                <li><b>All values are required</b></li>
                <li>Title cannot be empty and has to be shorter than 255 characters.</li>
                <li>Only direct imgur links are accepted.</li>
                <li>If your link does not end in '.jpg' or '.png', right-click on the image and choose copy image adrress.</li>
            </ul>
        </div>
    </div>

    <script>
        initPost();
    </script>
</body>

</html>