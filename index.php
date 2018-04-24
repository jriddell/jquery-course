<?php
require_once('common.php');
htmlhead();
?>

<h1>Jonathan Riddell's CV Library</h1>

<?php
if (isset($_SESSION['message'])) {
    print "<p style='color: green'>" . $_SESSION['message'] . "</p>\n";
    unset($_SESSION['message']);
}

if (isset($_SESSION['user_id'])) {
    print "<p><a href='logout.php'>Log out</a></p>\n";
} else {
    print "<p><a href='login.php'>Please log in</a></p>\n";
}

?>

<table style="border: 1px solid black">
<tr><th>Name</th><th>Headline</th>
<?php
if (isset($_SESSION['user_id'])) {
    print "<th>Action</th>";
}
?>
<?php

foreach ($dbh->query('SELECT * FROM Profile') as $row) {
    print "<tr>\n";
    print "<td><a href='view.php?profile_id=" . $row['profile_id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</a></td>\n";
    print "<td>" . $row['headline'] . "</td>\n";
    if (isset($_SESSION['user_id'])) {
        print "<td><a href='edit.php?profile_id=" . $row['profile_id'] . "'>Edit</a> <a href='delete.php?profile_id=" . $row['profile_id'] . "'>Delete</a></td>\n";
    }
    print "</tr>\n";
}
?>
</table>
<?php
if (isset($_SESSION['user_id'])) {
    print "<p><a href='add.php'>Add New Entry</a></p>\n";
}

htmltail();
