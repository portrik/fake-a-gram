<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] === 'POST')
    {
        if (isset($_POST["submit"]))
        {
            $_SESSION["accentColor"] = $_POST["accentColor"];
            $_SESSION["compact"] = $_POST["compact"];
            $_SESSION["textColor"] = $_POST["textColor"];
        }
        else if (isset($_POST["reset"]))
        {
            unset($_SESSION["accentColor"]);
            unset($_SESSION["compact"]);
            unset($_SESSION["textColor"]);
        }

        header('Location: /');
    }
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <title>Settings - Fake-a-Gram</title>
    <meta content="text/html; charset=UTF-8">

    <!-- Default CSS styling -->
    <link rel="stylesheet" href="/~dvorap74/fake-a-gram/css/style.php">

    <!-- Loads CSS styling based on system preference -->
    <link rel="stylesheet" href="/~dvorap74/fake-a-gram/css/darkstyle.css" media="(prefers-color-scheme: dark), (prefers-color-scheme: no-preference)">
    <link rel="stylesheet" href="/~dvorap74/fake-a-gram/css/lightstyle.css" media="(prefers-color-scheme: light)">

    <!-- Custom Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

    <!-- Custom JS -->
    <script src="/~dvorap74/fake-a-gram/js/settingsPage.js"></script>
</head>

<body>
    <nav>
        <a href="/~dvorap74/fake-a-gram/">Homepage</a>
        <?php
            if (isset($_SESSION["username"]))
            {
                echo('<a href="/~dvorap74/fake-a-gram/post.php">Add Post</a>');
                echo('<span class="right">');
                    echo('<a id="username">'. $_SESSION["username"] .'</a>');
                    echo('<a href="/~dvorap74/fake-a-gram/logout.php">Logout</a>');
                echo('</span>');
            }
        ?>
    </nav>
    <div class="main">
        <div class="post">
            <form method="POST" action="#" id="settingsForm">
                <label for="accentColor">Choose Your Accent Color:
                    <input type="color" name="accentColor" id="accentColor" value="<?php echo isset($_SESSION["accentColor"]) ? $_SESSION["accentColor"] : "#FFCF82" ?>" required>
                </label>
                <label for="textColor">Set Text Color to White:
                    <input type="checkbox" name="textColor" id="textColor" <?php
                         if (isset($_SESSION["textColor"])) {
                             if ($_SESSION["textColor"] === 'on') 
                             {
                                 echo ("checked");
                             }
                         }
                         ?>>
                </label>
                <label for="compact">Hide Comments:
                    <input type="checkbox" name="compact" id="compact" <?php
                         if (isset($_SESSION["compact"])) {
                             if ($_SESSION["compact"] === 'on') 
                             {
                                 echo ("checked");
                             }
                         }
                         ?>>
                </label>
                <input type="submit" name="submit" id="submit" value="Save Settings">
            </form>

            <form method="POST" action="#" id="resetForm">
                    <input type="submit" name="reset" id="reset" value="Reset Settings">
            </form>
        </div>
    </div>

    <script>
        initSettings();
    </script>
</body>

</html>