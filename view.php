<?php
require_once('common.php');
htmlhead();
?>

<h1>Profile information</h1>

<?php
$result = $dbh->query('SELECT * FROM Profile WHERE profile_id='. $_GET['profile_id']);
$record = $result->fetch();

print "<p>First Name: " . $record['first_name'] . "</p>";
print "<p>Last Name: " . $record['last_name'] . "</p>";
print "<p>Email: " . $record['email'] . "</p>";
print "<p>Headline: " . $record['headline'] . "</p>";
print "<p>Summary: " . $record['summary'] . "</p>";

print "<div style='margin-left: 2em'>";
$sql = 'SELECT * FROM Position WHERE profile_id='. $_GET['profile_id'];
foreach ($dbh->query($sql) as $row) {
    print "<h3>Position</h3>";
    print "<p>Rank: " . $row['rank'] . "</p>\n";
    print "<p>Year: " . $row['year'] . "</p>\n";
    print "<p>Description: " . $row['description'] . "</p>\n";
}

?>
</div>

<?php
htmltail();
