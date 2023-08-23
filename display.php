<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
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
                    // Include config file
                    require_once "dbConfig.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM userdiabetisdata";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Date</th>";
                                        echo "<th>time taking test in AM</th>";
                                        echo "<th>Blood sugar level in am</th>";
                                        echo "<th>Breakfast menu</th>";
                                        echo "<th>Lunch menu</th>";
                                        echo "<th>Dinner menu</th>";
                                        echo "<th>Time taking test in PM</th>";
                                        echo "<th>Blood sugar level in PM</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['testDate'] . "</td>";
                                        echo "<td>" . $row['Time_of_Blood_Sugar_test_before_breakfast'] . "</td>";
                                        echo "<td>" . $row['Blood_Sugar_Level_before_breakfast'] . "</td>";
                                        echo "<td>" . $row['Breakfast'] . "</td>";
                                        echo "<td>" . $row['Lunch'] . "</td>";
                                        echo "<td>" . $row['Dinner'] . "</td>";
                                        echo "<td>" . $row['Time_of_Blood_Sugar_test_after_dinner'] . "</td>";
                                        echo "<td>" . $row['Blood_Sugar_Level_after_dinner'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>