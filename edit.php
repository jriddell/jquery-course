<?php
require_once('common.php');
check_login();

if (isset($_POST['profile_id'])) {
    if (!isset($_POST['first_name']) || $_POST['first_name'] == "" ||
        !isset($_POST['last_name']) || $_POST['last_name'] == "" ||
        !isset($_POST['email']) || $_POST['email'] == "" ||
        !isset($_POST['headline']) || $_POST['headline'] == "" ||
        !isset($_POST['summary']) || $_POST['summary'] == "") {
        $_SESSION['message'] = 'Not all fields set';
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }

    if (strpos($_POST['email'], '@') === FALSE) {
        $_SESSION['message'] = 'Email not valid';
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }

    $stmt = $dbh->prepare('UPDATE Profile'.
        ' SET user_id = :uid, first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su'.
        ' WHERE profile_id='.$_POST['profile_id']);
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
    );
    $_SESSION['message'] = 'Profile Edited';
    header("Location: index.php");
    return;
}

if (isset($_GET['profile_id'])) {
    $result = $dbh->query('SELECT * FROM Profile WHERE profile_id='. $_GET['profile_id']);
    $record = $result->fetch();
}

htmlhead();
if (isset($_SESSION['message'])) {
    print "<p style='color: green'>" . $_SESSION['message'] . "</p>\n";
    unset($_SESSION['message']);
}
?>

<h1>Editing Profile for UMSI</h1>
<form method="post" action="edit.php">
<p>First Name:
<input type="text" name="first_name" size="60"
value="<?php print $record['first_name']; ?>"
/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"
value="<?php print $record['last_name']; ?>"
/></p>
<p>Email:
<input type="text" name="email" size="30"
value="<?php print $record['email']; ?>"
/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"
value="<?php print $record['headline']; ?>"
/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80">
<?php print $record['summary']; ?></textarea>
<p>
<input type="hidden" name="profile_id"
value="<?php print $record['profile_id']; ?>"
/>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<?php
htmltail();
