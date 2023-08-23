<?php

// Get the user id
$user_id = $_REQUEST['user_id'];

// Database connection
$con = mysqli_connect("localhost", "root", "", "hciproject");

if ($user_id !== "") {
	
	// Get corresponding first name and
	// last name for that user id	
	$query = mysqli_query($con, "SELECT  SecondPasswordfirstQuestion,
	SecondPasswordfirstQuestion FROM userdetails WHERE username='$user_id'");

	$row = mysqli_fetch_array($query);

	// Get the first name
	$first_name = $row["SecondPasswordfirstQuestion"];

	// Get the first name
	$last_name = $row["SecondPasswordfirstQuestion"];
}

// Store it in a array
$result = array("$first_name", "$last_name");

// Send in JSON encoded form
$myJSON = json_encode($result);
echo $myJSON;
?>
