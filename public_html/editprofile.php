<?php
    // php file to connect to the database
    require './../resources/library/connect.php';

    session_start();

    // make sure user is logged in and on their profile
    if (isset($_SESSION['loggedin'])) {
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $_SESSION['user']);
        $statement->execute();
        $user = $statement->fetch();
    }

    // when user clicks save changes, validate changes and update user in the database
    if (isset($_POST['submit'])) {
        // sanitize real name and bio fields
        $realname = filter_input(INPUT_POST, 'realname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $bio = filter_input(INPUT_POST, 'bio', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // to sum this up: if a field is empty, it is updated to null in database
        // real name and bio CAN be empty if user chooses not to share that info
        if (isset($realname) && isset($bio)) {
            if (strlen($realname) < 1 && strlen($bio) > 0) { // if realname is empty update column to null
                $update = "UPDATE users SET realname = NULL, bio = :bio WHERE username = :username";
                $updateStatement = $db->prepare($update);
                $updateStatement->bindValue(':username', $_SESSION['user']);
                $updateStatement->bindValue(':bio', $bio);
                $updateStatement->execute();
            } elseif (strlen($bio) < 1 && strlen($realname) > 0) { // if bio is empty update column to null
                $update = "UPDATE users SET realname = :realname, bio = NULL WHERE username = :username";
                $updateStatement = $db->prepare($update);
                $updateStatement->bindValue(':username', $_SESSION['user']);
                $updateStatement->bindValue(':realname', $realname);
                $updateStatement->execute();
            } elseif (strlen($bio) < 1 && strlen($realname) < 1) { // if both are empty ....
                $update = "UPDATE users SET realname = NULL, bio = NULL WHERE username = :username";
                $updateStatement = $db->prepare($update);
                $updateStatement->bindValue(':username', $_SESSION['user']);
                $updateStatement->execute();
            } else {
                $update = "UPDATE users SET realname = :realname, bio = :bio WHERE username = :username";
                $updateStatement = $db->prepare($update);
                $updateStatement->bindValue(':username', $_SESSION['user']);
                $updateStatement->bindValue(':realname', $realname);
                $updateStatement->bindValue(':bio', $bio);
                $updateStatement->execute();
            }

            // redirect user to their profile page
            header('Location: profile.php?id='.$_SESSION['user']);
            exit();
        }
    }
?>
<!doctype html>
<html lang="en">
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
        <script src="https://use.fontawesome.com/f51889d3c4.js"></script>
    </head>
    <body>
        <? include './../resources/templates/header.php'; ?>
        <div class="panel panel-default">
            <!-- if user is logged in -->
            <?php if (isset($_SESSION['loggedin'])): ?>
                <form method="post" action="" id="edit">
                    <div class="form-group">
                        <label for="realname">Name</label>
                        <input type="text" class="form-control" id="realname" name="realname" placeholder="Full name" value="<?= $user['realname'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea name="bio" id="bio" rows="5" maxlength="500" class="form-control" placeholder="Write something about yourself!"><?= $user['bio'] ?></textarea>
                    </div>
                    <input type="submit" name="submit" class="btn btn-default" value="Save Changes" />
                </form>
            <!-- warning message with link to login page if user not logged in -->
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    <p>Please <a href="login.php" class="alert-link">log in.</a></p>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>