<?php

session_start();
$salt = 'XyZzy12*_';
$dbh = new PDO('mysql:host=localhost;dbname=misc', 'fred', 'zap');

function htmlhead() {
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Jonathan Riddell - WA4E Javascript Week 3</title>
<style>
table, th, td { border: 1px solid black; border-collapse: collapse; padding: 1ex; }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
<div class="container">
<?php
}

function htmltail() {
?>
</div>
</body>
</html>
<?php
}

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        die('Not logged in');
    }
}
