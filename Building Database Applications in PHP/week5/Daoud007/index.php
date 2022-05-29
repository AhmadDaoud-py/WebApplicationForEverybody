<?php
session_start();
require_once "pdo.php";
$stmt = $pdo->query("SELECT auto_id, make, model, year, mileage FROM autos");
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
<h2>Welcome to the Automobiles Database</h2>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['successMSG']) ) {
    echo '<p style="color:green">'.$_SESSION['successMSG']."</p>\n";
    unset($_SESSION['successMSG']);
}

if ( ! isset($_SESSION['success'] ) ) {
    unset($_SESSION['success']);
    echo "<p><a href='login.php'>Please log in</a></p>";
    echo '<p>Attempt to <a href="add.php">add data</a> without logging in</p>';
    echo "<p>Ahmad Daoud &copy 2022</p>";
}
if (isset($_SESSION['success'] ) ) {
  if($stmt -> rowCount () >0){
    echo "<table border=\"1\">";
    echo "<tr><th>Make</th>
          <th>Model</th>
          <th>Year</th>
          <th>Mileage</th>
          <th>Action</th></tr>";
    foreach ( $rows as $row ) {
      echo "<tr><td>";
      echo htmlentities($row['make']);
      echo "</td><td>";
      echo htmlentities($row['model']);
      echo "</td><td>";
      echo htmlentities($row['year']);
      echo "</td><td>";
      echo htmlentities($row['mileage']);
      echo "</td><td>";
      echo ('<a href="edit.php?auto_id='.$row['auto_id'].'">Edit</a> / <a href="delete.php?auto_id='.$row['auto_id'].'">Delete</a>');
      echo "</td></tr>";
    }
    echo "</table>";


  }
  else {
    echo "<p>No rows found</p>";
  }



    echo "<p><a href=\"add.php\">Add New Entry</a></p>
          <p><a href=\"logout.php\">Logout</a></p>
          <p>
          <b>Note:</b> Your implementation should retain data across multiple
          logout/login sessions.  This sample implementation clears all its
          data on logout - which you should not do in your implementation.
          </p>
          <p>Ahmad Daoud &copy 2022</p>";

}
  ?>

</div>
</body>
