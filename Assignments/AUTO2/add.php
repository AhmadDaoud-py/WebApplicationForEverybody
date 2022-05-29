<?php

require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['success'] ) ) {
    unset($_SESSION['success']);
    die('Not logged in');
}
if(isset($_POST['cancel'])){
    header('Location:view.php');
    return;
}

if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']))
{
    
    if( !is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])){
        $_SESSION['error'] ="Mileage and year must be numeric";
        error_log("Wrong inputs ".$_SESSION['account'].isset($_SESSION['error']));
        header('Location: add.php');
        return;

    }
    if(strlen($_POST['make'])<1){
        $_SESSION['error'] ="Mileage and year must be numeric";
        error_log("Wrong inputs ".$_SESSION['account'].isset($_SESSION['error']));
        header('Location: add.php');
        return;

    }
    else{
    $sql = "INSERT INTO autos (make , year, mileage) 
              VALUES (:make, :year, :mileage)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage']));

        $_SESSION['insert'] = "Record inserted";
        header('Location:view.php');
        return;
    }
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

<?php
if ( isset($_SESSION['account']) ) {
    echo "<h1>Tracking Autos for ";
    echo htmlentities($_SESSION['account']);
    echo "</h1>\n";
}

if ( isset($_SESSION['error']) ) {

    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
}

?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="cancel">
</form>

</div>
</body>
</html>
