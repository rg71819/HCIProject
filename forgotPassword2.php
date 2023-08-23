<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["resetPass"]) || $_SESSION["resetPass"] !== true){
    header("location: login.php");
    exit;
}
$resetEmail = $_SESSION['resetEmail'];

// Include config file
require_once "dbConfig.php";
 
// Define variables and initialize with empty values
$new_password = $confirm_password =  $new_SecurityQuestion1 = $new_SecurityQuestion2 = $new_SecurityQuestion3 = $new_SecurityQuestionAnswer1 = $new_SecurityQuestionAnswer2 = $new_SecurityQuestionAnswer3 = $new_thirdPassword = $tempPassword =  "";
$new_password_err = $confirm_password_err = $new_SecurityQuestion1_err =  $new_SecurityQuestion2_err =  $new_SecurityQuestion3_err = $new_SecurityQuestionAnswer1_err = $new_SecurityQuestionAnswer2_err = $new_SecurityQuestionAnswer3_err = $new_thirdPassword_err = $tempPassword_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

        // Validate tempPassword
        if(empty(trim($_POST["tempPass"]))){
            $tempPassword_err = "Please enter a temp password.";
        } elseif(!preg_match('/^[a-zA-Z0-9!@#$%&*_+]+$/', trim($_POST["tempPass"]))){
            $tempPassword_err = "temp password can only contain letters, numbers, and underscores.";
        } else{
            // Prepare a select statement
            $sql = "SELECT id,tempPassword FROM userdetails WHERE email = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                var_dump($stmt);
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_resetEmail);
                
                // Set parameters
                $param_resetEmail = $resetEmail;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    /* store result */
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        mysqli_stmt_bind_result($stmt, $id, $tempPassword);
                        mysqli_stmt_fetch($stmt);
                        if($tempPassword !== $_POST["tempPass"]){
                            $tempPassword_err = "Temp Password Does not match";
                            echo "<script>alert('".$param_resetEmail." = ".$tempPassword."=".$_POST["tempPass"]."');</script>";
                        }
                    }
                    else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate new password
    if(empty(trim($_POST["SecurityQuestion1"]))){
        $new_SecurityQuestion1_err = "Please enter the Security Question 1.";     
    } else{
        $new_SecurityQuestion1 = trim($_POST["SecurityQuestion1"]);
    }

    // Validate new password
    if(empty(trim($_POST["SecurityQuestion2"]))){
        $new_SecurityQuestion2_err = "Please enter the Security Question 2.";     
    } else{
        $new_SecurityQuestion2 = trim($_POST["SecurityQuestion2"]);
    }

    // Validate new password
    if(empty(trim($_POST["SecurityQuestion3"]))){
        $new_SecurityQuestion3_err = "Please enter the Security Question 3.";     
    } else{
        $new_SecurityQuestion3 = trim($_POST["SecurityQuestion3"]);
    }

    // Validate new password
    if(empty(trim($_POST["SecurityQuestionAnswer1"]))){
        $new_SecurityQuestionAnswer1_err = "Please enter the Security Answer 1.";     
    } else{
        $new_SecurityQuestionAnswer1 = trim($_POST["SecurityQuestionAnswer1"]);
    }

    // Validate new password
    if(empty(trim($_POST["SecurityQuestionAnswer2"]))){
        $new_SecurityQuestionAnswer2_err = "Please enter the Security Answer 2.";     
    } else{
        $new_SecurityQuestionAnswer2 = trim($_POST["SecurityQuestionAnswer2"]);
    }

    // Validate new password
    if(empty(trim($_POST["SecurityQuestionAnswer3"]))){
        $new_SecurityQuestionAnswer3_err = "Please enter the Security Answer 3.";     
    } else{
        $new_SecurityQuestionAnswer3 = trim($_POST["SecurityQuestionAnswer3"]);
    }

    if(empty(trim($_POST["thirdPassword"]))){
        $new_thirdPassword_err = "Please select an image.";     
    } else{
        $new_thirdPassword = trim($_POST["thirdPassword"]);
    }

        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err) && empty($confirm_SecurityQuestion1_err) && empty($confirm_SecurityQuestion2_err) && empty($confirm_SecurityQuestion3_err) && empty($confirm_SecurityQuestionAnswer2_err) && empty($confirm_SecurityQuestionAnswer2_err) && empty($confirm_SecurityQuestionAnswer3_err) && empty($confirm_thirdPassword_err) && empty($tempPassword_err)){
        // Prepare an update statement
        $sql1 = "UPDATE userdetails SET firstPassword = ? , SecondPasswordfirstQuestion = ? , SecondPasswordFirstAnswer = ? , SecondPasswordsecondQuestion = ? , SecondPasswordSecondAnswer = ? , SecondPasswordthirdQuestion = ? , SecondPasswordThirdAnswer = ? , thirdPassword = ?  WHERE email = ?";
        

        if($stmt1 = mysqli_prepare($link, $sql1)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt1, "sssssssss", $param_password, $param_SecondPasswordfirstQuestion, $param_SecurityQuestionAnswer1, $param_SecondPasswordsecondQuestion, $param_SecurityQuestionAnswer2, $param_SecondPasswordthirdQuestion, $param_SecurityQuestionAnswer3, $param_thirdPassword, $param_email);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_SecondPasswordfirstQuestion = $new_SecurityQuestion1;
            $param_SecondPasswordsecondQuestion = $new_SecurityQuestion2;
            $param_SecondPasswordthirdQuestion = $new_SecurityQuestion3;
            $param_SecurityQuestionAnswer1 = $new_SecurityQuestionAnswer1;
            $param_SecurityQuestionAnswer2 = $new_SecurityQuestionAnswer2;
            $param_SecurityQuestionAnswer3 = $new_SecurityQuestionAnswer3;
            $param_thirdPassword = $new_thirdPassword;
            $param_email = $resetEmail;
            


            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt1)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt1);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/mvp.css">
    <link rel="stylesheet" href="assets/style.css">
    <script>
    function toggleField(hideObj,showObj){
        hideObj.disabled=true;        
        hideObj.style.display='none';
        showObj.disabled=false;   
        showObj.style.display='inline';
        showObj.focus();
        }
    </script>
