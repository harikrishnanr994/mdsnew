<?php
require_once("config.php");
$con = mysqli_connect($host,$user,$password,$db)or die ("connection failed");
if ($con==TRUE){
  echo "Connected<br>";
}
$dir = dirname(__FILE__).'/hari/';
echo $dir."<br>";
$filename = $dir.'hari.pdf';
echo $filename."<br>";
$file = sha1_file($filename);
$filename2=$dir.$file.'.txt';
echo $file."<br>";
$action=$_GET['id'];
if ($action==1){
  $result=compress($filename,$file,$dir,$con);
  if($result==TRUE){
    echo "compressed";
  }
  else{
    echo "moonji";
  }
}
elseif($action==3){
  $encrypted_text1 = file_get_contents($filename2);
  $result=decrypt($encrypted_text1,$file,$dir,$con);
  if($result==TRUE){
    fclose($dir.$file.'.zip');
    unlink($dir.$file.'.zip') or die("Couldn't delete zip");
    echo "<br>File not corrupt";
  }
  else{
    echo "<br>File corrupt";
  }
}
else{
  echo "not working";
}
function compress($filename1,$file,$dir,$con){
if (file_exists($filename1)) {
$zip = new ZipArchive;
if ($zip->open($dir.$file.'_1.zip', ZipArchive::CREATE) === TRUE)
{
    // Add files to the zip file
    $result=$zip->addFile('hari.pdf');
    if($result==TRUE){
      echo "<br>zip created<br>";
      }
    $zip->close();
    $zipfile = fread(fopen($dir.$file.'_1.zip', "r"), filesize($dir.$file.'_1.zip'));
    $file_to_encrypt = base64_encode($zipfile);
    echo $file_to_encrypt;
    echo file_put_contents($dir.$file.'_1.txt',$file_to_encrypt);
    $result = encrypt($file_to_encrypt,$file,$dir,$con);
    if($result==TRUE){
      echo "<br>this is encrypted";
    }
    else {
      echo "not encrypted";
    }
  }
}
else {
echo "recheck the link file maybe broken";
}
}
function encrypt($file_to_encrypt,$file,$dir,$con)
{
  $encrypted_file =  openssl_encrypt($file_to_encrypt,"AES-256-CBC",$file,OPENSSL_ZERO_PADDING,1234567898765432);
  echo file_put_contents($dir.$file.'.txt',$encrypted_file);
  unlink($dir.$file.'_1.zip') or die("Couldn't delete _1.zip");
  unlink($dir.$file.'_1.txt') or die("Couldn't delete _1.txt");
  $fileid = $file;
  $pieceid = "1";
  $data = $encrypted_file;
  $sqlString = "INSERT INTO encrypted(fileid,pieceid,data) VALUES('$fileid','$pieceid','$data')";
  echo "<br>".$sqlString;
  $detail = mysqli_query($con,$sqlString) OR die(mysqli_error($con));
  echo "<br> Success";
  return($encrypted_file);
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
function decompress($filename1,$dir){
$zip = new ZipArchive;
$res = $zip->open($filename1);
if ($res === TRUE) {
  $zip->extractTo($dir.'/decompressed/');
  $zip->close();
  echo 'extracted!';
} else {
  echo 'Fucked up';
}
$file1 = sha1_file($dir.'/decompressed/giri1.jpg');
return($file1);
}
?>
