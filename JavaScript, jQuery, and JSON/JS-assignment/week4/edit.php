<?php 
require_once "pdo.php";
require_once "util.php";

session_start();

if ( ! isset($_SESSION['user_id'] ) && !isset($_SESSION['name']) ) {
    unset($_SESSION['name']);
    unset($_SESSION['user_id']);
    die('ACCESS DENIED');
}
if(isset($_POST['cancel'])){
    header('Location:index.php');
    return;
}


if ( ! isset($_REQUEST['profile_id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
  }
//load profile in question
$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :prof AND user_id = :user_id");
$stmt->execute(array(':prof' => $_REQUEST['profile_id'], 'user_id' => $_SESSION['user_id']));
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if($profile === false){
    $_SESSION['error']="Could not load profile";
    header('Location:index.php');
    return;
}
// handle incoming data
  if(isset($_POST['first_name'])&& isset($_POST['last_name'])&& isset($_POST['email'])&& isset($_POST['headline'])&& isset($_POST['summary'])){
    //validateProfile
    $msg = validateProfile();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location:edit.php?profile_id='.$_REQUEST['profile_id']);
        return;
    }
    //validate educations
    $msg = validateEdu();
    
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location:edit.php?profile_id='.$_REQUEST['profile_id']); 
        return;
    }

    //validatePos
    $msg = validatePos();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location:edit.php?profile_id='.$_REQUEST['profile_id']);
        return;
    }

        //updated profile
        $sql = "UPDATE profile SET first_name = :fn ,last_name =:ln, email = :mail ,headline =:hd ,summary = :sum Where profile_id = :profile_id";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['first_name'],
            ':mail' => $_POST['email'],
            ':hd' => $_POST['headline'],
            ':sum' => $_POST['summary'],
            ':profile_id' => $_POST['profile_id']));
    
            

        //delete the old position
        $stmt = $pdo ->prepare('DELETE FROM position WHERE profile_id = :pid');
        $stmt ->execute(array (':pid' => $_REQUEST['profile_id']));


            // insert into position table

        insertPositions($pdo, $_REQUEST['profile_id']);

        //delete the old education
        $stmt = $pdo ->prepare('DELETE FROM education WHERE profile_id = :pid');
        $stmt ->execute(array (':pid' => $_REQUEST['profile_id']));


            // insert into position table

            insertEducations($pdo, $_REQUEST['profile_id']);
       

            $_SESSION['successMSG'] = "Profile updated ";
            header('Location:index.php');
            return;

}

  
$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$fname = htmlentities($row['first_name']);
$lname = htmlentities($row['last_name']);
$mail = htmlentities($row['email']);
$head = htmlentities($row['headline']);
$sum = htmlentities($row['summary']);
$profile_id = $row['profile_id'];
if ( $row === false ) {
    $_SESSION['error'] = 'Could not load profile';
    header( 'Location: index.php' ) ;
    return;
}


//load the positions rows





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "bootstrap.php" ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahmad Daoud</title>
</head>
<body>
<div class="container">

<?php
if ( isset($_SESSION['name']) ) {
    echo "<h1>Editing Profile for ";
    echo htmlentities($_SESSION['name']);
    echo "</h1>\n";
}

flashMessage();



?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" value=<?=$fname ?> size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" value=<?=$lname ?>  size="60"/></p>
<p>Email:
<input type="text" name="email" value=<?=$mail ?>  size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" value=<?=$head ?>  size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8"  cols="80"><?= $sum ?></textarea>
</p>

<?php 
$positions = loadPos($pdo, $_REQUEST['profile_id']);
$educations = loadEdu($pdo, $_REQUEST['profile_id']);

$Rcount = count($positions);
$EduCount = count($educations);

