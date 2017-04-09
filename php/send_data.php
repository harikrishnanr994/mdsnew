<?php
   require_once("config.php");
   $con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die ("connection failed");
  if(isset($_GET['imei'])&&isset($_GET['fileid'])&&isset($_GET['pieceid'])&&isset($_GET['data']))
   {
         $imei=$_GET['imei'];
         $fileid=$_GET['fileid'];
         $pieceid=$_GET['pieceid'];
         $filedata=$_GET['data'];
         $data = array();
         $datafetch="INSERT INTO receive (imei,fileid,pieceid,data) VALUES ('$imei','$fileid','$pieceid','$filedata')";
         $result1 = mysqli_query($con, $datafetch);
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