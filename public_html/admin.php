<?php
    require './../resources/library/connect.php';
    session_start();

    $actioncompleted = false;

    // Check if user is logged in and is an admin
    if (!isset($_SESSION['userrole'])) {
        // if not an admin, redirect to homepage
        header('Location: index.php');
    } else {
        if ($_SESSION['userrole'] < 4) {
            // get all posts
            $postQuery = "SELECT postid, title FROM posts ORDER BY datecreated DESC";
            $postStatement = $db->prepare($postQuery);
            $postStatement->execute();
            $posts = $postStatement->fetchAll();

            // get all users
            $userQuery = "SELECT userid, username, rolename, users.roleid, status FROM users, roles WHERE users.roleid = roles.roleid AND username != :username ORDER BY userid";
            $userStatement = $db->prepare($userQuery);
            $userStatement->bindValue(':username', $_SESSION['user']);
            $userStatement->execute();
            $users = $userStatement->fetchAll();
        }
    }

    // when admin clicks submit on one of the buttons
    // check which button was clicked
    if (isset($_POST['remove-post'])) {
        // remove selected posts from database
        foreach ($_POST['posts'] as $post) {
            $removePost = "DELETE FROM posts WHERE postid = :postid";
            $removeStatement = $db->prepare($removePost);
            $removeStatement->bindValue(':postid', $post);
            $removeStatement->execute();
        }

        $actioncompleted = true;
        header('Location: admin.php');
        exit();
    } elseif (isset($_POST['ban-new'])) {
        // ban selected users
        foreach ($_POST['users'] as $user) {
            $banUsers = "UPDATE users SET status = 'banned' WHERE userid = :userid";
            $banStatement = $db->prepare($banUsers);
            $banStatement->bindValue(':userid', $user);
            $banStatement->execute();
        }

        $actioncompleted = true;
        header('Location: admin.php');
        exit();
    } elseif (isset($_POST['ban'])) {
        // ban selected users
        foreach ($_POST['bannedusers'] as $user) {
            $banUsers = "UPDATE users SET status = 'banned' WHERE userid = :userid";
            $banStatement = $db->prepare($banUsers);
            $banStatement->bindValue(':userid', $user);
            $banStatement->execute();
        }

        $actioncompleted = true;
        header('Location: admin.php');
        exit();
    } elseif (isset($_POST['suspend'])) {
        // suspend selected users
        foreach ($_POST['users'] as $user) {
            $suspend = "UPDATE users SET status = 'suspended' WHERE userid = :userid";
            $suspendStatement = $db->prepare($suspend);
            $suspendStatement->bindValue(':userid', $user);
            $suspendStatement->execute();
        }

        $actioncompleted = true;
        header('Location: admin.php');
        exit();
    } elseif (isset($_POST['unban'])) {
        // unban selected users
        foreach ($_POST['bannedusers'] as $user) {
            $unban = "UPDATE users SET status = NULL WHERE userid = :userid";
            $unbanStatement = $db->prepare($unban);
            $unbanStatement->bindValue(':userid', $user);
            $unbanStatement->execute();
        }

        $actioncompleted = true;
        header('Location: admin.php');
        exit();
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <title>PokéLookup</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <script
            src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://use.fontawesome.com/f51889d3c4.js"></script>
        <script src="js/admin.js"></script>
    </head>
    <body>
        <? include './../resources/templates/header.php'; ?>
        <div class="panel panel-default">
            <?php if (isset($_SESSION['loggedin'])): ?>
                <?php if ($_SESSION['userrole'] < 4): ?>
                    <h2>Admin Dashboard</h2>
                    <?php if ($actioncompleted == true): ?>
                        <p class="bg-success">Action completed successfully.</p>
                    <?php endif; ?>

                    <!-- buttons to display posts or comments -->
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-default" id="user-button">Users</button>
                        <?php if ($_SESSION['userrole'] < 3): ?>
                            <button type="button" class="btn btn-default" id="unban-button">Banned</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-default" id="post-button">Posts</button>
                    </div>


                    <!-- list of posts -->
                    <div class="post-list well" id="post-list">
                        <div class="checkbox select-all">
                            <label>
                                <input type="checkbox" value="" onClick="togglePosts(this)" />
                                <strong>Select All</strong>
                            </label>
                        </div>

                        <form action="" method="post">
                            <?php foreach ($posts as $post): ?>
                                <div class="checkbox post-checkbox">
                                    <label>
                                        <input type="checkbox" value="<?= $post['postid'] ?>" class="post-checkbox" name="posts[]" />
                                        <?= $post['title'] ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>

                            <button class="btn btn-default" type="submit" name="remove-post">Remove <i class="fa fa-trash" aria-hidden="true"></i></button>
                        </form>
                    </div>

                    <!-- list of users -->
                    <div class="user-list well" id="user-list">
                        <div class="checkbox select-all">
                            <label>
                                <input type="checkbox" value="" onClick="toggleUsers(this)" />
                                <strong>Select All</strong>
                            </label>
                        </div>

                        <form action="" method="post">
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['status'] != 'banned' && $user['status'] != 'suspended'): ?>
                                    <div class="checkbox <?php if($user['roleid'] < $_SESSION['userrole']) { echo 'disabled'; } ?>">
                                        <label>
                                            <input type="checkbox" value="<?= $user['userid'] ?>" class="user-checkbox" name="users[]" <?php if($user['roleid'] < $_SESSION['userrole']) { echo 'disabled'; } ?>/>
                                            <?= $user['username'] ?> <small class="capitalize"><?= $user['rolename'] ?></small>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php if ($_SESSION['userrole'] < 3): ?><button class="btn btn-default" type="submit" name="ban-new">Ban <i class="fa fa-user-times" aria-hidden="true"></i></button><?php endif; ?>
                            <?php if ($_SESSION['userrole'] < 4): ?><button class="btn btn-default" type="submit" name="suspend">Suspend <i class="fa fa-ban" aria-hidden="true"></i></button><?php endif; ?>
                        </form>
                    </div>

                    <!-- list of banned/suspended users -->
                    <div class="banned-user-list well" id="banned-user-list">
                        <div class="checkbox select-all">
                            <label>
                                <input type="checkbox" value="" onClick="toggleBannedUsers(this)" />
                                <strong>Select All</strong>
                            </label>
                        </div>

                        <form action="" method="post">
                            <?php foreach ($users as $user): ?>
                                <?php if ($user['status'] == 'banned' || $user['status'] == 'suspended'): ?>
                                    <div class="checkbox <?php if($user['roleid'] < $_SESSION['userrole']) { echo 'disabled'; } ?>">
                                        <label>
                                            <input type="checkbox" value="<?= $user['userid'] ?>" class="banned-user-checkbox" name="bannedusers[]" <?php if($user['roleid'] < $_SESSION['userrole']) { echo 'disabled'; } ?>/>
                                            <?= $user['username'] ?> <small class="capitalize"><?= $user['rolename'] ?></small> <small class="capitalize"><?= $user['status'] ?></small>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <?php if ($_SESSION['userrole'] < 3): ?><button class="btn btn-default" type="submit" name="ban">Ban <i class="fa fa-user-times" aria-hidden="true"></i></button><?php endif; ?>
                            <?php if ($_SESSION['userrole'] < 4): ?><button class="btn btn-default" type="submit" name="unban">Remove Ban <i class="fa fa-undo" aria-hidden="true"></i></button><?php endif; ?>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> You do not have access to this page.</div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> You do not have access to this page.</div>
            <?php endif; ?>
        </div>
    </body>
</html>
