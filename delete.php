<?php
require_once('common.php');
check_login();

if (isset($_POST['profile_id'])) {
    $result = $dbh->query('SELECT * FROM Profile WHERE profile_id='. $_POST['profile_id']);
    $record = $result->fetch();
    if (!$_SESSION['user_id'] == $record['user_id']) {
        $_SESSION['message'] =  "Trying to Delete a profile which does not belong to your login";
        return;
    }
    $result = $dbh->query('DELETE FROM Profile WHERE profile_id='. $_POST['profile_id']);
    $_SESSION['message'] = 'Profile Deleted';
    header("Location: index.php");
    return;
}

htmlhead();

$result = $dbh->query('SELECT * FROM Profile WHERE profile_id='. $_GET['profile_id']);
$record = $result->fetch();
?>
<h1>Deleteing Profile</h1>
<form method="post" action="delete.php">
<p>First Name: <?php print $record['first_name']; ?></p>
<p>Last Name: <?php print $record['last_name']; ?></p>
<input type="hidden" name="profile_id" value="<?php print $_GET['profile_id']; ?>"/>
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<?php
htmltail();
