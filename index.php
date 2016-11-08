<?php
    include 'apicaching.php';

    session_start();

    $pokeapi_url = 'http://pokeapi.co/api/v2/pokemon/?limit=718';

    // Decode JSON into an array
    $pokearray = json_cached_api_results(null, null, $pokeapi_url);
    //$pokearray = json_decode($pokearray, true);
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pok&eacute;Lookup</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/latest/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="css/main-styles.css" />
        <script
        src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </head>
    <body>
        <? include 'header.php'; ?>
        <div id="pokemon-list">
            <ul>
                <? foreach ($pokearray['results'] as $pokemon): ?>
                    <a href="pokemon.php?id=<?= substr(trim($pokemon['url'], '/'), strrpos(trim($pokemon['url'], '/'), '/')+1) ?>">
                        <li class="pokemon-card">
                            <img src="img/sugimori/<?= substr(trim($pokemon['url'], '/'), strrpos(trim($pokemon['url'], '/'), '/')+1) ?>.png" />
                            <div class="inlinetext">
                                <h4 class="name"><?= $pokemon['name'] ?></h4>
                                <p class="number"><?= substr(trim($pokemon['url'], '/'), strrpos(trim($pokemon['url'], '/'), '/')+1) ?></p>
                            </div>
                        </li>
                    </a>
                <? endforeach; ?>
            </ul>
        </div>
    </body>
</html>