$countPos=1;
$countEdu = 1;
echo('<p>Education : <input type="button" id = "addEdu" value = "+">'."\n");
echo('<div id="education_fields">'."\n");
if($EduCount >0)
foreach($educations as $edus){
    
    echo('<div id="edu'.$EduCount.'">');
    echo ('<p>Year: <input type="text"name="edu-year'.$EduCount.'" value = "'.$edus['year'].'"/>
    <input type="button" value = "-" 
    onclick = "$(\'#edu'.$EduCount.'\').remove(); countEdu--; return false;"></p> 
    <p> School: <input type="text" size = "80" name="edu-school'.$EduCount.'" class = "school" value = "'.htmlentities($edus['name']).'"/>
    </p>');
    echo ("\n"."</div>"."\n");
    $countEdu++;
}

echo ('</div></p>'."\n");

echo('<p>Position : <input type="button" id = "addPos" value = "+" >'."\n");
echo ('<div id="position_fields">'."\n");
    if($Rcount >0){
        
        foreach($positions as $posit){
            
            echo ('<div id="position'.$countPos.'" >
            <p>Year: <input type="text"name="year'.$countPos.'" value = "'.$posit['year'].'"/>
            <input type="button" value = "-" 
            onclick = "$(\'#position'.$countPos.'\').remove(); countPos--; return false;"></p> 
            <textarea name="desc'.$countPos.'" rows = "8" cols = "80">'.htmlentities($posit['description']).'</textarea>
            </div>');
            $countPos++;
            
        }
    }

    echo ('</div></p>'."\n");
?>
<input type="submit" value="Save">
<input type="hidden" name="profile_id" value="<?=$profile_id?>">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
<script>
    countPos = <?=$countPos-1?>;
    countEdu = <?=$countEdu-1?>;
    $(document).ready(function(){
        console.log("Document is ready");
        $('#addPos').click(function(event) {
            event.preventDefault();
            if(countPos >= 9){
                alert("Maximum of nine position entries exceeded");
                return;
            }
            countPos++; //
            console.log("Adding position"+countPos);
            $('#position_fields').append('<div id="position'+countPos+'" > \
            <p>Year: <input type="text"name="year'+countPos+'" value = ""/> \
            <input type="button" value = "-" \
            onclick = "$(\'#position'+ countPos +'\').remove(); countPos--; return false;"></p> \
            <textarea name="desc'+countPos+'" rows = "8" cols = "80"></textarea>\
            </div>');

        })

    $('#addEdu').click(function(event){
        event.preventDefault();
        if(countEdu >= 9){
                alert("Maximum of nine education entries exceeded");
                return;
        }
        countEdu++;
        console.log('Adding education'+countEdu);

        $('#education_fields').append('<div id="edu'+countEdu+'"> \
        <p>Year: <input type="text" name="edu-year'+countEdu+'" value = ""/>\
            <input type="button" value = "-" \
            onclick = "$(\'#edu'+countEdu+'\').remove(); countEdu--; return false;"></p> \
            <p> School: <input type="text" size = "80" name="edu-school'+countEdu+'" class = "school" value = ""/>\
            </p>\
            </div>');

        // ________________
        // var source = $('#edu-template').html();
        // //grap some html with hot spots and insert into the dom
        // $('education_fields').append(source.replace(/@COUNT@/g,countEdu));
        // ____________________

        //add event handler to add new ones
        $('.school').autocomplete({
            source : function (data, response){
                $.ajax({
                    url : 'school.php',
                    method : 'GET',
                    dataType : 'json',
                    data:{
                        term : data.term
                    },
                    success : function (result) {
                        response(result);
                    }
                });
            }
        });
    })
    })
</script>
<!--HTML with substitution hot spots -->

<!-- <script id="edu-template" type="text">
    <div id="edu@COUNT@">
    <p>Year: <input type="text"name="edu-year@COUNT@" value = ""/>
            <input type="button" value = "-" 
            onclick = "$('#edu@COUNT@').remove(); countEdu--; return false;"></p> 
            <p> School: <input type="text" size = "80" name="edu-school@COUNT@" class = "school" value = ""/>
            </p>
            </div>
</script> -->

</div>
</body>
</html>