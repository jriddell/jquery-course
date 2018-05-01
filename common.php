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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css"> 

  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
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

function get_institution_id($education_institution) {
    $dbh = new PDO('mysql:host=localhost;dbname=misc', 'fred', 'zap');
    $sql = 'SELECT count(*) FROM Institution WHERE name=\''. $education_institution .'\'';
    $result = $dbh->query($sql);
    if ($result && $result->fetchColumn() == 1) {
        $result2 = $dbh->query('SELECT * FROM Institution WHERE name=\''. $education_institution .'\'');
        $row = $result2->fetch();
        return $row['institution_id'];
    } else {
        $stmt = $dbh->prepare('INSERT INTO Institution (name) VALUES (:name)');
        $stmt->execute(array(':name' => $education_institution));
        return $dbh->lastInsertId();
    }
}
