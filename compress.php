<?php
   function zippy($dir,$con,$file_to_compress,$file){
     echo $file;
   $zipname = "zips/".$file.'.zip';
    $zip = new ZipArchive;
    if ($zip->open($zipname,  ZipArchive::CREATE))
    {
     echo 'Archive created!';
     $result=$zip->addFile($dir.$file_to_compress);
     if($result==TRUE)
     {
      echo 'file added!';
      }
      else
      {
       echo "file not added";
      }
      $zip->close();
     }
     else
     {
      echo 'Failed!';
      }
    $file = sha1_file($dir.$file_to_compress);
    $file_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $file_key=base64_encode($file);
    $encryption=compress($zipname,$file,$dir,$con,$file_to_compress,$file_iv,$file_key);
    if($encryption==TRUE)
    {
      echo "Encrypted";
      unlink($dir.$file_to_compress);
      unlink("zips/".$file.'.zip');
    }
    else
    {
      echo "Not Encrypted";
    }
    return("Success");
   }

   function compress($filename1,$file,$dir,$con,$file_to_compress,$file_iv,$file_key)
   {
    if (file_exists($filename1))
    {
      $file_contents=file_get_contents($filename1);
      $file_to_encrypt =base64_encode($file_contents);
      echo "Base_encode".'<br/>';
      echo $file_to_encrypt;
      $encrypted_data = my_encrypt($file_to_encrypt,$file,$con,$file_key,$file_iv);
      echo "Encrypted Data".'<br>';
      echo $encrypted_data.'<br>';
      $pieceid=1;
      $encoded_iv= base64_encode($file_iv);
      $saveiv = "UPDATE files SET iv ='$encoded_iv' WHERE fileid='$file'";
      $detailiv = mysqli_query($con,$saveiv) OR die(mysqli_error($con));
      echo "Saved IV".'<br/>';
      echo "Split Function".'<br/>';
      $return=split($encrypted_data,$con,$file,1,$encoded_iv);

      if($return==TRUE)
      {

        echo "Completed".'<br/>';
        return TRUE;

      }
      else
      {
        echo "Splitting error".'<br/>';
      }
     }
     else
     {
       echo "recheck the link file maybe broken";
     }
  }
  function split($data,$con,$file,$i,$iv)
  {
   if(isset($data)&&!empty($data))
   {
    echo "Length Of encrypted data".'<br/>';
    echo strlen($data).'<br/>';
    $fileid = $file;
    $pieceid = $i;
    $data1 = substr($data,0, 102400);
    $theRest = substr($data,102400);
    echo $data1.'<br/>';
    $sqlString = "INSERT INTO encrypted(fileid,pieceid,data)VALUES('$fileid','$pieceid','$data1')";
    $detail = mysqli_query($con,$sqlString) OR die(mysqli_error($con));
    $data=$theRest;
    $i+=1;
    split($data,$con,$file,$i,$iv);
    return($detail);
    }
  }

  function my_encrypt($data,$file,$con,$key,$iv)
  {
   echo "Encryption".'<br/>';
   $encryption_key = base64_decode($key);
   $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key,0, $iv) or die("Encryption error");
   return base64_encode($encrypted . '::' . $iv);
  }

?>
