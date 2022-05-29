<?php 
require_once "pdo.php";
require_once "util.php";

session_start();

if ( ! isset($_SESSION['user_id'] ) && !isset($_SESSION['name']) ) {
    unset($_SESSION['name']);
    unset($_SESSION['user_id']);
    die('ACCESS DENIED');
}
if ( ! isset($_GET['profile_id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
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

$positions = loadPos($pdo, $_GET['profile_id']);


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
<body>
<div class="container">
<h1>Profile information</h1>
<p>First Name:
<?=$fname ?></p>
<p>Last Name:
<?=$lname ?></p>
<p>Email:
<?= $mail?></p>
<p>Headline:<br/>
<?=$head ?></p>
<p>Summary:<br/>
<?=$sum ?></p>
<?php
    if (count($positions)>0){
        echo ("<p>Position:<br/><ul>");
        foreach ($positions as $pos) {
            echo ('<li>' . $pos['year'] . ': ' . $pos['description'] . ' </li>');
        }
        echo ("</ul></p>");
    }

?>



<a href="index.php">Done</a>
</div>
</body>
</html>