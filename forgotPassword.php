<?php
// Initialize the session
session_start();
 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Include config file
require_once "dbConfig.php";
require 'vendor\phpmailer\phpmailer\src\Exception.php';
require 'vendor\phpmailer\phpmailer\src\PHPMailer.php';
require 'vendor\phpmailer\phpmailer\src\SMTP.php';
 
// Define variables and initialize with empty values
$email_err = "";

function generateTempPassword($length = 14) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%&*_+';
    $charLength = strlen($chars);
    $randomString = '#';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $chars[rand(0, $charLength - 1)];
    }
    return $randomString;
}

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
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
                                // Prepare an update statement
                                $sql = "UPDATE userdetails SET tempPassword = ? WHERE email = ?";
                                
                                if($stmt = mysqli_prepare($link, $sql)){
                                    // Bind variables to the prepared statement as parameters
                                    mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_Email);
                                    
                                    // Set parameters
                                    $param_password = generateTempPassword();
                                    $param_Email = $param_email;
                                    
                                    // Attempt to execute the prepared statement
                                    if(mysqli_stmt_execute($stmt)){
                                        // session_start();                      
                                        // Store data in session variables
                                        $_SESSION["resetPass"] = true;
                                        $_SESSION["resetEmail"] = trim($_POST["email"]);

                                        $mail = new PHPMailer();
                                        $mail->IsSMTP();
                                      
                                        $mail->SMTPDebug  = 0;  
                                        $mail->SMTPAuth   = TRUE;
                                        $mail->SMTPSecure = "tls";
                                        $mail->Port       = 587;
                                        $mail->Host       = "smtp.gmail.com";
                                        $mail->Username   = "rg007.rg1819@gmail.com";
                                        $mail->Password   = "vnvzzxzumzhbcufq";
                                      
                                        $mail->IsHTML(true);
                                        $mail->AddAddress($_POST["email"], "recipient-name");
                                        $mail->SetFrom("rg007.rg1819@gmail.com", "set-from-name");
                                        $mail->AddReplyTo("reply-to-email", "reply-to-name");
                                        $mail->AddCC("cc-recipient-email", "cc-recipient-name");
                                        $mail->Subject = "Your Temporary Password";
                                        $content = "Here is temporary password <b>".$param_password."</b>. Please use it reset your account";
                                      
                                        $mail->MsgHTML($content); 
                                        if(!$mail->Send()) {
                                          echo "<script>alert('There is an error. Please try again later.');</script>";
                                          var_dump($mail);
                                        } else {
                                          echo "<script>alert('A temporary password has been sent to your email.');</script>";
                                        }

                                        header("location: forgotpassword2.php");
                                        exit();
                                    } else{
                                        echo "Oops! Something went wrong. Please try again later.";
                                    }

                                    // Close statement
                                    mysqli_stmt_close($stmt);
                                }
                    } else{
                        $email_err = "<sup class='error'>This email is not associated with any account.</sup>";
                    }
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
    <title>Reset Password</title>
    <link rel="stylesheet" href="assets/mvp.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <main>
        <section>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>

                <label>Email <?php echo $email_err; ?></label>
                <input type="email" name="email"  >
                <input type="submit" class="btn btn-primary" value="Submit">
        </form>
</section>
</main>    
</body>
</html>