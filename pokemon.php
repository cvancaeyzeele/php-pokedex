<?php
    session_start();

    // Sanitize GET ID
    $pokemon_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    // URL for PokeAPI
    $pokeapi_url = 'http://pokeapi.co/api/v2/pokemon/' . $pokemon_id . '/';
    $pokeapi_url_species = "http://pokeapi.co/api/v2/pokemon-species/" . $pokemon_id . '/';

    // Decode JSON into an array
    $pokemon = file_get_contents($pokeapi_url);
    $pokemon = json_decode($pokemon, true);

    $pokemon_species = file_get_contents($pokeapi_url_species);
    $pokemon_species = json_decode($pokemon_species, true);

    // get evolution chain if applicable
    $evolution_chain = $pokemon_species['evolution_chain']['url'];
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
        <script src="js/pokemon-page.js"></script>
    </head>
    <body>
    <? include 'header.php'; ?>
        <div id="pokemon-list">
            <ul>
                <li class="pokemon-card">
                    <select class="form-control gen-value" onChange="getGenValue(this)">
                        <option value="1">Gen. 1</option>
                        <option value="2">Gen. 2</option>
                        <option value="3">Gen. 3</option>
                        <option value="4">Gen. 4</option>
                        <option value="5">Gen. 5</option>
                        <option value="6">Gen. 6</option>
                    </select>
                    <div class="sprites">
                        <img src="img/xy-animated/<?= $pokemon['id'] ?>.gif" class="sprite" />
                        <img src="img/xy-animated-shiny/<?= $pokemon['id'] ?>.gif" class="sprite-shiny" />
                    </div>
                    <div class="inlinetext">
                        <h2 class="name"><?= $pokemon['name'] ?> <small><?= $pokemon['id'] ?></small></h2>
                        <div class="types">
                            <?php foreach ($pokemon['types'] as $type): ?>
                                <img src="img/types/<?= $type['type']['name'] ?>.gif" alt="<?= $type['type']['name'] ?>" class="type" />
                            <?php endforeach; ?>
                        </div>
                        <div class="flavor-text">
                            <h4>Description</h4>
                            <?php foreach ($pokemon_species['flavor_text_entries'] as $flavor_text): ?>
                                <?php if ($flavor_text['version']['name'] == "y" && $flavor_text['language']['name'] == "en"): ?>
                                    <p><?= $flavor_text['flavor_text'] ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="col-md-6">
                            <h4>Base Stats</h4>
                            <? foreach ($pokemon['stats'] as $stat): ?>
                                <p><?= $stat['stat']['name'] ?> - <?= $stat['base_stat'] ?></p>
                            <? endforeach; ?>
                        </div>
                        <div class="col-md-6">
                            <h4>Abilities</h4>
                            <? foreach ($pokemon['abilities'] as $abilities): ?>
                                <p><?= str_replace("-", " ", $abilities['ability']['name']) ?></p>
                            <? endforeach; ?>
                        </div>
                        <div class="col-md-6">
                            <h4>Egg Groups</h4>
                            <? foreach ($pokemon_species['egg_groups'] as $egg_groups): ?>
                                <p><?= $egg_groups['name'] ?></p>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="inlinetext">

                    </div>
                    <h4 class="inlinetext">Moves</h4>
                    <table class="table-hover table moves">
                        <thead>
                        <tr>
                            <td>Move</td>
                            <td>Learn Method</td>
                            <td>Level Learned At</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pokemon['moves'] as $move): ?>
                            <?php foreach ($move['version_group_details'] as $moveversion): ?>
                                <?php if ($moveversion['version_group']['name'] == "x-y"): ?>
                                    <tr>
                                        <td><?= str_replace("-", " ", $move['move']['name']) ?></td>
                                        <td><?= str_replace("-", " ", $moveversion['move_learn_method']['name']) ?></td>
                                        <?php if ($moveversion['move_learn_method']['name'] == "level-up"): ?>
                                            <td><?= $moveversion['level_learned_at'] ?></td>
                                        <?php else: ?>
                                            <td>N/A</td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </li>
            </ul>
        </div>
    </body>
</html>
