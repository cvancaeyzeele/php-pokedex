<?php
    require 'connect.php';
    session_start();

    if (isset($_GET['sortby'])) {
        $sortby = filter_input(INPUT_GET, 'sortby', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $order;

        if (isset($_GET['order'])) {
            $order = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        if (isset($order) && isset($sortby)) {
            $postquery = "SELECT * FROM posts ORDER BY $sortby $order";
            $poststatement = $db->prepare($postquery);
            $poststatement->execute();
            $posts = $poststatement->fetchAll();
        } elseif (isset($sortby) && !isset($order)) {
            $postquery = "SELECT * FROM posts ORDER BY $sortby ASC";
            $poststatement = $db->prepare($postquery);
            $poststatement->execute();
            $posts = $poststatement->fetchAll();
        } else {
            $postquery = "SELECT * FROM posts ORDER BY datecreated DESC";
            $poststatement = $db->prepare($postquery);
            $poststatement->execute();
            $posts = $poststatement->fetchAll();
        }
    } else {
        $postquery = "SELECT * FROM posts ORDER BY datecreated DESC";
        $poststatement = $db->prepare($postquery);
        $poststatement->execute();
        $posts = $poststatement->fetchAll();
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
        <? include 'header.php'; ?>
        <div class="panel panel-default">
            <div class="btn-group dropdown">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Sort <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="allposts.php?sortby=title&order=asc" id="title">Title | A-Z</a></li>
                    <li><a href="allposts.php?sortby=title&order=desc" id="title">Title | Z-A</a></li>
                    <li><a href="allposts.php?sortby=datecreated&order=asc" id="date-created">Date Created | Oldest First</a></li>
                    <li><a href="allposts.php?sortby=datecreated&order=desc" id="date-created">Date Created | Newest First</a></li>
                    <li><a href="allposts.php?sortby=category" id="category">Category</a></li>
                </ul>
            </div>
            <?php foreach ($posts as $post): ?>
                <div class="post <?= $post['userid'] ?> <?= $post['category'] ?> <?= date( "m/d/y", strtotime($post['datecreated'])) ?>">
                    <h2 class="post-title"><a href="post.php?userid=<?= $post['userid'] ?>&postid=<?= $post['postid'] ?>"><?= $post['title'] ?></a></h2>
                    <p class="date"><?= date("F j, Y g:i a", strtotime($post['datecreated'])); ?></p>
                    <p class="category capitalize"><?= $post['category'] ?></p>
                    <?php if (strlen($post['content']) > 200): ?>
                        <p><?= substr($post['content'],0,200) ?>... <a href="post.php?userid=<?= $post['userid'] ?>&postid=<?= $post['postid'] ?>">Full Post Here</a></p>
                    <?php else: ?>
                        <p><?= $post['content'] ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>