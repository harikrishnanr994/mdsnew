<?php
require_once("config.php");

$con = mysqli_connect($host,$user,$password,$db)or die ("connection failed");
if ($con==TRUE){
  echo "<br>Connected<br>";
  }

if(isset($_GET['fileid'])&&isset($_GET['pieceid']))
   {
         $file=$_GET['fileid'];
         $pieceid=$_GET['pieceid'];
   }


  $dir = __DIR__ . '/../../files/EEDC1600047';
  echo $dir."<br>";

  $filename = $dir.'giri1.jpg';
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
      echo "Not compressed";
    }
  }

  elseif($action==3)
  {

           $sqlstring1="SELECT data FROM encrypted WHERE fileid='$file' order by pieceid";
           $result1=mysqli_query($con,$sqlstring1);
           $encrypted_text1 = "";
           $number=mysqli_num_rows($result1);

              if ($number > 0){
                 // output data of each row
                  while($row1 = mysqli_fetch_assoc($result1)) {
                    $encrypted_text1.=$row1['data'];
               }
             }else {
                 echo "0 results";
          }



         //print json_encode($encrypted_text1, JSON_PRETTY_PRINT);


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

function compress($filename1,$file,$dir,$con)
{

  if (file_exists($filename1))
  {
      $zip = new ZipArchive;
      if ($zip->open($dir.$file.'_1.zip', ZipArchive::CREATE) === TRUE)
      {
        $result=$zip->addFile('giri1.jpg');
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
  else
  {
    echo "recheck the link file maybe broken";
  }
}

function encrypt($file_to_encrypt,$file,$dir,$con)
{
  $encrypted_file =  openssl_encrypt($file_to_encrypt,"AES-256-CBC",$file,OPENSSL_ZERO_PADDING,1234567898765432);
  echo file_put_contents($dir.$file.'.txt',$encrypted_file);
  unlink($dir.$file.'_1.zip') or die("Couldn't delete _1.zip");
  unlink($dir.$file.'_1.txt') or die("Couldn't delete _1.txt");
  $data = $encrypted_file;
  split1($file,1,$data,$con,$dir);
}
function split1($fileid,$pieceid,$data,$con,$dir){
  $fileid=$fileid;
  $data1 = substr($data, 0, 102400);
  $theRest = substr($data, 102400);
  if($theRest==TRUE){
  echo $dir.$fileid."<br>";
  $sqlString = "INSERT INTO encrypted(fileid,pieceid,data) VALUES('$fileid','$pieceid','$data1')";
  $detail = mysqli_query($con,$sqlString) OR die(mysqli_error($con));
  echo "<br> Success";
  echo $pieceid+=1;

  split1($fileid,$pieceid,$theRest,$con,$dir);
  }
  else{
    echo "completed";
  }
}
function decrypt($encrypted_text,$file,$dir,$con)
{
    echo "<br>".$encrypted_text;
  $decrypted_file = openssl_decrypt($encrypted_text,"AES-256-CBC",$file,OPENSSL_ZERO_PADDING,1234567898765432);
  echo "<br>".$decrypted_file."<br>";

  if(file_put_contents($dir.$file.'.txt',$decrypted_file)==TRUE)
  echo "<br>decrypted<br>";
  else
  echo "decryption Failed";

  $file_to_decrypt=base64_decode($decrypted_file);
  if(file_put_contents($dir.$file.'.zip',$file_to_decrypt)==TRUE)
  echo "<br>converted<br>";
  else
  echo "conversion Failed";

  $decompressed = decompress($dir.$file.'.zip',$dir);
  if ($decompressed == $file)
  {
    return(TRUE);

  }
  else
  {
    echo "File corrupted";
  }

}

function decompress($filename1,$dir)
{
  $zip = new ZipArchive;
  $res = $zip->open($filename1);
  if ($res === TRUE)
  {
    $zip->extractTo($dir.'/decompressed/');
    $zip->close();
    echo 'extracted!';
  }
  else
  {
    echo 'Not extracted';
  }
  //$file1 = sha1_file($dir.'decompressed/giri1.jpg');
  return($file1);
}
?>
