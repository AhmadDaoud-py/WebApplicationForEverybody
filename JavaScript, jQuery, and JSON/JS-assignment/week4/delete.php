<?php
require_once "pdo.php";
session_start();
if ( ! isset($_SESSION['name'] ) ) {
    unset($_SESSION['name']);
    die('ACCESS DENIED');
}
if(isset($_POST['cancel'])){
    header('Location:index.php');
    return;
}

if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['profile_id']));
    $_SESSION['successMSG'] = 'Profile deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that auto id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}


$stmt = $pdo->prepare("SELECT first_name, last_name, profile_id FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Could not load profile';
    header( 'Location: index.php' ) ;
    return;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahmad Daoud</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<div class="container">
<h1>Deleteing Profile</h1>
<form method="post" action="delete.php">
<p>First Name: <?=htmlentities($row['first_name']) ?></p>
<p>Last Name: <?=htmlentities($row['last_name']) ?></p>

<input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>"/>
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>

</html>

