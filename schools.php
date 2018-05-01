<?php
require_once('common.php');
check_login();

$sql = 'SELECT * FROM Institution WHERE name LIKE \''. $_GET['term'].'%\'';

$institutions = array();
foreach ($dbh->query($sql) as $row) {
    $institutions[] = $row['name'];
}
print json_encode($institutions, JSON_PRETTY_PRINT);
