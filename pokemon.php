<?php
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
    </head>
    <body>
    <? include 'header.php'; ?>
        <div id="pokemon-list">
            <ul>
                <li class="pokemon-card">
                    <img src="img/sprites/<?= $pokemon['id'] ?>.png" />
                    <div class="inlinetext">
                        <h4 class="name"><?= $pokemon['name'] ?></h4>
                        <p class="number"><?= $pokemon['id'] ?></p>
                    </div>
                </li>
            </ul>
        </div>
    </body>
</html>
