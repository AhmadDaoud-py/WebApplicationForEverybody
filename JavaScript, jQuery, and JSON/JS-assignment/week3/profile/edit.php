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


if ( ! isset($_GET['profile_id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
  }



  if(isset($_POST['first_name'])&& isset($_POST['last_name'])&& isset($_POST['email'])&& isset($_POST['headline'])&& isset($_POST['summary'])){
    $msg = validateProfile();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location:edit.php?profile_id='.$_REQUEST['profile_id']);
        return;
    }
    $msg = validatePos();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location:edit.php?profile_id='.$_REQUEST['profile_id']);
        return;
    }

    
        $sql = "UPDATE profile SET first_name = :fn ,last_name =:ln, email = :mail ,headline =:hd ,summary = :sum Where profile_id = :profile_id";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['first_name'],
            ':mail' => $_POST['email'],
            ':hd' => $_POST['headline'],
            ':sum' => $_POST['summary'],
            ':profile_id' => $_POST['profile_id']));
    
            

        
        $stmt = $pdo ->prepare('DELETE FROM position WHERE profile_id = :pid');
        $stmt ->execute(array (':pid' => $_REQUEST['profile_id']));

        $rank = 1;
            for($i=1; $i<=9; $i++) {
                if(!isset($_POST['year'.$i])) continue;
                if(!isset($_POST['desc'.$i])) continue;
                $year = $_POST['year'.$i];
                $desc = $_POST['desc'.$i];

                $stmt = $pdo->prepare('INSERT INTO position (profile_id, rank, year, description) values (:pid ,:rank, :year, :desc)');
                $stmt->execute(array(':pid' => $_REQUEST['profile_id'] , ':rank' => $rank, ':year' => $year, ':desc' => $desc));
                $rank++;


            }

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
<p>
<p>
    Position : <input type="button" id = "addPos" value = "+">
    <div id="position_fields">
    </div>
</p>
<?php 
$positions = loadPos($pdo, $_REQUEST['profile_id']);
$Rcount = count($positions);
$countPos=0;
    if($Rcount >0){
        
        foreach($positions as $posit){
            
            echo ('<div id="position'.$countPos.'" >
            <p>Year: <input type="text"name="year'.$countPos.'" value = "'.$posit['year'].'"/>
            <input type="button" value = "-" 
            onclick = "$(\'#position'.$countPos.'\').remove(); countPos--; return false;"></p> 
            <textarea name="desc'.$countPos.'" rows = "8" cols = "80">'.$posit['description'].'</textarea>
            </div>');
            $countPos++;
        }
    }
?>
<input type="submit" value="Save">
<input type="hidden" name="profile_id" value="<?=$profile_id?>">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
<script>
    countPos = <?=$countPos?>;
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
    })
</script>
</div>
</body>
</html>