<?php 
        require_once("config.php");
	$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die ("connection failed");
        $sqlString = "UPDATE data SET info=''";
        $detail = mysqli_query($con,$sqlString) OR die(mysqli_error($con));
?>