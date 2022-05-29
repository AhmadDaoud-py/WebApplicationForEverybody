<?php // Do not put any HTML above this line
session_start();

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to autos.php
    header("Location: index.php");
    return;
}


$stored_hash = '218140990315bb39d948a523d61549b4';  // Pw is php123


// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    unset($_SESSION['account']);
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
    } else {
        $check = hash('md5',$_POST['pass']);

            if ( $check == $stored_hash ) {
            // Redirect the browser to view.php
            header("Location: view.php");
            error_log("Login success ".$_POST['email']);
            $_SESSION['account'] = $_POST['email'];
            $_SESSION["success"] = "Logged in.";
            header('Location: index.php');
            return;



        } 
        else {
            $_SESSION['error']  = "Incorrect password ";
            error_log("Login fail ".$_POST['email']." $check");
            header( 'Location: login.php' ) ;
            return;
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<title>Ahmad Daoud</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( isset($_SESSION['error'])) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION["error"]);
}
?>
<form method="POST" action="login.php">
<label for="email">Email</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<a href="index.php">Cancel</a>
</form>
<p>
For a password hint, the password is php123.
</p>
<p>Ahmad Daoud &copy 2022</p>
</div>
</body>
