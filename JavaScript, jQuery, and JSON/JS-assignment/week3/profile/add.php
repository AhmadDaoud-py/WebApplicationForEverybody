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
//handle the incoming data
if(isset($_POST['first_name'])&& isset($_POST['last_name'])&& isset($_POST['email'])&& isset($_POST['headline'])&& isset($_POST['summary'])){
    $msg = validateProfile();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location:add.php');
        return;
    }

    $msg = validatePos();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location:add.php');
        return;
    }

    
        $sql = "INSERT INTO profile ( user_id, first_name , last_name , email, headline, summary)
                  VALUES (:id, :fn, :ln, :mail, :hd, :sum)";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':id' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':mail' => $_POST['email'],
            ':hd' => $_POST['headline'],
            ':sum' => $_POST['summary']));


            $profile_id = $pdo-> lastInsertId();

            // insert position information

            $rank = 1;
            for($i=1; $i<=9; $i++) {
                if(!isset($_POST['year'.$i])) continue;
                if(!isset($_POST['desc'.$i])) continue;
                $year = $_POST['year'.$i];
                $desc = $_POST['desc'.$i];

                $stmt = $pdo->prepare('INSERT INTO position (profile_id, rank, year, description) values (:pid ,:rank, :year, :desc)');
                $stmt->execute(array(':pid' => $profile_id , ':rank' => $rank, ':year' => $year, ':desc' => $desc));
                $rank++;


            }
    
            $_SESSION['successMSG'] = "Profile added";
            header('Location:index.php');
            return;
            
        

}

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
    echo "<h1>Adding Profile for ";
    echo htmlentities($_SESSION['name']);
    echo "</h1>\n";
}

flashMessage();



?>
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
<textarea name="summary" rows="8" cols="80"></textarea>
<p>
    Position : <input type="button" id = "addPos" value = "+">
    <div id="position_fields">
    </div>
</p>
<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
<script>
    countPos = 0;
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
