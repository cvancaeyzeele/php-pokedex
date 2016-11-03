<?php
    require 'connect.php';
    session_start();

    $validlogin = true;
    $emptyfields = false;

    date_default_timezone_set('America/Winnipeg');

    if (isset($_POST['submit'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (isset($username) && isset($password) && strlen($username) > 0 && strlen($password) > 0) {
            // find user matching username
            $query = "SELECT * FROM users WHERE username = :username";
            $statement = $db->prepare($query);
            $statement->bindValue(':username', $username);
            $statement->execute();
            $matchingUser = $statement->fetch();

            // if a match is found verify password against hash
            if (count($matchingUser) > 0) {
                // if password is correct create session variables and redirect to index.php
                if (password_verify($password, $matchingUser['password'])) {
                    $_SESSION['user'] = $matchingUser['username'];
                    $_SESSION['loggedin'] = true;
                    header("Location: index.php");
                    exit();
                } else {
                    // set variable to false to show error message
                    $validlogin = false;
                }
            } else {
                // set variable to false to show error message
                $validlogin = false;
            }
        } else {
            // set variable to true to show error message
            $emptyfields = true;
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pok&eacute;Lookup</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/latest/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="css/main-styles.css" />
        <link rel="stylesheet" type="text/css" href="css/form-styles.css" />
        <script
            src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>
        <? include 'header.php'; ?>
        <div class="panel panel-default">
            <form method="post" action="">
                <div class="alert alert-info" role="alert">
                    <p>Don't have an account? Sign up <a href="register.php" class="alert-link">here!</a></p>
                </div>
                <?php if (!$validlogin): ?>
                    <p class="text-danger">Invalid username or password.</p>
                <?php endif; ?>
                <?php if ($emptyfields): ?>
                    <p class="text-danger">Please fill in all fields.</p>
                <?php endif; ?>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input name="username" type="text" class="form-control" id="username" value="" placeholder="Username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input name="password" type="password" class="form-control" id="password" value="" placeholder="Password">
                </div>
                <input type="submit" name="submit" class="btn btn-default" value="Log In" />
            </form>
        </div>
    </body>
</html>
