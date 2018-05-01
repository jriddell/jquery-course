<?php
require_once('common.php');
check_login();

if (isset($_POST['first_name'])) {
    if (!isset($_POST['first_name']) || $_POST['first_name'] == "" ||
        !isset($_POST['last_name']) || $_POST['last_name'] == "" ||
        !isset($_POST['email']) || $_POST['email'] == "" ||
        !isset($_POST['headline']) || $_POST['headline'] == "" ||
        !isset($_POST['summary']) || $_POST['summary'] == "") {
        $_SESSION['message'] = 'All values are required';
        header("Location: add.php");
        return;
    }
    if (strpos($_POST['email'], '@') === FALSE) {
        $_SESSION['message'] = 'Email not valid';
        header("Location: add.php");
        return;
    }
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year'.$i])) { continue; }
        if (!isset($_POST['description'.$i])) { continue; }
        if (strlen($_POST['year'.$i]) == 0 || strlen($_POST['description'.$i]) == 0) {
            $_SESSION['message'] = 'Year and Description must be filled in' . 'year'.$i .$_POST['year'.$i]. 'description'.$i.$_POST['description'.$i];
            header("Location: add.php");
            return;
        }
        if (!is_numeric($_POST['year'.$i])) {
            $_SESSION['message'] = 'Year must be a number';
            header("Location: add.php");
            return;
        }
    }
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['education_year'.$i])) { continue; }
        if (!isset($_POST['education_institution'.$i])) { continue; }
        if (strlen($_POST['education_year'.$i]) == 0 || strlen($_POST['education_institution'.$i]) == 0) {
            $_SESSION['message'] = 'Education year and institution must be filled in: '.$i .' '.$_POST['education_year'.$i]. ' education_institution '.$i.$_POST['education_institution'.$i];
            header("Location: add.php");
            return;
        }
        if (!is_numeric($_POST['education_year'.$i])) {
            $_SESSION['message'] = 'Education year must be a number';
            header("Location: add.php");
            return;
        }
    }

    $stmt = $dbh->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary) 
        VALUES ( :uid, :fn, :ln, :em, :he, :su)');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => htmlentities($_POST['first_name']),
        ':ln' => htmlentities($_POST['last_name']),
        ':em' => htmlentities($_POST['email']),
        ':he' => htmlentities($_POST['headline']),
        ':su' => htmlentities($_POST['summary']))
    );
    $profile_id = $dbh->lastInsertId();
    $rank = 0;
    for ($i = 1; $i <= 9; $i++) {
        if (isset($_POST['year'.$i])) {
            $year = htmlentities($_POST['year'.$i]);
            $description = htmlentities($_POST['education_institution'.$i]);
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
    for ($i = 1; $i <= 9; $i++) {
        if (isset($_POST['education_year'.$i])) {
            print "<p>Inserting $i " . $_POST['education_institution'.$i];
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
    $_SESSION['message'] = 'Added';
    header("Location: index.php");
    return;
}


htmlhead();
if (isset($_SESSION['message'])) {
    print "<p style='color: green'>" . $_SESSION['message'] . "</p>\n";
    unset($_SESSION['message']);
}
?>

<h1>Adding Profile for UMSI</h1>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea></p>

<p>Education: <input type="button" value="+" id="addeducation" /></p>
<div id="educationforms">
<div id="education1">
<p>Year: <input type="text" name="education_year1" /> <input type="button" value="-" onclick="$('#education1').empty()" /></p>
<p><input type="text" name="education_institution1" class="institution" /></p>
</div>
</div>

<p>Position: <input type="button" value="+" id="addposition" /></p>
<div id="positionforms">
<div id="position1">
<p>Year: <input type="text" name="year1" /> <input type="button" value="-" onclick="$('#position1').empty()" /></p>
<p><textarea name="description1" rows="10" cols="100"></textarea></p>
</div>
</div>

<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<script>
count = 1;
$(document).ready(function() {
    $('#addposition').click(function() {
        count++;
        if (count > 9) {
            alert('Only 9 positions allowed');
            return false;
        }
        console.log('XXX Add position clicked:'+count);
        $('#positionforms').append('<div id="position'+count+'" <p>Year: <input type="text" name="year'+count+'" /> <input type="button" value="-" onclick="$(\'#position'+count+'\').empty()" /></p>\
                                    <p><textarea name="description'+count+'" rows="10" cols="100"></textarea></p></div>');
    })
});

education_count = 1;
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
