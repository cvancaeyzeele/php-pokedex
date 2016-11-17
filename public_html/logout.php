<?php
session_start();
unset($_SESSION['user']);
unset($_SESSION['loggedin']);
unset($_SESSION['userrole']);
header("Location: index.php");