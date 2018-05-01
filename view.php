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
$sql = 'SELECT * FROM Institution,Profile,Education WHERE Institution.institution_id=Education.institution_id AND Profile.profile_id=Education.profile_id AND Profile.profile_id='. $_GET['profile_id'].' ORDER BY Rank';
print "<h3>Education</h3>";
foreach ($dbh->query($sql) as $row) {
    print "<ul>";
    print "<li>". $row['year'] . ": " . $row['name'] . "</li>\n";
    print "</ul>";
}
print "</div>";

print "<div style='margin-left: 2em'>";
$sql = 'SELECT * FROM Position WHERE profile_id='. $_GET['profile_id'].' ORDER BY Rank';
print "<h3>Position</h3>";
foreach ($dbh->query($sql) as $row) {
    print "<ul>";
    print "<li>". $row['year'] . ": " . $row['description'] . "</li>\n";
    print "</ul>";
}

?>
</div>

<?php
htmltail();
