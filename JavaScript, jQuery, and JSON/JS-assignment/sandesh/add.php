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

if(isset($_POST['first_name'])&& isset($_POST['last_name'])&& isset($_POST['email'])&& isset($_POST['headline'])&& isset($_POST['summary'])){
    if(strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1 || strlen($_POST['email'])<1 || strlen($_POST['headline'])<1 || strlen($_POST['summary'])<1 ){
        $_SESSION['error'] ="All fields are required";
      error_log("Wrong inputs ".$_SESSION['name'].isset($_SESSION['error']));
      header("Location: add.php");
      return;
    }
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $_SESSION['error'] ="Email address must contain @";
      error_log("Wrong Email ".$_SESSION['name'].isset($_SESSION['error']));
      header("Location: add.php");
      return;
    }
    else{
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
    
            $_SESSION['successMSG'] = "Profile added";
            header('Location:index.php');
            return;
        }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "bootstrap.php" ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add</title>
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
    echo "<h1>Adding Profile for ";
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
        <input type="text" class="form-control" placeholder="Enter the first name" name="first_name">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="lname">Last name:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="Enter the last name" name="last_name">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="mail">Email:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="Enter the email" name="email">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="headline">Headline:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="Enter the headline" name="headline">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="headline">Summary:</label>
      <div class="col-sm-10">
        <textarea type="text" class="form-control" rows="8" cols="80" placeholder="Enter the Summary" name="summary"></textarea>
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
