<?php
require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['success'] ) ) {
    unset($_SESSION['success']);
    die('ACCESS DENIED');
}
if(isset($_POST['cancel'])){
    header('Location:index.php');
    return;
}

//_______________________________

if(isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage']))
{

  if(strlen($_POST['make'])<1 || strlen($_POST['model'])< 1|| strlen($_POST['year']) < 1|| strlen($_POST['mileage']) < 1 ){
      $_SESSION['error'] ="All fields are required";
      error_log("Wrong inputs ".$_SESSION['account'].isset($_SESSION['error']));
      header("Location: edit.php?auto_id=".$_POST['auto_id']);
      return;

  }

    if( !is_numeric($_POST['mileage'])){
        $_SESSION['error'] ="Mileage must be numeric";
        error_log("Wrong inputs ".$_SESSION['account'].isset($_SESSION['error']));
        header("Location: edit.php?auto_id=".$_POST['auto_id']);
        return;

    }
    if( !is_numeric($_POST['year'])){
        $_SESSION['error'] ="Year must be numeric";
        error_log("Wrong inputs ".$_SESSION['account'].isset($_SESSION['error']));
        header("Location: edit.php?auto_id=".$_POST['auto_id']);
        return;

    }

    else{
    $sql = "UPDATE autos SET make = :make, model = :model, year = :year, mileage = :mileage WHERE auto_id = :auto_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':auto_id' => $_POST['auto_id']));
        error_log("Data is successfuly updated".$_SESSION['account']);
        $_SESSION['successMSG'] = "Record edited";
        header('Location:index.php');
        return;
    }
}


//_________________________
$stmt = $pdo->prepare("SELECT * FROM autos where auto_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$mk = htmlentities($row['make']);
$mdl = htmlentities($row['model']);
$y = htmlentities($row['year']);
$mile = htmlentities($row['mileage']);
$auto_id = $row['auto_id'];
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header( 'Location: index.php' ) ;
    return;
}

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 <title>Ahmad Daoud</title>
 <?php require_once "bootstrap.php"; ?>
 </head>
 <body>
 <div class="container">
<h1>Editing Automobile</h1>

 <?php


 if ( isset($_SESSION['error']) ) {

     echo('<p style="color: red;">'.$_SESSION['error']."</p>\n");
     unset($_SESSION['error']);
 }


 ?>
 <form method="post">
 <p>Make:
 <input type="text" name="make" value="<?=$mk?>" size="60"/></p>
 <p>Model:
 <input type="text" name="model" value="<?=$mdl?>"size="60"/></p>
 <p>Year:
 <input type="text" name="year" value="<?= $y?>"/></p>
 <p>Mileage:
 <input type="text" name="mileage" value="<?=$mile?>"/></p>
 <input type="hidden" name="auto_id" value="<?=$auto_id?>">
 <input type="submit" value="Save">
 <input type="submit" name="cancel" value="cancel">
 </form>

 </div>
 </body>
 </html>
