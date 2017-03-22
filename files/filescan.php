<?php
function scanpath($path) {
    $myscan = scandir($path);
    $tree=[];
    foreach($myscan as $entry) {
        //echo '<br>'.$entry;
        if($entry==='.' || $entry ==='..') {

            // do nothing
        } else  if(is_dir($path.'/'.$entry)) {
            // this is a folder, I will recurse
            echo $path.'/'.$entry.'<br>';
            $tree[$entry] = scanpath($path.'/'.$entry);



        } else {
            // this is a file or link. Value is file size
             $tree[$entry] = filesize($path.'/'.$entry);
             
        }
    }
    return $tree;
}
$scanresult=scanpath(__DIR__);

echo '<pre>';
print_r($scanresult);
echo '</pre>';
?>
