<?php
    session_start();

    // Sanitize GET ID
    $pokemon_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    // URL for PokeAPI
    $pokeapi_url = 'http://pokeapi.co/api/v2/pokemon/' . $pokemon_id . '/';

    // Decode JSON into an array
    $pokemon = file_get_contents($pokeapi_url);
    $pokemon = json_decode($pokemon, true);
?>
<!doctype html>
<html lang="en">
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
                <li class="pokemon-card">
                    <img src="img/sprites/<?= $pokemon['id'] ?>.png" />
                    <div class="inlinetext">
                        <h2 class="name"><?= $pokemon['name'] ?> <small><?= $pokemon['id'] ?></small></h2>
                        <div class="col-md-6">
                            <h4>Base Stats</h4>
                            <? foreach ($pokemon['stats'] as $stat): ?>
                                <p><?= $stat['stat']['name'] ?> - <?= $stat['base_stat'] ?></p>
                            <? endforeach; ?>
                        </div>
                        <div class="col-md-6">
                            <h4>Abilities</h4>
                            <? foreach ($pokemon['abilities'] as $abilities): ?>
                                <p><?= $abilities['ability']['name'] ?></p>
                            <? endforeach; ?>
                        </div>
                        <div class="col-md-6">
                            <h4>Moves</h4>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </body>
</html>
