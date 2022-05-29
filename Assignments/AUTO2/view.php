<?php require_once "pdo.php";
session_start();
if ( ! isset($_SESSION['success'] ) ) {
    die("Not logged in");

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
if ( isset($_SESSION['account']) ) {
    echo "<h1>Tracking Autos for ";
    echo htmlentities($_SESSION['account']);
    echo "</h1>\n";
}

if(isset($_SESSION['insert'])){
    echo('<p style="color: green;">'.htmlentities($_SESSION['insert'])."</p>\n");
    unset($_SESSION['insert']);
}

?>

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
<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
</div>
</body>
</html>
