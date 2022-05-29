<?php

require_once "pdo.php";

if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}
if(isset($_POST['logout'])){
    header('Location:index.php');
    return;
}

if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']))
{
    $msg = false;
    if( !is_numeric($_POST['year']) && !is_numeric($_POST['mileage'])){
        $msg = "Mileage and year must be numeric";

    }
    if(strlen($_POST['make'])<1){
        $msg = "Make is required";

    }
    else{
    $sql = "INSERT INTO autos (make , year, mileage) 
              VALUES (:make, :year, :mileage)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage']));
    }
}

$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
if ( isset($_REQUEST['name']) ) {
    echo "<h1>Tracking Autos for ";
    echo htmlentities($_REQUEST['name']);
    echo "</h1>\n";
}

if ( $msg !== false ) {

    echo('<p style="color: red;">'.htmlentities($msg)."</p>\n");
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
<input type="submit" name="logout" value="Logout">
</form>
<h2>Automobiles</h2>
<?php
foreach($rows as $row){
    echo "<ul>";
    echo "<li>";
    echo "<p>", $row['year']," ", $row['make'], " / ", $row['mileage'],"</p>";
    echo "</li>";
    echo "</ul>";
}

?>
</div>
</body>
</html>
