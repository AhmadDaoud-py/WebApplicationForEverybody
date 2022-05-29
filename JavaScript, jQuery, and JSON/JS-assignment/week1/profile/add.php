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

if ( isset($_SESSION['error']) ) {

    echo('<p style="color: red;">'.$_SESSION['error']."</p>\n");
    unset($_SESSION['error']);
}



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
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
</html>
