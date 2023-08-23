<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
else if(isset($_SESSION["firstPassword"]) && $_SESSION["firstPassword"] === true){
    header("location: secondPassword.php");
    exit;
}
 
// Include config file
require_once "dbConfig.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
                        $username = trim($_POST["username"]);
                    } else{
                        $username_err = "<sup class='error'>This username does not exist.</sup>";
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
    
                // Close statement
                mysqli_stmt_close($stmt);
            }
        } 
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "<sup class='error'>Please enter your password.</sup>";
    } else{
        $password = trim($_POST["password"]);
    }  
    // Validate credentials
    if(empty($username_err) && empty($password_err)){    
            // Set parameters
            $param_username = $username;
            
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["firstPassword"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;  
                            $_SESSION["firstPasswordValue"] = $password;
                            
                            // Redirect user to welcome page
                            header("location: secondPassword.php");
                        }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/mvp.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main>
        <section>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <header>
                <h2>Login</h2>
                <p>Please fill in your credentials to login.</p>
                </header>

                <label>Username<?php echo $username_err; ?></label>
                <input type="text" name="username" style="width: auto;" value="<?php echo $username; ?>">

                <label>Password <?php echo $password_err; ?></label>
                <input type="password" name="password" value="">

                <input type="submit" class="btn btn-primary" value="Login">
                <p>Don't have an account? <a href="userRegister.php">Sign up now</a>.</p>
                <p>Forgot Your Password? <a href="forgotPassword.php">Click here</a>.</p>
        </form>
        </section>
    </main>
</body>
</html>