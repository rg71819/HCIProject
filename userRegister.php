<?php
// Include config file
require_once "dbConfig.php";
//validate password
function validatePassword($password) {
    $errors = array(); // Initialize an empty array to store error messages
  
    // Check password length
    if (strlen($password) < 8) {
      $errors[] = "<sup class='error'>Password must be at least 8 characters in length.</sup>";
    }
  
    // Check if password contains uppercase letters
    if (!preg_match("/[A-Z]/", $password)) {
      $errors[] = "<sup class='error'>Password must contain at least one uppercase letter.</sup>";
    }
  
    // Check if password contains lowercase letters
    if (!preg_match("/[a-z]/", $password)) {
      $errors[] = "<sup class='error'>Password must contain at least one lowercase letter.</sup>";
    }
  
    // Check if password contains numbers
    if (!preg_match("/[0-9]/", $password)) {
      $errors[] = "<sup class='error'>Password must contain at least one number.</sup>";
    }
  
    // Check if password contains symbols
    if (!preg_match("/[!@#$%^&*(),.?]/", $password)) {
      $errors[] = "<sup class='error'>Password must contain at least one symbol.</sup>";
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
        $fullname_err = "<sup class='error'>Please enter Full Name.</sup>";
    } elseif(!preg_match('/^[a-zA-Z ]+$/', trim($_POST["fullname"]))){
        $fullname_err = "<sup class='error'>Full Name can only contain letters</sup>";
    } else{
        $fullname = trim($_POST["fullname"]);
    }

    //validate answer 1
    if(empty(trim($_POST["SecurityQuestion1"]))){
        $SecurityQuestion1_err = "<sup class='error'>Please enter Security Question 1.</sup>";
    } else{
        $SecurityQuestion1 = trim($_POST["SecurityQuestion1"]);
    }

    //validate Security Question Answer 1
    if(empty(trim($_POST["SecurityQuestionAnswer1"]))){
        $SecurityQuestionAnswer1_err = "<sup class='error'>Please enter the Answer.</sup>";
    } elseif(!preg_match('/^[a-zA-Z ]+$/', trim($_POST["SecurityQuestionAnswer1"]))){
        $SecurityQuestionAnswer1_err = "<sup class='error'>Answer Name can only contain letters and spaces</sup>";
    } else{
        $SecurityQuestionAnswer1 = trim($_POST["SecurityQuestionAnswer1"]);
    }
    
    //validate Question 2
    if(empty(trim($_POST["SecurityQuestion2"]))){
        $SecurityQuestion2_err = "<sup class='error'>Please enter Security Question 2.</sup>";
    } else{
        $SecurityQuestion2 = trim($_POST["SecurityQuestion2"]);
    }

    //validate Security Question Answer 2
    if(empty(trim($_POST["SecurityQuestionAnswer2"]))){
        $SecurityQuestionAnswer2_err = "<sup class='error'>Please enter the Answer.</sup>";
    } elseif(!preg_match('/^[a-zA-Z ]+$/', trim($_POST["SecurityQuestionAnswer2"]))){
        $SecurityQuestionAnswer2_err = "<sup class='error'>Answer Name can only contain letters and spaces</sup>";
    } else{
        $SecurityQuestionAnswer2 = trim($_POST["SecurityQuestionAnswer2"]);
    }
    
    //validate Question 3
    if(empty(trim($_POST["SecurityQuestion3"]))){
        $SecurityQuestion3_err = "<sup class='error'>Please enter Security Question 3.</sup>";
    }else{
        $SecurityQuestion3 = trim($_POST["SecurityQuestion3"]);
    }

    //validate Security Question Answer 3
    if(empty(trim($_POST["SecurityQuestionAnswer3"]))){
        $SecurityQuestionAnswer3_err = "<sup class='error'>Please enter the Answer.</sup>";
    } elseif(!preg_match('/^[a-zA-Z ]+$/', trim($_POST["SecurityQuestionAnswer3"]))){
        $SecurityQuestionAnswer3_err = "<sup class='error'>Answer Name can only contain letters and spaces</sup>";
    } else{
        $SecurityQuestionAnswer3 = trim($_POST["SecurityQuestionAnswer3"]);
    }    

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "<sup class='error'>Please enter a username.</sup>";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "<sup class='error'>Username can only contain letters, numbers, and underscores.</sup>";
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
                    $username_err = "<sup class='error'>This username is already taken.</sup>";
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
            $email_err = "<sup class='error'>Please enter email.</sup>";
        } elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            $email_err = "<sup class='error'>Invalid email id</sup>";
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
                        $email_err = "<sup class='error'>This email is already associated with another account.</sup>";
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
        $confirm_password_err = "<sup class='error'>Please confirm password.</sup>";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "<sup class='error'>Password did not match.</sup>";
        }
    }

    // Check if date of birth is valid
    if (empty(trim($_POST["dob"]))) {
        $dateOfBirth_err = "<sup class='error'>Please enter date of birth</sup>";
    } else {
        $dateOfBirth = $_POST["dob"];
    }
        // Check if date of birth is valid
    if(isset($_POST['thirdPassword'])){
    if (empty(trim($_POST["thirdPassword"]))) {
        $thirdPassword_err = "<sup class='error'>Please select an image for your third password</sup>";
    } else {
        $thirdPassword = $_POST["thirdPassword"];
    }
    }else{
        $thirdPassword_err = "<sup class='error'>Please select an image for your third password</sup>";
    }

    //validate phone number
    if (preg_match("/^[0-9]{10}$/", $_POST["phone_number"])) {
        $phone_number=$_POST["phone_number"];
      } else {
        $phone_number_err = "<sup class='error'>Please enter a valid phone number.</sup>";
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
            $secondPasswordThirdAnswer = $SecurityQuestionAnswer3; 
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

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <!-- <header> -->
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <!-- </header> -->

                <label>Full Name<?php echo $fullname_err; ?></label>
                <input type="text"  name="fullname"  value="<?php echo $fullname; ?>">

                <label>Date of Birth<?php echo $dateOfBirth_err; ?></label>
                <input type="date"  name="dob"  value="<?php echo $dateOfBirth; ?>">

                <label>Email<?php echo $email_err; ?></label>
                <input type="email" name="email"   value="<?php echo $email; ?>">

                <label>Phone Number<?php echo $phone_number_err; ?></label>
                <input type="tel"  name="phone_number"  value="<?php echo $phone_number; ?>">

                <label>Username<?php echo $username_err; ?></label>
                <input type="text"  name="username"  value="<?php echo $username; ?>">

                <label>Password</label>
                <?php    if(is_array($password_err)){ foreach ($password_err as $perror) { echo "- $perror<br>";} }?>
                <input type="password"  name="password"  value="<?php echo $password; ?>">

                <label>Confirm Password<?php echo $confirm_password_err; ?></label>
                <input type="password"  name="confirm_password"  value="<?php echo $confirm_password; ?>">

            <label>Security Question 1<?php echo $SecurityQuestion1_err; ?></label>
            <select name="SecurityQuestion1"   value="<?php echo $SecurityQuestion1; ?>"
                    onchange="if(this.options[this.selectedIndex].value=='CustomQuestion1'){
                        toggleField(this,this.nextSibling);
                        this.selectedIndex='0';
                    }">
                <option value="">Please select a security question or write your own</option>
                <option value="CustomQuestion1">Add Your Own Question</option>
                <option value="What is the name of a college you applied to but didn’t attend?">What is the name of a college you applied to but didn’t attend?</option>
                <option value="What was the name of the first school you remember attending?">What was the name of the first school you remember attending?</option>
                <option value="Where was the destination of your most memorable school field trip?">Where was the destination of your most memorable school field trip?</option>
                <option value="What was your math’s teacher's surname in your 8th year of school?">What was your math’s teacher's surname in your 8th year of school?</option>
                <option value="What was the name of your first stuffed toy?">What was the name of your first stuffed toy?</option>
                <option value="What was your driving instructor's first name?">What was your driving instructor's first name?</option>
            </select><input name="SecurityQuestion1"  style="display:none;" disabled="disabled"  value="<?php echo $SecurityQuestion1; ?>"
                onblur="if(this.value==''){toggleField(this,this.previousSibling);}">

                <label>Security Question Answer 1<?php echo $SecurityQuestionAnswer1_err; ?></label>
                <input type="text" name="SecurityQuestionAnswer1"  value="<?php echo $SecurityQuestionAnswer1; ?>">


            <label>Security Question 2<?php echo $SecurityQuestion2_err; ?></label>
            <select name="SecurityQuestion2" value="<?php echo $SecurityQuestion2; ?>"
                    onchange="if(this.options[this.selectedIndex].value=='CustomQuestion2'){
                        toggleField(this,this.nextSibling);
                        this.selectedIndex='0';
                    }">
                <option value="">Please select a security question or write your own</option>
                <option value="CustomQuestion2">Add Your Own Question</option>
                <option value="What is the name of a college you applied to but didn’t attend?">What is the name of a college you applied to but didn’t attend?</option>
                <option value="What was the name of the first school you remember attending?">What was the name of the first school you remember attending?</option>
                <option value="Where was the destination of your most memorable school field trip?">Where was the destination of your most memorable school field trip?</option>
                <option value="What was your math’s teacher's surname in your 8th year of school?">What was your math’s teacher's surname in your 8th year of school?</option>
                <option value="What was the name of your first stuffed toy?">What was the name of your first stuffed toy?</option>
                <option value="What was your driving instructor's first name?">What was your driving instructor's first name?</option>
            </select><input name="SecurityQuestion2" style="display:none;" disabled="disabled"  value="<?php echo $SecurityQuestion2; ?>"
                onblur="if(this.value==''){toggleField(this,this.previousSibling);}">

                <label>Security Question Answer 2<?php echo $SecurityQuestionAnswer2_err; ?></label>
                <input type="text" name="SecurityQuestionAnswer2"  value="<?php echo $SecurityQuestionAnswer2; ?>">

            <label>Security Question 3<?php echo $SecurityQuestion3_err; ?></label>
            <select name="SecurityQuestion3"  value="<?php echo $SecurityQuestion3; ?>"
                    onchange="if(this.options[this.selectedIndex].value=='CustomQuestion3'){
                        toggleField(this,this.nextSibling);
                        this.selectedIndex='0';
                    }">
                <option value="">Please select a security question or write your own</option>
                <option value="CustomQuestion3">Add Your Own Question</option>
                <option value="What is the name of a college you applied to but didn’t attend?">What is the name of a college you applied to but didn’t attend?</option>
                <option value="What was the name of the first school you remember attending?">What was the name of the first school you remember attending?</option>
                <option value="Where was the destination of your most memorable school field trip?">Where was the destination of your most memorable school field trip?</option>
                <option value="What was your math’s teacher's surname in your 8th year of school?">What was your math’s teacher's surname in your 8th year of school?</option>
                <option value="What was the name of your first stuffed toy?">What was the name of your first stuffed toy?</option>
                <option value="What was your driving instructor's first name?">What was your driving instructor's first name?</option>
            </select><input name="SecurityQuestion3" style="display:none;" disabled="disabled"  value="<?php echo $SecurityQuestion3; ?>"
                onblur="if(this.value==''){toggleField(this,this.previousSibling);}">

                <label>Security Question Answer 3<?php echo $SecurityQuestionAnswer3_err; ?></label>
                <input type="text" name="SecurityQuestionAnswer3"  value="<?php echo $SecurityQuestionAnswer3; ?>">

            <div class="hiddenradio">
            <p><b>Select an image for your Third Password</b><?php echo $thirdPassword_err; ?></p>
                <?php
                for($i=1;$i<10;$i++)
                {
                    echo '<label style="display:inline-block;"><input type="radio" name="thirdPassword" value="'.$i.'.jpg" ><img src="pictures/'.$i.'.jpg" width=100 height=100 class="imageLarge"></label>';
                    if($i%3==0){
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
        </section>   
            </main> 
</body>
</html>