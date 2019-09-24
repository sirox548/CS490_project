<?php
	$username = $_POST['username'];
	$hpwd = $_POST['password'];
	$hpwd = hash("sha256", $hpwd);
	$con = mysqli_connect("sql.njit.edu","rjb57", "******");
	if (!$con) {
		die('Could not connect: ' . mysqli_error($con));	
	}
	
	mysqli_select_db($con,"rjb57");
	$sql = "SELECT * FROM rjb57.CS490 WHERE username='".$username."';";
	$result = mysqli_query($con,$sql);
	if(!$result){
		echo "User doesn't exist";
		return;
	}
	$rows = mysqli_fetch_array($result);
	if($rows['password']==$hpwd){
		echo "Password correct";
	}
	else {
		echo "Password incorrect";
	}
?>