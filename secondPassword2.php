<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["secondPassword"]) && $_SESSION["secondPassword"] === true){
    header("location: thirdPassword.php");
    exit;
} else if(!isset($_SESSION["firstPassword"]) && $_SESSION["firstPassword"] !== true){
    header("location: login.php");
    exit;
} 

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

 
// Include config file
require_once "dbConfig.php";
 
// Define variables and initialize with empty values
$answer1 = $answer2 = $answer3 = "";
$answer1_err =  $answer2_err = $answer3_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if answer 1 is empty
    if(empty(trim($_POST["SecurityQuestionAnswer1"]))){
        $answer1_err = "Please enter answer 1.";
    } else{
        $answer1 = trim($_POST["SecurityQuestionAnswer1"]);
    }
    
    // Check if answer 2 is empty
    if(empty(trim($_POST["SecurityQuestionAnswer2"]))){
        $answer2_err = "Please enter answer 2.";
    } else{
        $answer2 = trim($_POST["SecurityQuestionAnswer2"]);
    }

    // Check if answer 3 is empty
    if(empty(trim($_POST["SecurityQuestionAnswer3"]))){
        $answer3_err = "Please enter answer 3.";
    } else{
        $answer3 = trim($_POST["SecurityQuestionAnswer3"]);
    }
    
    // Validate credentials
    if(empty($answer1_err) && empty($answer2_err)&& empty($answer3_err)){
        
        

            
            // Set parameters
            $param_username = $_SESSION["username"];
            $_SESSION["secondPasswordValue1"] = $answer1;
            $_SESSION["secondPasswordValue2"] = $answer2;
            $_SESSION["secondPasswordValue3"] = $answer3;

            

                

                      
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["secondPassword"] = true;                         
                            
                            // Redirect user to welcome page
                            header("location: thirdPassword.php");
                        
                    
                
            
        
    }
    
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Employees Details</h2>
                    </div>
                    <?php 
                    if(!empty($login_err)){
                        echo '<div class="alert alert-danger">' . $login_err . '</div>';
                    }        
                    ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <?php
                    // Include config file
                    require_once "dbConfig.php";
                    
                    // Attempt select query execution
                    $sql = 'SELECT SecondPasswordfirstQuestion, SecondPasswordsecondQuestion, SecondPasswordthirdQuestion FROM userdetails where username= "'.$_SESSION['username'].'"';
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){

                                while($row = mysqli_fetch_array($result)){
                                    echo '<div class="form-group">
                                                <label>'.$row["SecondPasswordfirstQuestion"].'</label>
                                                <input type="text" name="SecurityQuestionAnswer1" class="form-control">
                                                <span class="invalid-feedback"><?php echo $answer1_err; ?></span>
                                            </div>';
                                    echo '<div class="form-group">
                                            <label>'.$row["SecondPasswordsecondQuestion"].'</label>
                                            <input type="text" name="SecurityQuestionAnswer2" class="form-control">
                                            <span class="invalid-feedback"><?php echo $answer2_err; ?></span>
                                        </div>';   
                                    echo '<div class="form-group">
                                        <label>'.$row["SecondPasswordthirdQuestion"].'</label>
                                        <input type="text" name="SecurityQuestionAnswer3" class="form-control">
                                        <span class="invalid-feedback"><?php echo $answer3_err; ?></span>
                                    </div>';
                                }
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo 'Oops! Something went wrong. Please try again later.';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>