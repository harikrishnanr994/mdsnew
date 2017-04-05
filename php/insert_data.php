<?php
   require_once("config.php");
  if(isset($_GET['imei'])&&isset($_GET['fileid'])&&isset($_GET['pieceid']))
   {
         $imei=$_GET['imei'];
         $fileid=$_GET['fileid'];
         $pieceid=$_GET['pieceid'];
         $data = array();
         $sqlstring1="SELECT fileid,pieceid,data FROM encrypted WHERE fileid='$fileid' and pieceid='$pieceid'";
         $result1 = mysqli_query($con, $sqlstring1);
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