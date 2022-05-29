<?php 

function flashMessage(){
    if(isset($_SESSION['error'])){
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }
    if ( isset($_SESSION['successMSG']) ) {
        echo '<p style="color:green">'.$_SESSION['successMSG']."</p>\n";
        unset($_SESSION['successMSG']);
    }
}
function validateProfile() {
    if(isset($_POST['first_name'])&& isset($_POST['last_name'])&& isset($_POST['email'])&& isset($_POST['headline'])&& isset($_POST['summary'])){
        if(strlen($_POST['first_name'])<1 || strlen($_POST['last_name'])<1 || strlen($_POST['email'])<1 || strlen($_POST['headline'])<1 || strlen($_POST['summary'])<1 ){
            // $_SESSION['error'] ="All fields are required";
        //   error_log("Wrong inputs ".$_SESSION['name'].isset($_SESSION['error']));
        //   header("Location: add.php");
          return "All fields are required";
        }
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            // $_SESSION['error'] ="Email address must contain @";
        //   error_log("Wrong Email ".$_SESSION['name'].isset($_SESSION['error']));
        //   header("Location: add.php");
          return"Email address must contain @";
        }
        
}
return true;
}

function validatePos(){
    for($i=1;$i<=9;$i++){
        if(!isset($_post['year'.$i])) continue;
        if(!isset($_post['desc'.$i])) continue;
        $year = $_post['year'.$i];
        $desc = $_post['desc'.$i];
        if(strlen($year)==0 || strlen($desc)==0){
            return "All fields are required";
        }
        if(! is_numeric($year)){
            return "Position year must be a numeric";
        }


    }
    return true;
}

function loadPos($pdo, $profile_id){
    $stmt = $pdo->prepare ('SELECT * FROM Position WHERE profile_id = :prof ORDER BY rank');
    $stmt->execute(array(':prof' => $profile_id));
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $positions;
}