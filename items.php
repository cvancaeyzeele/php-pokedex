<?php
    include 'apicaching.php';

    session_start();

    $pokeapi_url = 'http://pokeapi.co/api/v2/item/?limit=2000';

    // Decode JSON into an array
    $pokearray = json_cached_api_results_items(null, null, $pokeapi_url);
?>
<!doctype html>
<html lang="en">
    <head>
        <title>Pok&eacute;Lookup</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/latest/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="css/main-styles.css" />
        <link rel="stylesheet" type="text/css" href="css/table-styles.css" />
    </head>
    <body>
        <? include 'header.php'; ?>
        <div class="panel panel-default">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pokearray['results'] as $item): ?>
                    <tr>
                        <td class="item-name"><?= str_replace("-", " ", $item['name']) ?></td>
                        <td class="item-url"><?= $item['url'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
