<?php
if(isset($_GET['file']))
{
    // $_GET['file'] 即為傳入要下載檔名的引數
    header("Content-type:application");
    $path = $_GET['th']."/".$_GET['file'];
	header("Content-Length: " .(string)(filesize($path)));
    header("Content-Disposition: attachment; filename=".$_GET['file']);
	readfile($path);
}

?>