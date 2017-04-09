<?php 
        require_once("config.php");
	$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die ("connection failed");
        if(isset($_GET['email']))
        {
             $email=$_GET['email'];
             $sqlString = "SELECT * FROM sqra WHERE email='$email'";
             $result=mysqli_query($con,$sqlString) OR die(mysqli_error($con));
             $row=$result->fetch_row();
             echo($row[1]."#".$row[2]);
        }

?>