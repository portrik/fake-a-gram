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
    font-family: 'Varela Round', sans-serif;
}

div {
    border-radius: 25px;
}

footer {
    width: 100vw;
    height: 5vh;
    background: <?php echo($accent) ?>;
}

a {
    color: <?php
    if ($whiteText)
    {
        echo('white');
    }
    else 
    {
        echo('black');
    }
    ?>
}

nav {
    background: <?php echo($accent) ?>;
    height: 5vh;
}

nav a {
    margin-left: 10px;
}

input {
    width: 100%;
    padding: 0;
    font-size: 24px;
    font-family: 'Varela Round', sans-serif;
    border-radius: 25px;
    margin-top: 5px;
}

.pagination {
    width: 60vw;
    margin-left: auto;
    margin-right: auto;
    margin-top: 1vh;
    font-size: 1.5em;
}

.pagination a {
    margin-left: 10px;
}

.active {
    border-bottom: 5px groove red;
}

.main {
    width: 60vw;
    margin-left: auto;
    margin-right: auto;
    margin-top: 2vh;
}

.hidden {
    display: none;
}

.postMain {
    width: 100%;
}

.postSecondary {
    padding: 1vw;
}

.post {
    background: rgba(0, 0, 0, 0.2);
    margin-bottom: 5vh;
}

.imgPost {
    display: block;
    cursor: pointer;
    max-width: 30vw;
    margin-left: auto;
    margin-right: auto;
}

.likeForm, .likeCounter {
    margin: 5px;
    display: inline-block;
    width: 40%;
}

.comments {
    background: rgba(0, 0, 0, 0.2);
    padding-left: 5px;
}

.right {
    float: right;
}

.right a {
    margin-right: 10px;
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