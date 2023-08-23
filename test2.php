<?php

require "dbConfig.php";
$sql = "SELECT id, username, firstPassword FROM userdetails WHERE email = ?";
$password="a";        
if($stmt = mysqli_prepare($link, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    
    // Set parameters
    $param_username = $_GET['p'];
    
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        // Store result
        mysqli_stmt_store_result($stmt);
        
        // Check if username exists, if yes then verify password
        if(mysqli_stmt_num_rows($stmt) == 1){                    
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
            if(mysqli_stmt_fetch($stmt)){
                if(password_verify($password, $hashed_password)){
                    // Password is correct, so start a new session
                    session_start();
                    
                    // Store data in session variables
                    $_SESSION["firstPassword"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["username"] = $username;                            
                    
                    // Redirect user to welcome page
                    header("location: secondPassword.php");
                } else{
                    // Password is not valid, display a generic error message
                    $login_err = "Invalid username or password.";
            echo $id;

                }
            }
        } else{
            // Username doesn't exist, display a generic error message
            $login_err = "Invalid username or password.";

            echo $login_err;
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    mysqli_stmt_close($stmt);
}



?>