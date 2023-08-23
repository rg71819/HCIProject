<?php
// Include config file
require_once "dbConfig.php";
//validate password
function validatePassword($password) {
    $errors = array(); // Initialize an empty array to store error messages
  
    // Check password length
    if (strlen($password) < 8) {
      $errors[] = "Password must be at least 8 characters in length.";
    }
  
    // Check if password contains uppercase letters
    if (!preg_match("/[A-Z]/", $password)) {
      $errors[] = "Password must contain at least one uppercase letter.";
    }
  
    // Check if password contains lowercase letters
    if (!preg_match("/[a-z]/", $password)) {
      $errors[] = "Password must contain at least one lowercase letter.";
    }
  
    // Check if password contains numbers
    if (!preg_match("/[0-9]/", $password)) {
      $errors[] = "Password must contain at least one number.";
    }
  
    // Check if password contains symbols
    if (!preg_match("/[!@#$%^&*(),.?]/", $password)) {
      $errors[] = "Password must contain at least one symbol.";
    }
  
    return $errors; // Return the array of error messages
  }

// Define variables and initialize with empty values
$username = $password = $confirm_password = $fullname = $email = $dateOfBirth = $phone_number = $SecurityQuestion1 = $SecurityQuestion2 = $SecurityQuestion3 = $SecurityQuestionAnswer1 = $SecurityQuestionAnswer2 = $SecurityQuestionAnswer3 = $thirdPassword ="";
$username_err = $password_err = $confirm_password_err = $fullname_err = $email_err = $dateOfBirth_err = $phone_number_err = $SecurityQuestion1_err = $SecurityQuestion2_err = $SecurityQuestion3_err = $SecurityQuestionAnswer1_err = $SecurityQuestionAnswer2_err = $SecurityQuestionAnswer3_err = $thirdPassword_err= "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    //validate fullname
    if(empty(trim($_POST["fullname"]))){
        $fullname_err = "Please enter Full Name.";
    } elseif(!preg_match('/^[a-zA-Z ]+$/', trim($_POST["fullname"]))){
        $fullname_err = "Full Name can only contain letters";
    } else{
        $fullname = trim($_POST["fullname"]);
    }

    //validate answer 1
    if(empty(trim($_POST["SecurityQuestion1"]))){
        $SecurityQuestion1_err = "Please enter Security Question 1.";
    } else{
        $SecurityQuestion1 = trim($_POST["SecurityQuestion1"]);
    }

    //validate Security Question Answer 1
    if(empty(trim($_POST["SecurityQuestionAnswer1"]))){
        $SecurityQuestionAnswer1_err = "Please enter the Answer.";
    } elseif(!preg_match('/^[a-zA-Z ]+$/', trim($_POST["SecurityQuestionAnswer1"]))){
        $SecurityQuestionAnswer1_err = "Answer Name can only contain letters and spaces";
    } else{
        $SecurityQuestionAnswer1 = trim($_POST["SecurityQuestionAnswer1"]);
    }
    
    //validate Question 2
    if(empty(trim($_POST["SecurityQuestion2"]))){
        $SecurityQuestion2_err = "Please enter Security Question 2.";
    } else{
        $SecurityQuestion2 = trim($_POST["SecurityQuestion2"]);
    }

    //validate Security Question Answer 2
    if(empty(trim($_POST["SecurityQuestionAnswer2"]))){
        $SecurityQuestionAnswer2_err = "Please enter the Answer.";
    } elseif(!preg_match('/^[a-zA-Z ]+$/', trim($_POST["SecurityQuestionAnswer2"]))){
        $SecurityQuestionAnswer2_err = "Answer Name can only contain letters and spaces";
    } else{
        $SecurityQuestionAnswer2 = trim($_POST["SecurityQuestionAnswer2"]);
    }
    
    //validate Question 3
    if(empty(trim($_POST["SecurityQuestion3"]))){
        $SecurityQuestion3_err = "Please enter Security Question 3.";
    }else{
        $SecurityQuestion3 = trim($_POST["SecurityQuestion3"]);
    }

    //validate Security Question Answer 3
    if(empty(trim($_POST["SecurityQuestionAnswer3"]))){
        $SecurityQuestionAnswer3_err = "Please enter the Answer.";
    } elseif(!preg_match('/^[a-zA-Z ]+$/', trim($_POST["SecurityQuestionAnswer3"]))){
        $SecurityQuestionAnswer3_err = "Answer Name can only contain letters and spaces";
    } else{
        $SecurityQuestionAnswer3 = trim($_POST["SecurityQuestionAnswer3"]);
    }    

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM userdetails WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

        // Validate email
        if(empty(trim($_POST["email"]))){
            $email_err = "Please enter email.";
        } elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            $email_err = "Invalid email id";
        } else{
            // Prepare a select statement
            $sql = "SELECT id FROM userdetails WHERE email = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                
                // Set parameters
                $param_email = trim($_POST["email"]);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    /* store result */
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $email_err = "This email is already associated with another account.";
                    } else{
                        $email = trim($_POST["email"]);
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    
    // Validate password
    $password_err = validatePassword($_POST["password"]);
    if(empty($passErrors)){
        $password = trim($_POST["password"]); 
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check if date of birth is valid
    if (empty(trim($_POST["dob"]))) {
        $dateOfBirth_err = "Please enter date of birth";
    } else {
        $dateOfBirth = $_POST["dob"];
    }
        // Check if date of birth is valid
    if (empty(trim($_POST["thirdPassword"]))) {
        $thirdPassword_err = "Please select an image for your third password";
    } else {
        $thirdPassword = $_POST["thirdPassword"];
    }

    //validate phone number
    if (preg_match("/^[0-9]{10}$/", $_POST["phone_number"])) {
        $phone_number=$_POST["phone_number"];
      } else {
        $phone_number_err = "Please enter a valid phone number.";
      }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($fullname_err) && empty($email_err) && empty($dateOfBirth_err) && empty($phone_number_err) && empty($SecurityQuestion1_err) && empty($SecurityQuestion2_err) && empty($SecurityQuestion3_err) && empty($SecurityQuestionAnswer1_err) && empty($SecurityQuestionAnswer2_err) && empty($SecurityQuestionAnswer3_err)&& empty($thirdPassword_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO userdetails (FullName, username, firstPassword, Email, DOB, PhoneNumber, secondPasswordFirstAnswer, SecondPasswordSecondAnswer, SecondPasswordThirdAnswer, SecondPasswordfirstQuestion, SecondPasswordsecondQuestion, SecondPasswordthirdQuestion, thirdPassword) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssssss", $param_fullname,$param_username, $param_password,$param_email,$param_dateOfBirth,$param_phone_number, $param_secondPasswordFirstAnswer, $param_secondPasswordSecondAnswer, $param_secondPasswordThirdAnswer, $param_secondPasswordfirstQuestion, $param_secondPasswordsecondQuestion, $param_secondPasswordthirdQuestion, $param_thirdPassword);
            
            $secondPasswordfirstQuestion = $SecurityQuestion1; 
            $secondPasswordsecondQuestion = $SecurityQuestion2; 
            $secondPasswordthirdQuestion = $SecurityQuestion3; 
            $secondPasswordFirstAnswer = $SecurityQuestionAnswer1; 
            $secondPasswordSecondAnswer = $SecurityQuestionAnswer2; 
            $secondPasswordThirdAnswer = $SecurityQuestionAnswer2; 
            $imgThirdPassword  = $thirdPassword;

            // Set parameters
            $param_fullname = $fullname;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;
            $param_dateOfBirth = $dateOfBirth;
            $param_phone_number = $phone_number;
            $param_secondPasswordFirstAnswer = $secondPasswordFirstAnswer; 
            $param_secondPasswordSecondAnswer = $secondPasswordSecondAnswer;
            $param_secondPasswordThirdAnswer = $secondPasswordThirdAnswer;
            $param_secondPasswordfirstQuestion = $secondPasswordfirstQuestion;
            $param_secondPasswordsecondQuestion = $secondPasswordsecondQuestion;
            $param_secondPasswordthirdQuestion = $secondPasswordthirdQuestion;
            $param_thirdPassword = $thirdPassword;

            echo "<script>alert('".$param_fullname."  " .$param_username."  " . $param_password."  " .$param_email."  " .$param_dateOfBirth."  " .$param_phone_number."  " . $param_secondPasswordFirstAnswer."  " . $param_secondPasswordSecondAnswer."  " . $param_secondPasswordThirdAnswer."  " . $param_secondPasswordfirstQuestion."  " . $param_secondPasswordsecondQuestion."  " . $param_secondPasswordthirdQuestion."  " . $param_thirdPassword."');";
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
        else{
            echo "<script>alert('hello');</script>";
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="fullname" class="form-control <?php echo (!empty($fullname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fullname; ?>">
                <span class="invalid-feedback"><?php echo $fullname_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control <?php echo (!empty($dateOfBirth_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dateOfBirth; ?>">
                <span class="invalid-feedback"><?php echo $dateOfBirth_err; ?></span>
            </div> 
            <div class="form-group">
                <label>email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone_number" class="form-control <?php echo (!empty($phone_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone_number; ?>">
                <span class="invalid-feedback"><?php echo $phone_number_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>   
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php    if(is_array($password_err)){ foreach ($password_err as $perror) { echo "- $perror<br>";} }?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
            <label>Security Question 1</label>
            <select name="SecurityQuestion1" class="form-control <?php echo (!empty($SecurityQuestion1_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $SecurityQuestion1; ?>"
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
            </select><input name="SecurityQuestion1" style="display:none;" disabled="disabled" class="form-control <?php echo (!empty($SecurityQuestion1_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $SecurityQuestion1; ?>"
                onblur="if(this.value==''){toggleField(this,this.previousSibling);}">
            </div>

            <div class="form-group">
                <label>Security Question Answer 1</label>
                <input type="text" name="SecurityQuestionAnswer1" class="form-control <?php echo (!empty($SecurityQuestionAnswer1_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $SecurityQuestionAnswer1; ?>">
                <span class="invalid-feedback"><?php echo $SecurityQuestionAnswer1_err; ?></span>
            </div>

            <div class="form-group">
            <label>Security Question 2</label>
            <select name="SecurityQuestion2" class="form-control <?php echo (!empty($SecurityQuestion2_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $SecurityQuestion2; ?>"
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
            </select><input name="SecurityQuestion2" style="display:none;" disabled="disabled" class="form-control <?php echo (!empty($SecurityQuestion2_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $SecurityQuestion2; ?>"
                onblur="if(this.value==''){toggleField(this,this.previousSibling);}">
            </div>
            <div class="form-group">
                <label>Security Question Answer 2</label>
                <input type="text" name="SecurityQuestionAnswer2" class="form-control <?php echo (!empty($SecurityQuestionAnswer2_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $SecurityQuestionAnswer2; ?>">
                <span class="invalid-feedback"><?php echo $SecurityQuestionAnswer2_err; ?></span>
            </div>

            <div class="form-group">
            <label>Security Question 3</label>
            <select name="SecurityQuestion3" class="form-control <?php echo (!empty($SecurityQuestion3_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $SecurityQuestion3; ?>"
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
            </select><input name="SecurityQuestion3" style="display:none;" disabled="disabled" class="form-control <?php echo (!empty($SecurityQuestion3_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $SecurityQuestion3; ?>"
                onblur="if(this.value==''){toggleField(this,this.previousSibling);}">
            </div>
            <div class="form-group">
                <label>Security Question Answer 3</label>
                <input type="text" name="SecurityQuestionAnswer3" class="form-control <?php echo (!empty($SecurityQuestionAnswer3_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $SecurityQuestionAnswer3; ?>">
                <span class="invalid-feedback"><?php echo $SecurityQuestionAnswer3_err; ?></span>
            </div>
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
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>