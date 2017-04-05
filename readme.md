to compress , encrypt and split the file . use compress1.php?id=1

the file is static now . need to set the file name while uploading itself. 

zip files are created in zips folder.

$filename is the name of file along with the path, $file is the hash, $dir is the directory, $con is the connection, $file_to_compres is the file to compress.
compress($filename,$file,$dir,$con,$file_to_compress);

$zipname = $dir."zips/".$file.'.zip'; : create a zip file in zips folder.

$file_to_encrypt = base64_encode(file_get_contents($zipname)); :  convert the zip file to base64 .

encrypt($file_to_encrypt,$file,$dir,$con); : encryption function. 

$iv = (hash('crc32', $file). hash('crc32', $file)); : create an IV from the hash of file.

$encrypted_text =  openssl_encrypt($file_to_encrypt1,"AES-256-CBC",$file,OPENSSL_RAW_DATA,$iv); : This is the encryption funtion of php. 

$data = base64_encode($encrypted_text); :t he output of the openssl_encrypt is byte data which cannot be saved . so it is converted to text.

split($data,$con,$file,1); : call the split function to split the string into 100KB each

$data1 = substr($data, 0, 102400); splitting the string into 100KB (102400 chars)

$theRest = substr($data, 102400); saving the rest into another variable.

$sqlString = "INSERT INTO encrypted(fileid,pieceid,data)VALUES('$fileid','$pieceid','$data1')";
       
$detail = mysqli_query($con,$sqlString) OR die(mysqli_error($con));


use compress1.php?id=3&fileid=hash of file();
///////// decrytion and decompression /////
if ($action==3){

$sqlstring="SELECT data FROM encrypted WHERE fileid='$file' order by pieceid ";  selecting all the data of a fileid and ordering it based on pieceid
$result=mysqli_query($con,$sqlstring);
$data=""; $ data is initialised as a string
$row = mysqli_fetch_assoc($result);
$data .=$row['data']; appending result to data .
echo $data; : here the data printed is the same as of the db but it is not detected when using find in browser.  
$encrypted_text1 =$data;

           //$result=decrypt($encrypted_text1,$file,$dir,$con);
           //if($result==TRUE){
             //fclose($dir.$file.'.zip');
             //unlink($dir.$file.'.zip') or die("Couldn't delete zip");
             //echo "<br>File not corrupt";
           //}
           //else{
             //echo "<br>File corrupt";
           //}
    }
    
    
    Decrypt is the reverse of the encrypt funtion
    function decrypt($encrypted_text,$file,$dir,$con)
    {
      $iv = (hash('crc32', $file). hash('crc32', $file));
      $encrypted_text1 = base64_decode($encrypted_text); :  The fetched data is again converted back to bytedata .
      $decrypted_file = openssl_decrypt($encrypted_text1,"AES-256-CBC",$file,OPENSSL_RAW_DATA,$iv);
      $file_to_decrypt = base64_decode($decrypted_file);
      echo file_put_contents($dir."zips/".$file.'_1.zip',$file_to_decrypt); 
      echo "<br>decrypted<br>"; decryption is possible but the file is corrupted when trying to decompress. 
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
