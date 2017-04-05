<?php
   require_once("config.php");
   $con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE) or die ("connection failed");
   $sqlstring="SELECT imei FROM users";
   $result=mysqli_query($con,$sqlstring);
   $data = array();
   while(($row = mysqli_fetch_array($result))) {
             $data[] = $row['imei'];
             }
   function sendMessageThroughGCM($registration_ids, $message) {
		//Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );
		// Update your Google Cloud Messaging API Key
		define("GOOGLE_API_KEY", "AIzaSyAZkvlEZxGIsuMhhlvCLM750GiloYfEa78"); 		
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);	
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);				
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
  if(isset($_GET['imei']))
   {
         $imei=$_GET['imei'];
         $sqlstring="SELECT gcm_id FROM users WHERE imei='$imei'";
         $result=mysqli_query($con,$sqlstring);
         $data = array();
         $row = $result->fetch_row();
         $data[0]=$row[0];
         $gcm_id =$data[0];
         $sqlstring="SELECT * FROM send WHERE imei='$imei'";
         $result=mysqli_query($con,$sqlstring);
         $num_rows = mysqli_num_rows($result);
         echo $num_rows;
         if($num_rows!=0)
         {
         echo "Yoo";
         $field=array("type"=>"Checkphone");
         $message = array("m" => json_encode($field));
         $pushStatus = sendMessageThroughGCM($data, $message);	
         echo $pushStatus;
         }
         else
            echo "Error";
   }
?>