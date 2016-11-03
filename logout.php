<?php
session_start();
unset($_SESSION['user']);
unset($_SESSION['loggedin']);
header("Location: index.php");