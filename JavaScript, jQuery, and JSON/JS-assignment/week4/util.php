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
    for($i=1; $i<=9; $i++){
        if(!isset($_POST['year'.$i])) continue;
        if(!isset($_POST['desc'.$i])) continue;



        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        if (strlen($year) == 0 || strlen($desc) == 0) {
            return "All fields are required";
        }

        if (!is_numeric($year)) {
            return "Position year must be numeric";
        }
        
        


    }
    return true;
}

function validateEdu(){
    for($i=1;$i<=9;$i++){
        if(!isset($_POST['edu-year'.$i])) continue;
        if(!isset($_POST['edu-school'.$i])) continue;
        $year = $_POST['edu-year'.$i];
        $school = $_POST['edu-school'.$i];
        if(strlen($year)==0 || strlen($school)==0){
            return "All fields are required";
        }
        if(! is_numeric($year)){
            return "Educatin year must be a numeric";
        }


    }
    return true;

}

function insertPositions($pdo, $profile_id){
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if(!isset($_POST['year'.$i])) continue;
        if(!isset($_POST['desc'.$i])) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare('INSERT INTO position (profile_id, rank, year, description) values (:pid ,:rank, :year, :desc)');
        $stmt->execute(array(':pid' => $profile_id , ':rank' => $rank, ':year' => $year, ':desc' => $desc));
        $rank++;


    }
}

function insertEducations($pdo, $profile_id){
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if(!isset($_POST['edu-year'.$i])) continue;
        if(!isset($_POST['edu-school'.$i])) continue;
        $year = $_POST['edu-year'.$i];
        $school = $_POST['edu-school'.$i];
        $institution_id = false;
        
        $stmt = $pdo->prepare('SELECT institution_id from institution where name = :name');
        $stmt->execute(array(':name' => $school));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false) $institution_id = $row['institution_id'];
        if($row===false){
            $stmt = $pdo->prepare('INSERT INTO institution (name) VALUES (:name)');
            $stmt->execute(array(':name'=> $school));
            $institution_id = $pdo->lastInsertid();

        }

        $stmt = $pdo->prepare('INSERT INTO education (profile_id, rank, year, institution_id) VALUES (:profile_id, :rank, :year, :inst)');
        $stmt->execute(array(':profile_id'=>$profile_id, ':rank'=>$rank, ':year'=>$year, ':inst'=>$institution_id));


        
       
        $rank++;


    }
}

function loadPos($pdo, $profile_id){
    $stmt = $pdo->prepare ('SELECT * FROM Position WHERE profile_id = :prof ORDER BY rank');
    $stmt->execute(array(':prof' => $profile_id));
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $positions;
}

function loadEdu($pdo, $profile_id){
    $stmt = $pdo->prepare('SELECT year, name FROM education JOIN institution ON education.institution_id = institution.institution_id WHERE profile_id = :prof ORDER BY rank');
    $stmt->execute(array(':prof' => $_REQUEST['profile_id']));
    $educations = $stmt -> FetchAll(PDO::FETCH_ASSOC);

    return $educations;
}