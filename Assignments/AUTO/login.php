<?php // Do not put any HTML above this line

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to autos.php
    header("Location: index.php");
    return;
}


$stored_hash = '218140990315bb39d948a523d61549b4';  // Pw is meow123

$failure = false;  // If we have no POST data

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['who']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "User name and password are required";
    } else {
        $check = hash('md5',$_POST['pass']);
        
            if ( $check == $stored_hash && filter_var($_POST['who'], FILTER_VALIDATE_EMAIL) == true) {
            // Redirect the browser to autos.php
            header("Location: autos.php?name=".urlencode($_POST['who']));
            error_log("Login success ".$_POST['who']);
            
        
        
        } elseif(!filter_var($_POST['who'], FILTER_VALIDATE_EMAIL) == true) {
            $failure = "Email must have an at-sign (@)";
        }
        else {
            $failure = "Incorrect password ";
            error_log("Login fail ".$_POST['who']." $check");
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Ahmad Daoud</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...
if ( $failure !== false ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}
?>
<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="who" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.

</p>
</div>
</body>
