<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css" />
<style type="text/css">
#wrapper {
margin: 0 auto;
float: none;
width:70%;
}
.header {
padding:10px 0;
border-bottom:1px solid #CCC;
}
.title {
padding: 0 5px 0 0;
float:left;
margin:0;
}
.container form input {
height: 30px;
}
body
{

font-size:12;
font-weight:bold;
}
</style>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Upload File</title>
</head>
<body  align="center">

<br>
<div class="container home">
<font face="comic sans ms">
<h3><center> Upload File </center> </h3>
</font>

<form action="fileupload.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload" name="submit">
</form>

</body>
</html>
