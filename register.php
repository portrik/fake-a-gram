<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register - Fake-a-Gram</title>
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
        <a href="/login.php">Login</a>
    </nav>
    <div>
        <div>
            <form action="postregistration.php" method="POST">
                <label for="username">Username
                    <input type="text" name="username" id="username">
                </label>
                <label for="email">Email
                    <input type="email" name="email" id="email">
                </label>
                <label for="password">Password
                    <input type="password" name="password" id="password">
                </label>
                <label for="passwordConfirm">Confirm Password
                    <input type="password" name="passwordConfirm" id="passwordConfirm">
                </label>
                <input type="submit" name="submit" id="submit">
            </form>
        </div>
    </div>
</body>

</html>