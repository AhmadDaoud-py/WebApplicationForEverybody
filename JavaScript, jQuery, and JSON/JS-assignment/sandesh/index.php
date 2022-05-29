<?php
session_start();
require_once "pdo.php";
$stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline FROM profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 ?>
<!DOCTYPE html>
<html>
<head>
<title>Sandesh Ghimire</title>
<?php require_once "bootstrap.php"; ?>
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
          

      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
        <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span>Login</a></li>
      </ul>
    </div>
  </div>
</nav>
<h1>Sandesh Ghimire's Resume Registry</h1>
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
    echo "<p>Please log in</p>";
}
if (isset($_SESSION['name'] ) ) {
  echo "<p><a href='logout.php'>Logout</a></p>";
  }

  if($stmt -> rowCount () >0){
    echo "<table class=\"table table-striped\">";
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

  ?>

</div>
</body>
