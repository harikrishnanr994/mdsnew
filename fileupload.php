<?php
require "config.php";
require "compress.php";
$target_dir = "hari/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 50000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        $filename=basename( $_FILES["fileToUpload"]["name"]);
        $fileid=sha1_file("hari/".$filename);
        echo "<br>".$fileid;
        $sqlString = "INSERT INTO files(filename,fileid)VALUES('$filename','$fileid')";
        $detail = mysqli_query($con,$sqlString) OR die(mysqli_error($con));
        zippy("hari/",$con,$filename,$fileid);
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
