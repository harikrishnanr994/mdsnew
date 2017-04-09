<?php 
        require_once("config.php");
	$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die ("connection failed");
        if(isset($_POST['email']) &&isset($_POST['string']))
        {
             $email=$_POST['email'];
             $string=$_POST['string'];
             $sqlString = "INSERT INTO sqra(email,string) VALUES('$email','$string')";
             $result=mysqli_query($con,$sqlString) OR die(mysqli_error($con));
             $pass=random_password();
             $sqlString = "UPDATE sqra SET password='$pass' WHERE email='$email'";
             $detail=mysqli_query($con,$sqlString) OR die(mysqli_error($con));
             $msg = "The new password is ".$pass;
             mail($email,"Password Change",$msg);
        }

         function random_password( $length = 8 ) {
         $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
         $password = substr( str_shuffle( $chars ), 0, $length );
         return $password;
       }
?>