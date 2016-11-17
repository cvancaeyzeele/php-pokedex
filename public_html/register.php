<?php
    require './../resources/library/connect.php';
    session_start();

    $usernameTaken = false;
    $emailTaken = false;
    $emptyusername = false;
    $emptyemail = false;
    $emptypassword = false;
    $differentpasswords = false;
    $invalidemail = false;
    $invalidusername = false;

    date_default_timezone_set('America/Winnipeg');

    if (isset($_POST['submit'])) {
        $username = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_REGEXP,  array("options"=>array("regexp"=>"/^[A-Za-z0-9_-]{3,25}$/")));
        $email = filter_input(INPUT_POST, 'emailaddress', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $originalpassword = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $confirmpassword = filter_input(INPUT_POST, 'confirmpassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // hash password
        $password = password_hash($originalpassword, PASSWORD_DEFAULT);

        // TODO: set length restrictions on username, email, password
        if (isset($username) && isset($email) && isset($password) && isset($confirmpassword)
            && strlen($username) > 0 && strlen($email) > 0 && strlen($password) > 0
            && strlen($confirmpassword) > 0 && filter_input(INPUT_POST, 'emailaddress', FILTER_VALIDATE_EMAIL) && ctype_alnum($username)
            && $originalpassword == $confirmpassword) {

            // check if username and email are in use
            $usernameQuery = "SELECT username FROM users WHERE username = :username";
            $usernameStatement = $db->prepare($usernameQuery);
            $usernameStatement->bindValue(':username', $username);
            $usernameStatement->execute();
            $matchingUsers = $usernameStatement->fetchAll();

            $emailQuery = "SELECT emailaddress FROM users WHERE emailaddress = :email";
            $emailStatement = $db->prepare($emailQuery);
            $emailStatement->bindValue(':email', $email);
            $emailStatement->execute();
            $matchingEmail = $emailStatement->fetchAll();

            // if username is already in database
            if (count($matchingUsers) > 0) {

                $usernameTaken = true;
            }

            // if email is already in database
            if (count($matchingEmail) > 0) {

                $emailTaken = true;
            }

            // if username and email are available create a new user
            if (!$usernameTaken && !$emailTaken) {

                $query = "INSERT INTO users (username, emailaddress, password) values (:username, :email, :password)";
                $statement = $db->prepare($query);
                $statement->bindValue(':username', $username);
                $statement->bindValue(':email', $email);
                $statement->bindValue(':password', $password);
                $statement->execute();

                // log the new user in
                $_SESSION['user'] = $username;
                $_SESSION['loggedin'] = true;

                header('Location: index.php');
                exit();
            }
        } else {
            // if no username is entered
            if ($username == false) {
                $invalidusername = true;
            } elseif (strlen($username) == 0) {
                $emptyusername = true;
            }

            $emailcheck = filter_input(INPUT_POST, 'emailaddress', FILTER_VALIDATE_EMAIL);

            // if no email is entered or email does not validate
            if (strlen($email) == 0) {
                $emptyemail = true;
            } elseif ($emailcheck === false) {
                $invalidemail = true;
            }

            // if no password is entered or passwords do not match
            if (strlen($originalpassword) == 0 && strlen($confirmpassword) == 0) {
                $emptypassword = true;
            } elseif ($originalpassword != $confirmpassword) {
                $differentpasswords = true;
            }
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pok&eacute;Lookup</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <script
            src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="js/validate.js"></script>
    </head>
    <body>
        <? include './../resources/templates/header.php'; ?>
        <div class="panel panel-default">
            <form method="post" action="" id="registrationform">
                <div class="alert alert-info" role="alert">
                    <p>Already have an account? Log in <a href="login.php" class="alert-link">here!</a></p>
                </div>
                <div class="form-group <?php if ($usernameTaken || $emptyusername || $invalidusername) echo 'has-error'; ?>">
                    <label for="username">Username</label>
                    <input name="username" type="text" class="form-control" id="username" value="" placeholder="Username">
                    <?php if ($usernameTaken): ?>
                        <span id="helpBlock" class="help-block">This username is already in use.</span>
                    <?php elseif ($emptyusername): ?>
                        <span id="helpBlock" class="help-block">Please enter a username.</span>
                    <?php elseif ($invalidusername): ?>
                        <span id="helpBlock" class="help-block">Username cannot contain special characters and must be less than 25 characters.</span>
                    <?php endif; ?>
                </div>
                <div class="form-group <?php if ($emailTaken || $emptyemail || $invalidemail) echo 'has-error'; ?>">
                    <label for="emailaddress">Email address</label>
                    <input name="emailaddress" type="email" class="form-control" id="emailaddress" value="" placeholder="Email" />
                    <?php if ($emailTaken): ?>
                        <span id="helpBlock" class="help-block">This email address is already in use.</span>
                    <?php elseif ($emptyemail): ?>
                        <span id="helpBlock" class="help-block">Please enter an email address.</span>
                    <?php elseif ($invalidemail): ?>
                        <span id="helpBlock" class="help-block">Please enter a valid email address.</span>
                    <?php endif; ?>
                </div>
                <div class="form-group <?php if ($emptypassword || $differentpasswords) echo 'has-error'; ?>">
                    <label for="password">Password</label>
                    <input name="password" type="password" class="form-control" id="password" value="" placeholder="Password" />
                    <?php if ($emptypassword): ?>
                        <span id="helpBlock" class="help-block">Please enter a password.</span>
                    <?php endif; ?>
                </div>
                <div class="form-group <?php if ($differentpasswords) echo 'has-error'; ?>">
                    <label for="confirmpassword">Re-enter Password</label>
                    <input name="confirmpassword" type="password" class="form-control" id="confirmpassword" value="" placeholder="Password" />
                    <?php if ($differentpasswords): ?>
                        <span id="helpBlock" class="help-block">Passwords do not match.</span>
                    <?php endif; ?>
                </div>
                <input type="submit" name="submit" class="btn btn-default" value="Register" />
            </form>
        </div>
    </body>
</html>
