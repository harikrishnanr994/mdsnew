<?php 
        require_once("config.php");
	$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die ("connection failed");
        $password=random_password();
        $sqlString = "SELECT email FROM sqra";
        $result=mysqli_query($con,$sqlString) OR die(mysqli_error($con));
        if($result)
            {
               while($row = mysqli_fetch_array($result))
               {
                     $pass=random_password();
	             $email=$row['email'];
                     $sqlString = "UPDATE sqra SET password='$pass' WHERE email='$email'";
                     $detail=mysqli_query($con,$sqlString) OR die(mysqli_error($con));
                     $msg = "The new password is ".$pass;
                     mail($email,"Password Change",$msg);
               }
            }
       
        function random_password( $length = 8 ) {
         $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
         $password = substr( str_shuffle( $chars ), 0, $length );
         return $password;
       }
?>