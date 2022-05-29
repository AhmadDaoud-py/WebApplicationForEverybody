<?php 
require_once "pdo.php";
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
    if(strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1 || strlen($_POST['email'])<1 || strlen($_POST['headline'])<1 || strlen($_POST['summary'])<1 ){
        $_SESSION['error'] ="All fields are required";
      error_log("Wrong inputs ".$_SESSION['name'].isset($_SESSION['error']));
      header("Location: edit.php?profile_id=".$_POST['profile_id']);
      return;
    }
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $_SESSION['error'] ="Email address must contain @";
      error_log("Wrong Email ".$_SESSION['name'].isset($_SESSION['error']));
      header("Location: edit.php?profile_id=".$_POST['profile_id']);
      return;
    }
    else{
        $sql = "UPDATE profile SET first_name = :fn ,last_name =:ln, email = :mail ,headline =:hd ,summary = :sum Where profile_id = :profile_id";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['first_name'],
            ':mail' => $_POST['email'],
            ':hd' => $_POST['headline'],
            ':sum' => $_POST['summary'],
            ':profile_id' => $_POST['profile_id']));
    
            $_SESSION['successMSG'] = "Profile updated";
            header('Location:index.php');
            return;
        }

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




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "bootstrap.php" ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>
<body>
<div class="container">
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Resume Registry</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li class="dropdown">
          

    </div>
  </div>
</nav>
<?php
if ( isset($_SESSION['name']) ) {
    echo "<h1>Editing Profile for ";
    echo htmlentities($_SESSION['name']);
    echo "</h1>\n";
}

if ( isset($_SESSION['error']) ) {

    echo('<p style="color: red;">'.$_SESSION['error']."</p>\n");
    unset($_SESSION['error']);
}



?>
<form class="form-horizontal" method="post">
<div class="form-group">
      <label class="control-label col-sm-2" for="fname">First name:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value=<?=$fname ?> name="first_name">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="lname">Last name:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value=<?=$lname ?> name="last_name">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="mail">Email:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value=<?=$mail ?> name="email">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="headline">Headline:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" value=<?=$head ?> name="headline">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="headline">Summary:</label>
      <div class="col-sm-10">
        <textarea type="text" class="form-control" rows="8" cols="80"  name="summary"><?= $sum ?></textarea>
      </div>
    </div>

    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Add</button>
      </div>
    </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" name="cancel" class="btn btn-default">Cancel</button>
      </div>
    </div>
</form>

</div>
</body>
</html>