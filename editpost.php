<?php
    require 'connect.php';
    session_start();

    $emptytitle = false;
    $emptycontent = false;

    date_default_timezone_set('America/Winnipeg');

    if (isset($_SESSION['loggedin']) && isset($_GET['userid']) && isset($_GET['postid'])) {
        $userid = filter_input(INPUT_GET, 'userid', FILTER_VALIDATE_INT);
        $postid = filter_input(INPUT_GET, 'postid', FILTER_VALIDATE_INT);

        $query = "SELECT * FROM users WHERE userid = :userid";
        $statement = $db->prepare($query);
        $statement->bindValue(':userid', $userid);
        $statement->execute();
        $user = $statement->fetch();

        $postquery = "SELECT * FROM posts WHERE postid = :postid";
        $poststatement = $db->prepare($postquery);
        $poststatement->bindValue(':postid', $postid);
        $poststatement->execute();
        $post = $poststatement->fetch();

        if (isset($_POST['submit'])) {
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (isset($title) && isset($content) && strlen($title) > 0 && strlen($content) > 0) {
                $update = "UPDATE posts SET title = :title, content = :content WHERE postid = :postid";
                $updateStatement = $db->prepare($update);
                $updateStatement->bindValue(':title', $title);
                $updateStatement->bindValue(':content', $content);
                $updateStatement->bindValue(':postid', $postid);
                $updateStatement->execute();

                header('Location: post.php?userid='.$userid.'&postid='.$postid);
                exit();

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
?>
<!doctype html>
<html lang="en">
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
        <script src="https://use.fontawesome.com/f51889d3c4.js"></script>
        <script src="js/postvalidate.js"></script>
    </head>
    <body>
        <? include 'header.php'; ?>
        <div class="panel panel-default">
            <?php if (isset($_SESSION['loggedin']) && isset($_GET['userid']) && isset($_GET['postid']) && $_SESSION['user'] == $user['username']): ?>
                <form method="post" action="" class="form" id="newpost">
                    <div class="form-group <?php if ($emptytitle) echo 'has-error'; ?>">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Give your post a name" value="<?= $post['title'] ?>"/>
                        <?php if ($emptytitle): ?>
                            <span id="helpBlock" class="help-block">Please enter a title.</span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group <?php if ($emptycontent) echo 'has-error'; ?>">
                        <label for="content">Post</label>
                        <textarea name="content" id="content" rows="10" maxlength="3000" class="form-control" placeholder="Write your post here"><?= $post['content'] ?></textarea>
                        <?php if ($emptycontent): ?>
                            <span id="helpBlock" class="help-block">Post must have content.</span>
                        <?php endif; ?>
                    </div>
                    <input type="submit" name="submit" class="btn btn-default" value="Save post" />
                </form>
            <?php elseif (!isset($_SESSION['loggedin'])): ?>
                <div class="alert alert-warning" role="alert">
                    <p>Please <a href="login.php" class="alert-link">log in.</a></p>
                </div>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    <p>Post could not be found.</p>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>