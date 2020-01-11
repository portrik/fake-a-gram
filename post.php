<?php
    include("db_communication.php");
    session_start();
    if(!isset($_SESSION["username"])) 
    {
        header("Location: /~dvorap74/fake-a-gram/");
    }
    else 
    {
        if($_SERVER["REQUEST_METHOD"] === "POST") 
        {
            $conn = get_connection();
            $response = add_post($conn, $_POST["title"], $_POST["imgur_address"], $_SESSION["username"]);
            
            if($response == "Success") 
            {
                header("Location: /~dvorap74/fake-a-gram/");
            }
        }
    }
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <title>Add Post - Fake-a-Gram</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" type="image/png" href="/~dvorap74/fake-a-gram/favicon.png"/>

    <!-- Default CSS styling -->
    <link rel="stylesheet" href="/~dvorap74/fake-a-gram/css/style.php">
    
    <!-- Loads CSS styling based on system preference -->
    <link rel="stylesheet" href="/~dvorap74/fake-a-gram/css/darkstyle.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)">
    <link rel="stylesheet" href="/~dvorap74/fake-a-gram/css/lightstyle.css" media="(prefers-color-scheme: light)">
    <link rel="stylesheet" href="/~dvorap74/fake-a-gram/css/print.css" media="print">
    
    <!-- Custom Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

    <!-- Custom JS -->
    <script src="/~dvorap74/fake-a-gram/js/userValidation.js"></script>
</head>

<body>
    <nav>
        <a href="/~dvorap74/fake-a-gram/">Homepage</a>
        <a href="/~dvorap74/fake-a-gram/settings.php">Settings</a>
        <?php
            if (isset($_SESSION["username"]))
            {
                echo('<span class="right">');
                    echo('<a id="username">'. $_SESSION["username"] .'</a>');
                    echo('<a href="/~dvorap74/fake-a-gram/logout.php">Logout</a>');
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