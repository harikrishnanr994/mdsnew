<?php

	require_once("config.php");

	if(isset($_GET['fileid']))
	{
	      $file=$_GET['fileid'];

	}
	else {
		echo "error in get fileid";
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
       	$title=$filename1;
    		set_time_limit(0);
    		$path = "hari/hari/";
    		//echo $path.$title;

  if(file_exists($path.$title) && is_file($path.$title))
{

    header('Content-Type: application/octet-stream');
    header('Content-Length: '.filesize($path.$title));
    header('Content-Disposition: filename='.$title);

    flush();
    $file = fopen($path.$title, "r");
    while(!feof($file))
    {
        // send the current file part to the browser
        print fread($file, round(1024*1024));
        // flush the content to the browser
        flush();
        // sleep one second
        sleep(1);
    }
    fclose($file);
		delTree($path);
	}
	else {
	    die('Error: The file '.$path.$title.' does not exist!');
	}

function delTree($dir) {
 if (is_dir($dir)) {
   foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $filename) {
     if ($filename->isDir()) continue;
     unlink($filename);
   }
   rmdir($dir);
 }
 }
