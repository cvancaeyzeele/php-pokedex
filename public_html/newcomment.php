<?php
    require './../resources/library/connect.php';
    session_start();

    if (isset($_SESSION['loggedin']) && isset($_GET['id'])) {
        $userid = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $query = "SELECT * FROM users WHERE userid = :userid";
        $statement = $db->prepare($query);
        $statement->bindValue(':userid', $userid);
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
        <div class="panel panel-default">
            <form method="post" action="" class="form">

            </form>
        </div>
    </body>
</html>