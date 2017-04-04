<?php
require_once("config.php");
require_once "zip.php";

$con = mysqli_connect($host,$user,$password,$db)or die ("connection failed");
if ($con==TRUE){
  echo "<br>Connected<br>";
}
if(isset($_GET['fileid'])&&isset($_GET['pieceid']))
   {
         $file=$_GET['fileid'];
         $pieceid=$_GET['pieceid'];
   }


  $dir = __DIR__ . '/hari/';
  echo $dir."<br>";

  $filename = $dir.'hari1.pdf';
  echo $filename."<br>";

  $file = sha1_file($filename);
  $filename2=$dir.$file.'.zip';
  echo $file."<br>";

  $action=$_GET['id'];
  if ($action==1){
    $result=compress($filename,$file,$dir,$con);
    if($result==TRUE){
      echo "compressed";
    }
    else{
      echo "Not compressed";
    }
  }
  if ($action==3){
    $result=decompress($filename2,$dir);
    if($result==TRUE){
      echo "compressed";
    }
    else{
      echo "Not compressed";
    }
  }


  function compress($filename1,$file,$dir,$con)
  {

    if (file_exists($filename1))
    {
          echo $filename1."<br>";
          $file_compress = base64_encode(file_get_contents($filename1));
          //echo $file_compress;
          $str = $file_compress;
          $file_to_encrypt = bzcompress($str, 9);

          $result = encrypt($file_to_encrypt,$file,$dir,$con);
          if($result==TRUE){
            echo "<br>this is encrypted";
          }
          else {
            echo "not encrypted";
          }


    }
    else
    {
      echo "recheck the link file maybe broken";
    }
  }

  function decompress($filename2,$dir)
  {
          $encrypted_text1 = file_get_contents($filename2);
          $str = bzdecompress($encrypted_text1);

          $file_decompress = base64_decode($str);

          file_put_contents($dir.'hari2.pdf',$file_decompress);

    }


    function encrypt($file_to_encrypt1,$file,$dir,$con)
    {
      $data =  openssl_encrypt($file_to_encrypt1,"AES-256-CBC",$file,OPENSSL_ZERO_PADDING,1234567898765432);
      echo $data;
      echo file_put_contents($dir.$file.'.txt',$data);
      //unlink($dir.$file.'.zip') or die("Couldn't delete.zip");
      $fileid = $file;
      $pieceid = "1";
      $sqlString = "INSERT INTO encrypted(fileid,pieceid,data) VALUES('$fileid','$pieceid','$data')";
      echo "<br>".$sqlString;
      //$detail = mysqli_query($con,$sqlString) OR die(mysqli_error($con));
      echo "<br> Success";
      return($detail);
    }
    function decrypt($encrypted_text,$file,$dir,$con)
    {
      $decrypted_file = openssl_decrypt($encrypted_text,"AES-256-CBC",$file,OPENSSL_ZERO_PADDING,1234567898765432);
      echo file_put_contents($dir.$file.'.txt',$decrypted_file);
      $file_to_decrypt = base64_decode($decrypted_file);
      echo file_put_contents($dir.$file.'.zip',$file_to_decrypt);
      echo "<br>decrypted<br>";
      $decompressed = decompress($dir.$file.'.zip',$dir);
     if ($decompressed == $file)
      return(TRUE);
     else {
       echo "File corrupted";
     }
    }
