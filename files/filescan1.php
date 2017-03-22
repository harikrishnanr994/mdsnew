<?php
error_reporting(E_ALL);
/*
* code by Abhishek R. Kaushik
* Code for Read Recursive folders and files
*/
  require_once("config.php");


function dirFirstLevel($dir) {

   $result = array();

   $cdir = scandir($dir);
   foreach ($cdir as $key => $value)
   {
      if (!in_array($value,array(".","..")))
      {
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
         {
            $result[$value] = dirFirstLevel($dir . DIRECTORY_SEPARATOR . $value);
		 }
         else
         {
            $result[] = $value;
         }
      }
   }

   return $result;
}
echo __DIR__ .'/';
$o = dirFirstLevel(__DIR__ .'/');  // replace root_folder with your root directory from which you have to find the recursive folder, files structure
oep($o,'-1',__DIR__ .'/');

function oep($o,$id,$path){
	   $parent_id =$id;

	   $rPath ='';
foreach($o as $key => $val)
{
	//	print_r($val);
	if(is_array($val))
	{
		$rPath = $path.DIRECTORY_SEPARATOR;
		$qry = "insert into files(fileid,uuid,path)
		  values
		  ('".mysqli_real_escape_string($key,$con).','.$parent_id."')";
		   $res = mysqli_query($qry,$con);
		   $last_id = mysqli_num_rows($res);
		$rPath .= $key;
		oep($val,$last_id,$rPath);

	}
	else
	{
			 $qry = "insert into files(uuid,fileid,path)
		  values
		  ('EEDC1600047','".mysqli_real_escape_string($val,$con).",".$parent_id."')";
		   $res = mysqli_query($qry,$con);
	}
}
}
?>
