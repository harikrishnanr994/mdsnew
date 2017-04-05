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
  $dir = __DIR__ . '/';
  echo $dir."<br>";

  $filename = $dir.'hari/krish.pdf';
  $file_to_compress='hari/krish.pdf';
  echo $filename."<br>";

  $file = sha1_file($filename);
  $filename2=$dir."zips/".$file.'.zip';;
  echo $file."<br>";

  $action=$_GET['id'];
  if ($action==1){
    $result=compress($filename,$file,$dir,$con,$file_to_compress);
    if($result==TRUE){
      echo "compressed";
    }
    else{
      echo "Not compressed";
    }
  }
  if ($action==3){

           $sqlstring="SELECT data FROM encrypted WHERE fileid='$file' order by pieceid ";
           $result=mysqli_query($con,$sqlstring);
           $data="";
           $row = mysqli_fetch_assoc($result);
           $data .=$row['data'];
           echo $data;
           $encrypted_text1 =$data;

           $result=decrypt($encrypted_text1,$file,$dir,$con);
           //if($result==TRUE){
             //fclose($dir.$file.'.zip');
             //unlink($dir.$file.'.zip') or die("Couldn't delete zip");
             //echo "<br>File not corrupt";
           //}
           //else{
             //echo "<br>File corrupt";
           //}
    }
    else{
      echo "not working";
    }


  function compress($filename1,$file,$dir,$con,$file_to_compress)
  {

    if (file_exists($filename1))
    {
          echo $filename1."<br>";
          $str = file_get_contents($filename1);


          $zipname = $dir."zips/".$file.'.zip';
          $zip = new ZipArchive;
          if ($zip->open($zipname,  ZipArchive::CREATE)) {
            echo 'Archive created!';
            $result=$zip->addFile($file_to_compress);
            if($result==TRUE){
              echo 'file added!';
            }
            else {
              echo "file not added";
            }
            $zip->close();

          } else {
              echo 'Failed!';
          }

          $file_to_encrypt = base64_encode(file_get_contents($zipname));

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

    function encrypt($file_to_encrypt1,$file,$dir,$con)
    { //echo $dir.$file.".txt";
      //echo "<br>".$file_to_encrypt1;
      $iv = (hash('crc32', $file). hash('crc32', $file));
      $encrypted_text =  openssl_encrypt($file_to_encrypt1,"AES-256-CBC",$file,OPENSSL_RAW_DATA,$iv);
      $data = base64_encode($encrypted_text);
      echo "<br>".$data;
      echo file_put_contents($dir.$file.".txt",$data) or die("Couldn't create txt");
      //unlink($dir.$file.'.zip') or die("Couldn't delete.zip");
      $return=split($data,$con,$file,1);
      if ($return==TRUE){echo "<br> Success";}
      else {
        echo "Not successful";
      }
      return($return);

    }

    function split($data,$con,$file,$i){
      if(isset($data)&&!empty($data)){
          $fileid = $file;
          $pieceid = $i;
          $data1 = substr($data, 0, 102400);
          $theRest = substr($data, 102400);
          $sqlString = "INSERT INTO encrypted(fileid,pieceid,data)VALUES('$fileid','$pieceid','$data1')";
          echo "<br>".$sqlString;
          $detail = mysqli_query($con,$sqlString) OR die(mysqli_error($con));
          $data=$theRest;
          $i+=1;

          split($data,$con,$file,$i);

          }
      return($detail);
    }
    function decrypt($encrypted_text,$file,$dir,$con)
    {
      $iv = (hash('crc32', $file). hash('crc32', $file));
      $encrypted_text1 = base64_decode($encrypted_text);
      $decrypted_file = openssl_decrypt($encrypted_text1,"AES-256-CBC",$file,OPENSSL_RAW_DATA,$iv);
      $file_to_decrypt = base64_decode($decrypted_file);
      echo file_put_contents($dir."zips/".$file.'_1.zip',$file_to_decrypt);
      echo "<br>decrypted<br>";
      $decompressed = decompress($dir.$file.'_1.zip',$dir);
     if ($decompressed == $file)
      return(TRUE);
     else {
       echo "File corrupted";
     }
    }
    function decompress($filename1,$dir){
    $zip = new ZipArchive;
    $res = $zip->open($filename1);
    if ($res === TRUE) {
      $zip->extractTo($dir);
      $zip->close();
      echo 'extracted!';
    } else {
      echo 'Fucked up';
    }
    $file1 = sha1_file($dir.'hari/krish.pdf');
    return($file1);
    }
?>
