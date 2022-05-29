<?php
require_once "pdo.php";
require_once "util.php";

session_start();

if ( ! isset($_SESSION['user_id'] ) && !isset($_SESSION['name']) ) {
    unset($_SESSION['name']);
    unset($_SESSION['user_id']);
    die('ACCESS DENIED');
}
if(isset($_POST['cancel'])){
    header('Location:index.php');
    return;
}
//handle the incoming data
if(isset($_POST['first_name'])&& isset($_POST['last_name'])&& isset($_POST['email'])&& isset($_POST['headline'])&& isset($_POST['summary'])){
    $msg = validateProfile();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location:add.php');
        return;
    }

    $msg = validatePos();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location:add.php');
        return;
    }

    $msg = validateEdu();
    
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header('Location:add.php');
        return;
    }

    
        $sql = "INSERT INTO profile ( user_id, first_name , last_name , email, headline, summary)
                  VALUES (:id, :fn, :ln, :mail, :hd, :sum)";
    
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':id' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':mail' => $_POST['email'],
            ':hd' => $_POST['headline'],
            ':sum' => $_POST['summary']));


            $profile_id = $pdo-> lastInsertId();

            // insert position information

            insertPositions($pdo, $profile_id);


            //insert education information
            insertEducations($pdo, $profile_id);
    
            $_SESSION['successMSG'] = "Profile added";
            header('Location:index.php');
            return;
            
        

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "bootstrap.php" ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahmad Daoud</title>
</head>
<body>
<div class="container">

<?php
if ( isset($_SESSION['name']) ) {
    echo "<h1>Adding Profile for ";
    echo htmlentities($_SESSION['name']);
    echo "</h1>\n";
}

flashMessage();



?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>
<p>
    Education : <input type="button" id = "addEdu" value = "+">
    <div id="education_fields">
    </div>
</p>
<p>
    Position : <input type="button" id = "addPos" value = "+">
    <div id="position_fields">
    </div>
</p>
<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
<script>
    countPos = 0;
    countEdu = 0;
    $(document).ready(function(){
        console.log("Document is ready");
        $('#addPos').click(function(event) {
            event.preventDefault();
            if(countPos >= 9){
                alert("Maximum of nine position entries exceeded");
                return;
            }
            countPos++; //
            console.log("Adding position"+countPos);
            $('#position_fields').append('<div id="position'+countPos+'" > \
            <p>Year: <input type="text"name="year'+countPos+'" value = ""/> \
            <input type="button" value = "-" \
            onclick = "$(\'#position'+ countPos +'\').remove(); countPos--; return false;"></p> \
            <textarea name="desc'+countPos+'" rows = "8" cols = "80"></textarea>\
            </div>');

        });
        $('#addEdu').click(function(event){
        event.preventDefault();
        if(countEdu >= 9){
                alert("Maximum of nine education entries exceeded");
                return;
        }
        countEdu++;
        console.log('Adding education'+countEdu);

        $('#education_fields').append('<div id="edu'+countEdu+'"> \
        <p>Year: <input type="text" name="edu-year'+countEdu+'" value = ""/>\
            <input type="button" value = "-" \
            onclick = "$(\'#edu'+countEdu+'\').remove(); countEdu--; return false;"></p> \
            <p> School: <input type="text" size = "80" name="edu-school'+countEdu+'" class = "school" value = ""/>\
            </p>\
            </div>');

        // ________________
        // var source = $('#edu-template').html();
        // //grap some html with hot spots and insert into the dom
        // $('education_fields').append(source.replace(/@COUNT@/g,countEdu));
        // ____________________

        //add event handler to add new ones
        $('.school').autocomplete({
            source : function (data, response){
                $.ajax({
                    url : 'school.php',
                    method : 'GET',
                    dataType : 'json',
                    data:{
                        term : data.term
                    },
                    success : function (result) {
                        response(result);
                    }
                });
            }
        });
    });


    })
</script>
</div>
</body>
</html>
