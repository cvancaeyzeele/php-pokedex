<?php
    require './../resources/library/connect.php';
    session_start();

    $userfound = false;

    // if user is logged in and there is no id in url, profile shown will be theirs
    // if user is not logged in and there is no id in url, error and link to log in will be shown
    // if there is an id in url, that profile will be shown regardless if user is logged in or not
    if (isset($_GET['id'])) {
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

        if ($user['status'] != 'banned' && $user['status'] != 'suspended' && $user != false) {
            $postquery = "SELECT * FROM posts WHERE userid = :userid ORDER BY datecreated DESC LIMIT 10";
            $poststatement = $db->prepare($postquery);
            $poststatement->bindValue(':userid', $user['userid']);
            $poststatement->execute();
            $posts = $poststatement->fetchAll();

            $commentquery = "SELECT * FROM comments WHERE comments.userid = :userid ORDER BY datecreated DESC LIMIT 10";
            $commentstatement = $db->prepare($commentquery);
            $commentstatement->bindValue(':userid', $user['userid']);
            $commentstatement->execute();
            $comments = $commentstatement->fetchAll();

            $userfound = true;
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
        <script src="js/profile.js"></script>
    </head>
    <body>
        <? include './../resources/templates/header.php'; ?>
        <div class="panel panel-default">
            <!-- if user is logged in or id is passed through GET -->
            <?php if ($userfound == true): ?>
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
                    <img src="img/user/<?= $user['picture'] ?>" alt="profile-picture" class="img-rounded" />
                <?php endif; ?>

                <!-- display bio if set, placeholder text if not -->
                <?php if (is_null($user['bio'])): ?>
                    <p class="lead">No bio available</p>
                <?php else: ?>
                    <p class="lead"><?= $user['bio'] ?></p>
                <?php endif; ?>

                <!-- buttons to display posts or comments -->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default" id="post-button">Posts</button>
                    <button type="button" class="btn btn-default" id="comment-button">Comments</button>
                </div>
                
                <!-- new post button -->
                <?php if (isset($_SESSION['loggedin'])): ?>
                    <?php if ($_SESSION['user'] === $user['username']): ?>
                        <a href="newpost.php?id=<?= $user['userid'] ?>" class="btn btn-default new-post-btn" role="button">New Post <i class="fa fa-plus" aria-hidden="true"></i></a>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- posts created by user -->
                <div class="user-posts well" id="user-posts">
                    <?php foreach ($posts as $post): ?>
                        <h4 class="post-heading"><a href="post.php?userid=<?= $user['userid'] ?>&postid=<?= $post['postid'] ?>"><?= $post['title'] ?></a></h4>
                        <p><?= date("F j, Y g:i a", strtotime($post['datecreated'])); ?></p>
                        <?php if (strlen($post['content']) > 200): ?>
                            <p><?= substr($post['content'],0,200) ?>... <a href="post.php?userid=<?= $user['userid'] ?>&postid=<?= $post['postid'] ?>">Full Post Here</a></p>
                        <?php else: ?>
                            <p><?= $post['content'] ?></p>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- comments created by user -->
                <div class="user-comments well" id="user-comments">
                    <?php foreach ($comments as $comment): ?>
                        <h4 class="post-heading"><a href="post.php?userid=<?= $post['userid'] ?>&postid=<?= $comment['postid'] ?>">Comment</a></h4>
                        <p><?= date("F j, Y g:i a", strtotime($comment['datecreated'])); ?></p>
                        <p><?= $comment['content'] ?></p>
                    <?php endforeach; ?>
                </div>

            <!-- warning message with link to login page if user not logged in and no id passed -->
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    <p>No user found.</p>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>