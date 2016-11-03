<div id="navbar">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Pok&eacute;Lookup</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'index.php') { echo 'active'; } ?>"><a href="index.php">Home</a></li>
                <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'typechecker.php') { echo 'active'; } ?>"><a href="typechecker.php">Type Checker</a></li>
                <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'items.php') { echo 'active'; } ?>"><a href="items.php">Items</a></li>
                <li class="<?php if(basename($_SERVER['PHP_SELF']) == 'moves.php') { echo 'active'; } ?>"><a href="moves.php">Moves</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="logout.php">Log Out</a></li>
                <?php else: ?>
                    <li><a href="login.php">Log In</a></li>
                <?php endif; ?>
            </ul>
            <?php if (isset($_SESSION['loggedin'])): ?>
                <p class="navbar-text navbar-right"><a href="profile.php?id=<?= $_SESSION['user'] ?>">Signed in as <?= $_SESSION['user'] ?></a></p>
            <?php else: ?>
                <p class="navbar-text navbar-right">Not logged in</p>
            <?php endif; ?>
        </div>
    </nav>
</div>