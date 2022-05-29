<?php
if ( ! isset($_GET['term']) ) {
    die("Missing required parameter");
}

if(!isset($_COOKIE[session_name()])){
    die("Must be logged in");
}

session_start();
if ( ! isset($_SESSION['user_id'] ) && !isset($_SESSION['name']) ) {
    unset($_SESSION['name']);
    unset($_SESSION['user_id']);
    die('ACCESS DENIED');
}


require_once "pdo.php";

header('content-type: application/json; charset = UTF-8');

$term = $_REQUEST['term'];
error_log("looking up typeahead term=".$term);

$stmt = $pdo->prepare("SELECT name FROM institution WHERE name LIKE :prefix");
$stmt->execute(array(':prefix'=>$term."%"));

$retval = array();

while($row = $stmt -> fetch(PDO::FETCH_ASSOC))
    $retval[] = $row["name"];

echo(json_encode($retval, JSON_PRETTY_PRINT));