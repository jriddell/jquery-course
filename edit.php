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
        ':fn' => htmlentities($_POST['first_name']),
        ':ln' => htmlentities($_POST['last_name']),
        ':em' => htmlentities($_POST['email']),
        ':he' => htmlentities($_POST['headline']),
        ':su' => htmlentities($_POST['summary']))
    );
    $profile_id = $_POST['profile_id'];
    $dbh->query('DELETE FROM Position WHERE profile_id='.$profile_id);
    $rank = 0;
    for ($i = 1; $i <= 9; $i++) {
        if (isset($_POST['year'.$i])) {
            $year = htmlentities($_POST['year'.$i]);
            print "year " . $year;
            $description = htmlentities($_POST['description'.$i]);
            $stmt = $dbh->prepare('INSERT INTO Position
                (profile_id, rank, year, description) 
            VALUES ( :pid, :rank, :year, :desc)');
            $stmt->execute(array(
                ':pid' => $profile_id,
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $description)
            );
            $rank++;
        }
    }
    $dbh->query('DELETE FROM Education WHERE profile_id='.$profile_id);
    for ($i = 1; $i <= 9; $i++) {
        if (isset($_POST['education_year'.$i])) {
            $education_year = htmlentities($_POST['education_year'.$i]);
            $education_institution = htmlentities($_POST['education_institution'.$i]);
            $instituion_id = get_institution_id($education_institution);
            $stmt = $dbh->prepare('INSERT INTO Education
                (profile_id, institution_id, rank, year) 
            VALUES ( :pid, :institution_id, :rank, :year)');
            $stmt->execute(array(
                ':pid' => $profile_id,
                ':institution_id' => $instituion_id,
                ':rank' => $i,
                ':year' => $education_year)
            );
        }
    }
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

<p>Education: <input type="button" value="+" id="addeducation" /></p>
<div id="educationforms">
<?php
    $sql = 'SELECT * FROM Profile,Education,Institution WHERE Education.profile_id='. $_GET['profile_id'].' AND Education.profile_id=Profile.profile_id AND Institution.institution_id=Education.institution_id ORDER BY Education.rank';
    $education_count = 1;
    foreach ($dbh->query($sql) as $row) {
?>
<div id="education<?=$education_count?>">
<p>Year: <input type="text" name="education_year<?=$education_count?>" value="<?=$row['year']?>" /> <input type="button" value="-" onclick="$('#education<?=$education_count?>').empty()" /></p> 
<p><input type="text" name="education_institution<?=$education_count?>" value="<?=$row['name']?>" class="institution" /></p>
</div>
<?php
        $education_count++;
    }
?>
</div>

<p>Position: <input type="button" value="+" id="addposition" /></p>
<div id="positionforms">
<?php
    $sql = 'SELECT * FROM Position WHERE profile_id='. $_GET['profile_id'].' ORDER BY Rank';
    $count = 1;
    foreach ($dbh->query($sql) as $row) {
?>
<div id="position<?=$count?>">
<p>Year: <input type="text" name="year<?=$count?>" value="<?=$row['year']?>" /> <input type="button" value="-" onclick="$('#position<?=$count?>').empty()" /></p> <p><textarea name="description<?=$count?>" rows="10" cols="100"><?=$row['description']?></textarea></p>
</div>
<?php
        $count++;
    }
?>
</div>

<p>
<input type="submit" value="Save Edit">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<script>
count = <?=$count?>;
$(document).ready(function() {
    $('#addposition').click(function() {
        count++;
        if (count > 9) {
            alert('Only 9 positions allowed');
            return false;
        }
        console.log('XXX Addposition clicked:'+count);
        $('#positionforms').append('<div id="position'+count+'" <p>Year: <input type="text" name="year'+count+'" /> <input type="button" value="-" onclick="$(\'#position'+count+'\').empty()" /></p>\
                                    <p><textarea name="description'+count+'" rows="10" cols="100"></textarea></p></div>');
    })
});


education_count = <?=$education_count?>;
$(document).ready(function() {
    $('#addeducation').click(function() {
        education_count++;
        if (education_count > 9) {
            alert('Only 9 institutions allowed');
            return false;
        }
        console.log('XXX Add institution clicked:'+education_count);
        $('#educationforms').append('<div id="education'+education_count+'"> <p>Year: <input type="text" name="education_year'+education_count+'" /> <input type="button" value="-" onclick="$(\'#education'+education_count+'\').empty()" /></p>\
                                    <p><input type="text" name="education_institution'+education_count+'" class="institution" /></p></div>');
    })
});

$(document).ready(function() {
    $('.institution').autocomplete({source: "schools.php"});
});

</script>

<?php
htmltail();
