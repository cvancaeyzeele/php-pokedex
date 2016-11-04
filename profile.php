<?php
    require 'connect.php';
    session_start();

    // if user is logged in and there is no id in url, profile shown will be theirs
    // if user is not logged in and there is no id in url, error and link to log in will be shown
    // if there is an id in url, that profile will be shown regardless if user is logged in or not
    if (isset($_SESSION['loggedin']) || isset($_GET['id'])) {
        $username;

        // make sure a username is included in page url
        if (isset($_GET['id'])) {
            $username = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } elseif (isset($_SESSION['user'])) { // if no username passed through GET, get it from session variable
            $username = $_SESSION['user'];    // in case user types in url manually and doesn't click link
        } // no else; html code below displays error and link to login page if user has not logged in

        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $user = $statement->fetch();
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <title>Pok&eacute;Lookup</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/latest/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="css/main-styles.css" />
        <link rel="stylesheet" type="text/css" href="css/profile-styles.css" />
        <script
            src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://use.fontawesome.com/f51889d3c4.js"></script>
    </head>
    <body>
        <? include 'header.php'; ?>
        <div class="panel panel-default">
            <!-- if user is logged in or id is passed through GET -->
            <?php if (isset($_SESSION['loggedin']) || isset($_GET['id'])): ?>
                <h2><?= $user['username'] ?></h2>
                <!-- display real name if set -->
                <?php if (!is_null($user['realname'])): ?>
                    <h2 class="realname"><small><?= $user['realname'] ?></small></h2>
                <?php endif; ?>

                <!-- if user is on their profile and logged in -->
                <?php if (isset($_SESSION['loggedin'])): ?>
                    <?php if ($_SESSION['user'] === $user['username']): ?>
                        <a href="editprofile.php" type="button" class="btn btn-default edit-btn" role="button">Edit <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- display profile picture if set, placeholder if not -->
                <?php if (is_null($user['picture'])): ?>
                    <img src="https://api.adorable.io/avatars/80/<?= $user['userid'] ?>.png" alt="profile-picture" class="img-rounded" />
                <?php else: ?>
                    <img src="<?= 'data:image/jpeg;base64,'.base64_encode( $user['picture'] ) ?>" alt="profile-picture" class="img-rounded" />
                <?php endif; ?>

                <!-- display bio if set, placeholder text if not -->
                <?php if (is_null($user['bio'])): ?>
                    <p class="lead">No bio available</p>
                <?php else: ?>
                    <p class="lead"><?= $user['bio'] ?></p>
                <?php endif; ?>

                <!-- buttons to display posts or comments -->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default">Posts</button>
                    <button type="button" class="btn btn-default">Comments</button>
                </div>
            <!-- warning message with link to login page if user not logged in and no id passed -->
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    <p>Please <a href="login.php" class="alert-link">log in.</a></p>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>