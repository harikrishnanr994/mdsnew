<?php
   require_once("config.php");
  if(isset($_GET['imei'])&&isset($_GET['count']))
   {
         $imei=$_GET['imei'];
         $spaceid=$_GET['count'];
         $data = array();
         $alivephones="INSERT INTO phones (imei,space)VALUES ($imei,$spaceid)";
         $result1 = mysqli_query($con, $alivephones);
            if (mysqli_num_rows($result1) > 0) {
               // output data of each row
                while($row1 = mysqli_fetch_assoc($result1)) {
                 $data[] = $row1;
                }
           print json_encode($data,JSON_PRETTY_PRINT);
             } else {
               echo "0 results";
        }       
  }
?>