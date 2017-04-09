<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css" />
	<title>Download Files</title>
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

    font-size:14;
    font-weight:bold;
}
		</style>
</head>
<body  align="center">

<br>
<div class="container home">
<font face="comic sans ms">
<h3><center> List of Files the can be download </center> </h3>
</font>

 <table class="table table-bordered">
  <thead>
   <tr>

    <th><font face="comic sans ms">Filename </font></th>
	<th><font face="comic sans ms">Download Files </font></th>
  </tr>
   </thead>
    <tbody>
<?php

require "config.php";


	$q="SELECT count(*) \"total\"  from files1";
	$ros=mysqli_query($con,$q);
	$row=(mysqli_fetch_array($ros));
	$total=$row['total'];
	$dis=3;
	$total_page=ceil($total/$dis);
	$page_cur=(isset($_GET['page']))?$_GET['page']:1;
	$k=($page_cur-1)*$dis;
	$q="SELECT * FROM files";
	$ros=mysqli_query($con,$q);
	while($row=mysqli_fetch_array($ros))
	{
		echo '<tr>';
		echo '<td align=center>' . $row['filename'];
		echo "<td align=center><a title='Click here to download in file.'
		     href='download.php?fileid={$row['fileid']}'> Download</a>";
		echo '</tr>';

	}
	echo '</table>';
	echo  '</tbody>';
	echo '<br/>';


?>

</div>
</body>
</html>
