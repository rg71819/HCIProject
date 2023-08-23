<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
else if(!isset($_SESSION["secondPassword"]) && $_SESSION["secondPassword"] !== true){
    header("location: secondPassword.php");
    exit;
} else if(!isset($_SESSION["firstPassword"]) && $_SESSION["firstPassword"] !== true){
    header("location: login.php");
    exit;
} 

 
// Include config file
require_once "dbConfig.php";
 
// Define variables and initialize with empty values
$thirdPassword = "";
$thirdPassword_err = "";
$login_err = array();
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if answer 1 is empty
    if(!isset($_POST["thirdPassword"]) || empty(trim($_POST["thirdPassword"]))){
        $thirdPassword_err = "Please select an image";
    } else{
        $thirdPassword = trim($_POST["thirdPassword"]);
    }
    
    
    // Validate credentials
    if(empty($thirdPassword_err)){
        // Prepare a select statement
        $sql = "SELECT firstPassword, secondPasswordFirstAnswer, secondPasswordSecondAnswer, secondPasswordThirdAnswer, thirdPassword FROM userdetails WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $_SESSION["username"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $DBFirstPassword,$DBSecondPassword1,$DBSecondPassword2,$DBSecondPassword3, $DBthirdPassword);
                    if(mysqli_stmt_fetch($stmt)){
                        if($DBFirstPassword !== $_SESSION['firstPasswordValue']){
                            $login_err[] ="Wrong Password";
                            
                        } if($DBSecondPassword1 !== $_SESSION['secondPasswordValue1'] || $DBSecondPassword2 !== $_SESSION['secondPasswordValue2'] || $DBSecondPassword3 !== $_SESSION['secondPasswordValue3']){
                            $login_err[] ="Wrong Answers to one or more security questions";
                        }
                        if($DBthirdPassword !== $thirdPassword){
                            $login_err[] = "Wrong image selected";
                        }
                        if($DBFirstPassword === $_SESSION['firstPasswordValue'] && $DBSecondPassword1 === $_SESSION['secondPasswordValue1'] && $DBSecondPassword2 === $_SESSION['secondPasswordValue2'] && $DBSecondPassword3 === $_SESSION['secondPasswordValue3'] && $DBthirdPassword === $thirdPassword ){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["thirdPassword"] = true;                         
                            $_SESSION["loggedin"] = true;                         
                            
                            // Redirect user to welcome page
                            header("location: index.php");
                        } 
                        // else{
                            // Password is not valid, display a generic error message
                            
                        // }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err[] = "This username doesn't exist";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* HIDE RADIO */
        .hiddenradio [type=radio] { 
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        }

        /* IMAGE STYLES */
        .hiddenradio [type=radio] + img {
        cursor: pointer;
        padding: 2px;
        }

        /* CHECKED STYLES */
        .hiddenradio [type=radio]:checked + img {
        outline: 2px solid #f00;
        }
        .imageLarge:hover{
            height: 200px;
            width: 200px;
        }
    </style>
</head>
<body>
    <?php 
        if(!empty($login_err)){
            if(is_array($login_err)){ foreach ($login_err as $perror) { echo "<div class='alert alert-danger'> $perror </div><br>";} }
        }        
        ?>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group hiddenradio">
                <?php
                for($i=1;$i<82;$i++)
                {
                    echo '<label><input type="radio" name="thirdPassword" value="'.$i.'.jpg" ><img src="pictures/'.$i.'.jpg" width=55 height=55 class="imageLarge"></label>';
                    if($i%9==0){
                        echo "<br>";
                    }
                }
                ?>
                <span class="invalid-feedback"><?php echo $thirdPassword_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="logout.php">back</a>
            </div>
    </form>
</body>
</html>