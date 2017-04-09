<?php
   require_once("config.php");
   $con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die ("connection failed");
         $sqlString = "SELECT * FROM phones";
         $selectedphone = array();
         $spacedata = array();
         $datas = mysqli_query($con,$sqlString) OR die(mysqli_error($con));
         $rowsize = mysqli_num_rows($datas);
         while(($row = mysqli_fetch_array($datas)))
         {
           $spacedata[] = $row['space'];
           $selectedphone[] = $row['imei'];
         }
         $i=0;
         while($i<$rowsize)
         {
           if($spacedata[$i]<20)
           {
            echo $spacedata[$i].'<br/>';
            echo $selectedphone[$i].'<br/>';
           }
           $i++;
         }
?>