<?php
session_start();
require_once "pdo.php";
$stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM profile");
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
<h1>Ahmad Daoud's Resume Registry</h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['successMSG']) ) {
    echo '<p style="color:green">'.$_SESSION['successMSG']."</p>\n";
    unset($_SESSION['successMSG']);
}

if ( ! isset($_SESSION['name'] ) ) {
    unset($_SESSION['name']);
    echo "<p><a href='login.php'>Please log in</a></p>";
}
if (isset($_SESSION['name'] ) ) {
  echo "<p><a href='logout.php'>Logout</a></p>";
  }

  if($stmt -> rowCount () >0){
    echo "<table border=\"1\">";
    echo "<tr><th>Name</th>
          <th>Headline</th>
          <th>Action</th></tr>";
    foreach ( $rows as $row ) {
      echo "<tr><td>";
      
      echo ('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities ($row['first_name']).' '.htmlentities ($row['last_name']).' </a>' );
      echo "</td><td>";
      echo htmlentities($row['headline']);
      echo "</td><td>";
      echo ('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / <a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
      echo "</td></tr>";
    }
    echo "</table>";

}
if (isset($_SESSION['name'] ) ) {
  echo "<p><a href=\"add.php\">Add New Entry</a></p>";
  }

echo "<p>
<b>Note:</b> Your implementation should retain data across multiple
logout/login sessions.  This sample implementation clears all its
data on logout - which you should not do in your implementation.
</p>";
  ?>

</div>
</body>
