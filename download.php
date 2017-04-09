<?php

	require_once("config.php");

	if(isset($_GET['fileid']))
	{
	      $file=$_GET['fileid'];

	}
	else {
		echo "error in get fileid";
	}
	$encrypted_data ="SELECT data FROM encrypted WHERE fileid ='$file' order by pieceid * 1";
	$result=mysqli_query($con,$encrypted_data);
	$data=array();
	if (mysqli_num_rows($result) > 0)
	{
	 while($row = mysqli_fetch_assoc($result))
	 {
		$data[] = $row['data'];
		}
	 }
		else
		{
		 echo "0 results";
		}
		$filename ="SELECT filename FROM files WHERE fileid ='$file'";
		$result=mysqli_query($con,$filename);

		if (mysqli_num_rows($result) > 0)
		{
		 while($row = mysqli_fetch_assoc($result))
		 {
			$filename1 = $row['filename'];
			}
		 }
			else
			{
			 echo "0 results";
			}

	 $decryption= decompress($file,$con,$data,$filename1);
	 if($decryption==TRUE)
	 {
		 $URL="downloadfile.php?fileid=".$file;
 	 	echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
 		echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';

	 }
	 else
	 {
	   echo "Not Decrypted";
	 }


	function decompress($file,$con,$data,$filename1)
	{
	   //echo "Decompress function".'<br/>';

	   $file_iv= "SELECT iv FROM files WHERE fileid='$file'";

	   $result2=mysqli_query($con,$file_iv);
	   $data1=array();
	   if (mysqli_num_rows($result2) > 0)
	   {
	    while($row = mysqli_fetch_assoc($result2))
	    {
	     $data1[] = $row;
	     }
	    }
	     else
	     {
	      echo "0 results";
	     }
	   $file_key=base64_encode($file);
	   $encoded_data= json_encode($data).'<br>';
	   $file_to_decrypt=str_replace(['{"data":"','[',']','}','"',','],'',$encoded_data);
	   $decrypted = my_decrypt($file_to_decrypt,$file_key,$data1,$filename1);
	   $filecontent=base64_decode($decrypted);
	   $filedirectory='hari/';
	   $zipdirectory= "zips/".$file.'_1.zip';
	   $size = file_put_contents($zipdirectory,$filecontent);
	   if ($size == FALSE)
	     {
	            echo "error writing file";;
	     }
	     else
	     {
	     echo "Decrypted Zip File Created".'<br/>';
	     $zip1 = new ZipArchive;
	     $res = $zip1->open($zipdirectory);
	     if ($res === TRUE)
	     {
	      $zip1->extractTo($filedirectory);
	      $zip1->close();
	      echo 'extracted!';
	     }
	     else
	     {
	      echo 'Fucked up';
	     }
	    // $file1 = sha1_file($dir.'hari/krish.pdf');
	    // return($file1);
	     }
	   return TRUE;
	}


	function my_decrypt($data, $key,$iv,$filename1)
	{
	  //echo "Decryption Part".'<br/>';
	  //echo $data.'<br/>';
	  //echo strlen($data).'<br/>';
	  //echo json_encode($iv).'<br/>';
	  $decryption_key = base64_decode($key);
	  list($splitted_data, $iv) = explode('::', base64_decode($data), 2);
	  //echo "Splitted data".'<br/>';
	  //echo $splitted_data.'<br/>';

	  $dd= openssl_decrypt($splitted_data,'aes-256-cbc', $decryption_key,0, $iv);

	  return $dd;
	}

?>
