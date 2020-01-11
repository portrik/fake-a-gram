<?php
    header('Content-type: text/css; charset: UTF-8;');
    session_start();
    $accent = '#FFCF82';
    $compact = false;
    $whiteText = false;

    if (isset($_SESSION["accentColor"]))
    {
        $accent = $_SESSION["accentColor"];
    }

    if (isset($_SESSION["compact"]))
    {
        if ($_SESSION["compact"] === 'on')
        {
            $compact = true;
        }
    }

    if (isset($_SESSION["textColor"]))
    {
        if ($_SESSION["textColor"] === 'on')
        {
            $whiteText = true;
        }
    }
?>

body {
    margin: 0;
    padding: 0;
    background: grey;
    font-family: 'Varela Round', sans-serif;
}

a {
    color: black;
}

nav {
    background: <?php echo($accent) ?>;
    height: 5vh;
}

input, button {
    width: 100%;
    padding: 0;
    font-size: 36px;
    font-family: 'Varela Round', sans-serif;
}

.mainWrapper {
    width: 40vw;
    margin: auto;
}

.hidden {
    display: none;
}

.imgPost {
    cursor: pointer;
    max-width: 50vw;
    max-height: 25vh;
    margin-left: 45vw;
}

#overlay {
    position: fixed;
    width: 100vw;
    height: 100vh;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 2;
    cursor: pointer;
}

#overlayImg {
    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-top: 2vh;
    max-width: 90%;
    max-height: 90%;
}