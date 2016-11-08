<?php
    require 'connect.php';
    session_start();

    $emptycontent = false;

    if (isset($_GET['postid']) && isset($_GET['userid'])) {
        $postid = filter_input(INPUT_GET, 'postid', FILTER_VALIDATE_INT);
        $userid = filter_input(INPUT_GET, 'userid', FILTER_VALIDATE_INT);

        if (isset($postid)) {
            $query = "SELECT posts.*, users.* FROM posts, users WHERE postid = :postid && users.userid = :userid";
            $statement = $db->prepare($query);
            $statement->bindValue(':postid', $postid);
            $statement->bindValue(':userid', $userid);
            $statement->execute();
            $post = $statement->fetch();

            $commentquery = "SELECT comments.*, users.* FROM comments, users WHERE comments.postid = :postid && users.userid = :userid";
            $commentstatement = $db->prepare($commentquery);
            $commentstatement->bindValue(':postid', $postid);
            $commentstatement->bindValue(':userid', $post['userid']);
            $commentstatement->execute();
            $comments = $commentstatement->fetchAll();

            if (isset($_SESSION['loggedin'])) {
                if (isset($_POST['submit'])) {
                    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    if (isset($content) && strlen($content) > 0) {
                        $idquery = "SELECT userid FROM users WHERE username = :username";
                        $idstatement = $db->prepare($idquery);
                        $idstatement->bindValue(':username', $_SESSION['user']);
                        $idstatement->execute();
                        $loggedinuser = $idstatement->fetch();

                        $newcomment = "INSERT INTO comments (userid, postid, content, datecreated) values (:userid, :postid, :content, now())";
                        $newcommentstatement = $db->prepare($newcomment);
                        $newcommentstatement->bindValue(':userid', $loggedinuser['userid']);
                        $newcommentstatement->bindValue(':postid', $postid);
                        $newcommentstatement->bindValue(':content', $content);
                        $newcommentstatement->execute();
                    } else {
                        if (strlen($title) == 0) {
                            $emptytitle = true;
                        }

                        if (strlen($content) == 0) {
                            $emptycontent = true;
                        }
                    }
                }
            }
        }
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
            <?php if (isset($post['postid'])): ?>
                <h2><?= $post['title'] ?> <small><a href="profile.php?id=<?= $post['username'] ?>"><?= $post['username'] ?></a></small></h2>

                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($post['username'] == $_SESSION['user']): ?>
                        <a href="editpost.php?userid=<?= $post['userid'] ?>&postid=<?= $post['postid'] ?>" class="btn btn-default edit-post" role="button">Edit <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                    <?php endif; ?>
                <?php endif; ?>

                <p><?= date("F j, Y g:i a", strtotime($post['datecreated'])); ?></p>
                <p><?= $post['content'] ?></p>

                <h4 class="comment-heading">Comments</h4>

                <?php foreach ($comments as $comment): ?>
                    <div>
                        <p><?= $comment['content'] ?></p>
                        <p><small>By <?= $comment['username'] ?></small></p>
                    </div>
                <?php endforeach; ?>

                <?php if (isset($_SESSION['loggedin'])): ?>
                    <form method="post" action="" id="newcomment">
                        <div class="form-group">
                            <label for="content">Leave a comment</label>
                            <textarea name="content" id="content" rows="5" maxlength="1000" class="form-control" placeholder="Leave a comment here"></textarea>
                            <?php if ($emptycontent): ?>
                                <span id="helpBlock" class="help-block">Please enter something.</span>
                            <?php endif; ?>
                        </div>
                        <input type="submit" name="submit" class="btn btn-default" value="Comment" />
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        <p>Please <a href="login.php" class="alert-link">log in</a> to leave a comment.</p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    <p>No post found.</p>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>