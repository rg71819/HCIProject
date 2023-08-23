<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    if(!isset($_SESSION["thirdPassword"]) && $_SESSION["thirdPassword"] !== true){
        header("location: thirdPassword.php");
        exit;
    }
    else if(!isset($_SESSION["secondPassword"]) && $_SESSION["secondPassword"] !== true){
        header("location: secondPassword.php");
        exit;
    } else if(!isset($_SESSION["firstPassword"]) && $_SESSION["firstPassword"] !== true){
        header("location: login.php");
        exit;
    } 
}
?>
<?php
// Include config file
require_once "dbConfig.php";
 
// Define variables and initialize with empty values
$date = $breakfast = $lunch = $dinner = $breakFastTime = $lunchTime = $dinnerTime = $Blood_Sugar_Level_before_breakfast = $Time_of_Blood_Sugar_test_before_breakfast  = $Blood_Sugar_Level_after_dinner = $Time_of_Blood_Sugar_test_after_dinner = "";
$date_err = $breakfast_err = $lunch_err = $dinner_err = $breakFastTime_err = $lunchTime_err = $dinnerTime_err = $Time_of_Blood_Sugar_test_before_breakfast_err = $Blood_Sugar_Level_before_breakfast_err  = $Blood_Sugar_Level_after_dinner_err = $Time_of_Blood_Sugar_test_after_dinner_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Check if date is valid
        if (empty(trim($_POST["date"]))) {
            $date_err = "Please enter date";
        } else {
            $date = $_POST["date"];
        }

        if (!empty(trim($_POST["Time_of_Blood_Sugar_test_before_breakfast"]))) {
            $Time_of_Blood_Sugar_test_before_breakfast = $_POST["Time_of_Blood_Sugar_test_before_breakfast"];
        } 
        if (!empty(trim($_POST["Time_of_Blood_Sugar_test_after_dinner"]))) {
            $Time_of_Blood_Sugar_test_after_dinner = $_POST["Time_of_Blood_Sugar_test_after_dinner"];
        } 
        if (!empty(trim($_POST["breakfastTime"]))) {
            $breakFastTime = $_POST["breakfastTime"];
        } 

        if (!empty(trim($_POST["dinnerTime"]))) {
            $dinnerTime = $_POST["dinnerTime"];
        } 
        if (!empty(trim($_POST["lunchTime"]))) {
            $lunchTime = $_POST["lunchTime"];
        } 
    // Validate breakfast
    $input_breakfast = trim($_POST["breakfast"]);
    if(!empty($input_breakfast)){
        if(!filter_var($input_breakfast, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z \s]+$/")))){
            $breakfast_err = "Please enter a valid breakfast.";
        } else{
            $breakfast = $input_breakfast;
        }
    } 

    // Validate lunch
    $input_lunch = trim($_POST["lunch"]);
    if(!empty($input_lunch)){
        if(!filter_var($input_lunch, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z \s]+$/")))){
            $lunch_err = "Please enter a valid lunch.";
        } else{
            $lunch = $input_lunch;
        }
    } 
    // Validate dinner
    $input_dinner = trim($_POST["dinner"]);
    if(!empty($input_dinner)){
        if(!filter_var($input_dinner, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z \s]+$/")))){
            $dinner_err = "Please enter a valid dinner.";
        } else{
            $dinner = $input_dinner;
        } 
    }    

    
    // Validate sugar level
    $input_Blood_Sugar_Level_after_dinner = trim($_POST["Blood_Sugar_Level_after_dinner"]);
    if(!empty($input_Blood_Sugar_Level_after_dinner)){
        if(!ctype_digit($input_Blood_Sugar_Level_after_dinner)){
            $Blood_Sugar_Level_after_dinner_err = "Please enter a positive integer value.";
        } else{
            $Blood_Sugar_Level_after_dinner = $input_Blood_Sugar_Level_after_dinner;
        }    
    } 

        // Validate sugar level
        $input_Blood_Sugar_Level_before_breakfast = trim($_POST["Blood_Sugar_Level_before_breakfast"]);
        if(!empty($input_Blood_Sugar_Level_before_breakfast)){
            if(!ctype_digit($input_Blood_Sugar_Level_before_breakfast)){
                $Blood_Sugar_Level_before_breakfast_err = "Please enter a positive integer value.";
            } else{
                $Blood_Sugar_Level_before_breakfast = $input_Blood_Sugar_Level_before_breakfast;
            }    
        } 
    
    // Check input errors before inserting in database
    if(empty($breakfast_err) && empty($address_err) && empty($salary_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO userdiabetisdata ( username,testDate, Time_of_Blood_Sugar_test_before_breakfast, Blood_Sugar_Level_before_breakfast, Breakfast_Time, Breakfast, Lunch_Time, Lunch, Dinner_Time, Dinner, Time_of_Blood_Sugar_test_after_dinner, Blood_Sugar_Level_after_dinner) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssisssssssi", $param_username, $param_date, $param_Time_of_Blood_Sugar_test_before_breakfast, $param_Blood_Sugar_Level_before_breakfast, $param_breakFastTime, $param_breakfast, $param_lunchTime, $param_lunch, $param_dinnerTime, $param_dinner, $param_Time_of_Blood_Sugar_test_after_dinner, $param_Blood_Sugar_Level_after_dinner   );
            
            // Set parameters
            $param_username = $_SESSION['username'];
            $param_breakfast = $breakfast;
            $param_date = $date;
            $param_lunch = $lunch;
            $param_dinner = $dinner;
            $param_breakFastTime = $breakFastTime;
            $param_lunchTime = $lunchTime;
            $param_dinnerTime = $dinnerTime;
            $param_Blood_Sugar_Level_before_breakfast = $Blood_Sugar_Level_before_breakfast;
            $param_Time_of_Blood_Sugar_test_before_breakfast = $Time_of_Blood_Sugar_test_before_breakfast;
            $param_Blood_Sugar_Level_after_dinner = $Blood_Sugar_Level_after_dinner;
            $param_Time_of_Blood_Sugar_test_after_dinner = $Time_of_Blood_Sugar_test_after_dinner;

            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: create.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Time of Blood Sugar test  taken before breakfast</label>
                            <input type="time" name="Time_of_Blood_Sugar_test_before_breakfast" class="form-control <?php echo (!empty($Time_of_Blood_Sugar_test_before_breakfast_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Time_of_Blood_Sugar_test_before_breakfast; ?>">
                            <span class="invalid-feedback"><?php echo $Time_of_Blood_Sugar_test_before_breakfast_err;?></span>
                        </div>

                        <div class="form-group">
                            <label>Blood Sugar Level before breakfast</label>
                            <input type="text" name="Blood_Sugar_Level_before_breakfast" class="form-control <?php echo (!empty($Blood_Sugar_Level_before_breakfast_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Blood_Sugar_Level_before_breakfast; ?>">
                            <span class="invalid-feedback"><?php echo $Blood_Sugar_Level_before_breakfast_err;?></span>
                        </div>


                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
                            <span class="invalid-feedback"><?php echo $date_err; ?></span>
                        </div> 

                        <div class="form-group">
                            <label>Break Fast Time</label>
                            <input type="time" name="breakfastTime" class="form-control <?php echo (!empty($breakFastTime_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $breakFastTime; ?>">
                            <span class="invalid-feedback"><?php echo $breakFastTime_err; ?></span>
                        </div> 
                        <div class="form-group">
                            <label>Breakfast</label>
                            <input type="text" name="breakfast" class="form-control <?php echo (!empty($breakfast_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $breakfast; ?>">
                            <span class="invalid-feedback"><?php echo $breakfast_err;?></span>
                        </div>


                        <div class="form-group">
                            <label>Lunch Time</label>
                            <input type="time" name="lunchTime" class="form-control <?php echo (!empty($lunchTime_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lunchTime; ?>">
                            <span class="invalid-feedback"><?php echo $lunchTime_err; ?></span>
                        </div> 
                        <div class="form-group">
                            <label>Lunch</label>
                            <input type="text" name="lunch" class="form-control <?php echo (!empty($lunch_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lunch; ?>">
                            <span class="invalid-feedback"><?php echo $lunch_err;?></span>
                        </div>





                        <div class="form-group">
                            <label>Dinner Time</label>
                            <input type="time" name="dinnerTime" class="form-control <?php echo (!empty($dinnerTime_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dinnerTime; ?>">
                            <span class="invalid-feedback"><?php echo $dinnerTime_err; ?></span>
                        </div> 
                        <div class="form-group">
                            <label>Dinner</label>
                            <input type="text" name="dinner" class="form-control <?php echo (!empty($dinner_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dinner; ?>">
                            <span class="invalid-feedback"><?php echo $dinner_err;?></span>
                        </div>



                        <div class="form-group">
                            <label>Time of Blood Sugar test  taken After Dinner</label>
                            <input type="time" name="Time_of_Blood_Sugar_test_after_dinner" class="form-control <?php echo (!empty($Time_of_Blood_Sugar_test_after_dinner_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Time_of_Blood_Sugar_test_after_dinner; ?>">
                            <span class="invalid-feedback"><?php echo $Time_of_Blood_Sugar_test_after_dinner_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Blood Sugar Level after dinner</label>
                            <input type="text" name="Blood_Sugar_Level_after_dinner" class="form-control <?php echo (!empty($Blood_Sugar_Level_after_dinner_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Blood_Sugar_Level_after_dinner; ?>">
                            <span class="invalid-feedback"><?php echo $Blood_Sugar_Level_after_dinner_err;?></span>
                        </div>


                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>