</head>
<body>
    <main>
        <section>
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>

        <?php 
        if(!empty($tempPassword_err)){
            echo '<div class="alert alert-danger">' . $tempPassword_err . '</div>';
        }        
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>Temp Password</label>
                <input type="password" name="tempPass" class="form-control <?php echo (!empty($tempPassword_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tempPassword; ?>">
                <span class="invalid-feedback"><?php echo $tempPassword_err; ?></span>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
            <label>Security Question 1</label>
            <select name="SecurityQuestion1" class="form-control <?php echo (!empty($new_SecurityQuestion1_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_SecurityQuestion1; ?>"
                    onchange="if(this.options[this.selectedIndex].value=='CustomQuestion1'){
                        toggleField(this,this.nextSibling);
                        this.selectedIndex='0';
                    }">
                <option>Please select a security question or write your own</option>
                <option value="CustomQuestion1">Add Your Own Question</option>
                <option value="What is the name of a college you applied to but didn’t attend?">What is the name of a college you applied to but didn’t attend?</option>
                <option value="What was the name of the first school you remember attending?">What was the name of the first school you remember attending?</option>
                <option value="Where was the destination of your most memorable school field trip?">Where was the destination of your most memorable school field trip?</option>
                <option value="What was your math’s teacher's surname in your 8th year of school?">What was your math’s teacher's surname in your 8th year of school?</option>
                <option value="What was the name of your first stuffed toy?">What was the name of your first stuffed toy?</option>
                <option value="What was your driving instructor's first name?">What was your driving instructor's first name?</option>
            </select><input name="SecurityQuestion1" style="display:none;" disabled="disabled" class="form-control <?php echo (!empty($new_SecurityQuestion1_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_SecurityQuestion1; ?>"
                onblur="if(this.value==''){toggleField(this,this.previousSibling);}">
            </div>

            <div class="form-group">
                <label>Security Question Answer 1</label>
                <input type="text" name="SecurityQuestionAnswer1" class="form-control <?php echo (!empty($new_SecurityQuestionAnswer1_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_SecurityQuestionAnswer1; ?>">
                <span class="invalid-feedback"><?php echo $new_SecurityQuestionAnswer1_err; ?></span>
            </div>

            <div class="form-group">
            <label>Security Question 2</label>
            <select name="SecurityQuestion2" class="form-control <?php echo (!empty($new_SecurityQuestion2_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_SecurityQuestion2; ?>"
                    onchange="if(this.options[this.selectedIndex].value=='CustomQuestion2'){
                        toggleField(this,this.nextSibling);
                        this.selectedIndex='0';
                    }">
                <option>Please select a security question or write your own</option>
                <option value="CustomQuestion2">Add Your Own Question</option>
                <option value="What is the name of a college you applied to but didn’t attend?">What is the name of a college you applied to but didn’t attend?</option>
                <option value="What was the name of the first school you remember attending?">What was the name of the first school you remember attending?</option>
                <option value="Where was the destination of your most memorable school field trip?">Where was the destination of your most memorable school field trip?</option>
                <option value="What was your math’s teacher's surname in your 8th year of school?">What was your math’s teacher's surname in your 8th year of school?</option>
                <option value="What was the name of your first stuffed toy?">What was the name of your first stuffed toy?</option>
                <option value="What was your driving instructor's first name?">What was your driving instructor's first name?</option>
            </select><input name="SecurityQuestion2" style="display:none;" disabled="disabled" class="form-control <?php echo (!empty($new_SecurityQuestion2_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_SecurityQuestion2; ?>"
                onblur="if(this.value==''){toggleField(this,this.previousSibling);}">
            </div>
            <div class="form-group">
                <label>Security Question Answer 2</label>
                <input type="text" name="SecurityQuestionAnswer2" class="form-control <?php echo (!empty($new_SecurityQuestionAnswer2_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_SecurityQuestionAnswer2; ?>">
                <span class="invalid-feedback"><?php echo $new_SecurityQuestionAnswer2_err; ?></span>
            </div>

            <div class="form-group">
            <label>Security Question 3</label>
            <select name="SecurityQuestion3" class="form-control <?php echo (!empty($new_SecurityQuestion3_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_SecurityQuestion3; ?>"
                    onchange="if(this.options[this.selectedIndex].value=='CustomQuestion3'){
                        toggleField(this,this.nextSibling);
                        this.selectedIndex='0';
                    }">
                <option>Please select a security question or write your own</option>
                <option value="CustomQuestion3">Add Your Own Question</option>
                <option value="What is the name of a college you applied to but didn’t attend?">What is the name of a college you applied to but didn’t attend?</option>
                <option value="What was the name of the first school you remember attending?">What was the name of the first school you remember attending?</option>
                <option value="Where was the destination of your most memorable school field trip?">Where was the destination of your most memorable school field trip?</option>
                <option value="What was your math’s teacher's surname in your 8th year of school?">What was your math’s teacher's surname in your 8th year of school?</option>
                <option value="What was the name of your first stuffed toy?">What was the name of your first stuffed toy?</option>
                <option value="What was your driving instructor's first name?">What was your driving instructor's first name?</option>
            </select><input name="SecurityQuestion3" style="display:none;" disabled="disabled" class="form-control <?php echo (!empty($new_SecurityQuestion3_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_SecurityQuestion3; ?>"
                onblur="if(this.value==''){toggleField(this,this.previousSibling);}">
            </div>
            <div class="form-group">
                <label>Security Question Answer 3</label>
                <input type="text" name="SecurityQuestionAnswer3" class="form-control <?php echo (!empty($new_SecurityQuestionAnswer3_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_SecurityQuestionAnswer3; ?>">
                <span class="invalid-feedback"><?php echo $new_SecurityQuestionAnswer3_err; ?></span>
            </div>
            <div class="form-group hiddenradio">
                <?php
                for($i=1;$i<10;$i++)
                {
                    echo '<labelstyle="display:inline-block;"><input type="radio" name="thirdPassword" value="'.$i.'.jpg" ><img src="pictures/'.$i.'.jpg" width=100 height=100 class="imageLarge"></label>';
                    if($i%3==0){
                        echo "<br>";
                    }
                }
                ?>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
            </section>
            </main>    
</body>
</html>