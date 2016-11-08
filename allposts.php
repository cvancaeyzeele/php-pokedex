<?php
    require 'connect.php';
    session_start();

    $page = 0;

    if (isset($_GET['page'])) {
        $pagevalue = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);

        if (isset($pagevalue)) {
            $page = $pagevalue;
        }
    }

    $postquery = "SELECT * FROM posts ORDER BY datecreated DESC LIMIT :start, 10";
    $poststatement = $db->prepare($postquery);
    $poststatement->bindValue(':start', $page, PDO::PARAM_INT);
    $poststatement->execute();
    $posts = $poststatement->fetchAll();

    $page += 10;
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
            <?php foreach ($posts as $post): ?>
                <div class="post <?= $post['userid'] ?> <?= $post['category'] ?> <?= date( "m/d/y", strtotime($post['datecreated'])) ?>">
                    <h2><a href="post.php?userid=<?= $post['userid'] ?>&postid=<?= $post['postid'] ?>"><?= $post['title'] ?></a></h2>
                    <p><?= date("F j, Y g:i a", strtotime($post['datecreated'])); ?></p>
                    <?php if (strlen($post['content']) > 200): ?>
                        <p><?= substr($post['content'],0,200) ?>... <a href="post.php?userid=<?= $post['userid'] ?>&postid=<?= $post['postid'] ?>">Full Post Here</a></p>
                    <?php else: ?>
                        <p><?= $post['content'] ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <nav aria-label="...">
                <ul class="pager">
                    <?php if ($page != 10): ?>
                        <li class="previous"><a href="allposts.php?page=<?= $page - 20 ?>"><span aria-hidden="true">&larr;</span> Newer</a></li>
                    <?php endif; ?>
                    <?php if (count($posts) >= 10): ?>
                        <li class="next"><a href="allposts.php?page=<?= $page ?>">Older <span aria-hidden="true">&rarr;</span></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </body>
</html>