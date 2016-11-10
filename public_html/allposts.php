<?php
    // php file to connect to the database
    require './../resources/library/connect.php';

    session_start();

    // check if there is a session variable containing posts
    // if there is it means user selected sorting options
    // use that instead of doing a query because query was done before posting
    if (isset($_SESSION['posts'])) {
        $posts = $_SESSION['posts'];

        // unset the session variable
        unset($_SESSION['posts']);
    } else {
        // if user is coming to this page for first time with no sorting options
        // sort results by date, newest to oldest by default
        $postquery = "SELECT * FROM posts ORDER BY datecreated DESC";
        $poststatement = $db->prepare($postquery);
        $poststatement->execute();
        $posts = $poststatement->fetchAll();
    }

    // when user submits form with sorting options, query table to get all posts sorted with options posted
    if (isset($_POST['submit'])) {
        $sortby = filter_input(INPUT_POST, 'sortby', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $order = filter_input(INPUT_POST, 'order', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $category = filter_input(INPUT_POST, 'posttype', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (isset($sortby) && isset($order) && isset($category)) {
            // if they want posts from all categories
            if ($category == 'all') {
                $postquery = "SELECT * FROM posts ORDER BY $sortby $order";
                $poststatement = $db->prepare($postquery);
                $poststatement->execute();
                $posts = $poststatement->fetchAll();
                $_SESSION['posts'] = $posts;
            } else { // if they only want posts from a specific category
                $postquery = "SELECT * FROM posts WHERE category = :category ORDER BY $sortby $order";
                $poststatement = $db->prepare($postquery);
                $poststatement->bindValue(':category', $category);
                $poststatement->execute();
                $posts = $poststatement->fetchAll();
                $_SESSION['posts'] = $posts;
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
    </head>
    <body>
        <? include './../resources/templates/header.php'; ?>
        <div class="options panel panel-info">
            <div class="panel-heading">Sort Options</div>
            <form action="" method="post" class="form">
                <select class="form-control" name="sortby">
                    <option value="title">Title</option>
                    <option value="datecreated">Date Created</option>
                    <option value="type">Post Type</option>
                </select>
                <select class="form-control" name="order">
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
                <select class="form-control" name="posttype">
                    <option value="all">All</option>
                    <option value="help">Help</option>
                    <option value="update">Update</option>
                    <option value="other">Other</option>
                </select>
                <button type="submit" name="submit" class="btn btn-primary">Go</button>
            </form>
        </div>
        <div class="panel panel-default">
            <?php foreach ($posts as $post): ?>
                <div class="post <?= $post['userid'] ?> <?= $post['category'] ?> <?= date( "m/d/y", strtotime($post['datecreated'])) ?>">
                    <h2 class="post-title"><a href="post.php?userid=<?= $post['userid'] ?>&postid=<?= $post['postid'] ?>"><?= $post['title'] ?></a></h2>
                    <p class="date text-muted">
                        <?= date("F j, Y g:i a", strtotime($post['datecreated'])); ?>
                        <span class="label label-primary capitalize"><a href="allposts.php?category=<?= $post['category'] ?>"><?= $post['category'] ?></a></span>
                    </p>
                    <div class="well">
                        <?php if (strlen($post['content']) > 200): ?>
                            <p><?= substr($post['content'],0,200) ?>... <br /><br /><a href="post.php?userid=<?= $post['userid'] ?>&postid=<?= $post['postid'] ?>" class="text-info">Full Post Here</a></p>
                        <?php else: ?>
                            <p><?= $post['content'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>