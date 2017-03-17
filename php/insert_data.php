<?php
   require_once("config.php");
  if(isset($_GET['imei'])&&isset($_GET['fileid'])&&isset($_GET['pieceid']))
   {
         $imei=$_GET['imei'];
         $fileid=$_GET['fileid'];
         $pieceid=$_GET['pieceid'];
         $sqlstring="SELECT * FROM users WHERE imei='$imei'";
         $result=mysqli_query($con,$sqlstring);
         $data = array();
         $row = $result->fetch_row();
         $sqlstring1="SELECT * FROM encrypted WHERE fileid='$fileid' AND pieceid='$pieceid'";
         $result1=mysqli_query($con,$sqlstring1);
         $data = array();
         $data[] = $result1->fetch_row(MYSQL_ASSOC);
        echo json_encode($data,JSON_FORCE_OBJECT);
  }

?>