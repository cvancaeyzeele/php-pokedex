<?php
  // URL for PokeAPI
  $pokeapi_url = 'http://pokeapi.co/api/v2/pokemon/?limit=718';

  // Get JSON data from PokeAPI
  $json_data = file_get_contents($pokeapi_url);

  // Decode JSON into an array
  $pokearray = json_decode($json_data, true);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Pok&eacute;Lookup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/latest/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/main-styles.css" />
  </head>
  <body>
    <div id="navbar">
      <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="#">Pok&eacute;Lookup</a>
          </div>
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">Type Checker</a></li>
            <li><a href="#">Items</a></li>
            <li><a href="#">Moves</a></li>
          </ul>
        </div>
      </nav>
    </div>

    <div id="pokemon-list">
      <? foreach ($pokearray['results'] as $pokemon): ?>
        <a href="pokemon.php?id=<?= substr(trim($pokemon['url'], '/'), strrpos(trim($pokemon['url'], '/'), '/')+1) ?>">
          <li class="pokemon-card">
            <img src="img/sprites/<?= substr(trim($pokemon['url'], '/'), strrpos(trim($pokemon['url'], '/'), '/')+1) ?>.png" />
            <div class="inlinetext">
              <h4 class="name"><?= $pokemon['name'] ?></h4>
              <p class="number"><?= substr(trim($pokemon['url'], '/'), strrpos(trim($pokemon['url'], '/'), '/')+1) ?></p>
            </div>
          </li>
        </a>
      <? endforeach; ?>
    </div>
  </body>
</html>
