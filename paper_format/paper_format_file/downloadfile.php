<?php

if(isset($_GET['pa_ewfile'])){
    // $_GET['pa_ewfile'] 即為傳入要下載檔名的引數
    header("Content-type:application");
    header("Content-Length: " .(string)(filesize($_GET['pa_ewfile'])));
    header("Content-Disposition: attachment; filename=".$_GET['pa_ewfile']);
    readfile($_GET['pa_ewfile']);
}

if(isset($_GET['pa_cwfile'])){
    // $_GET['pa_cwfile'] 即為傳入要下載檔名的引數
    header("Content-type:application");
    header("Content-Length: " .(string)(filesize($_GET['pa_cwfile'])));
    header("Content-Disposition: attachment; filename=".$_GET['pa_cwfile']);
    readfile($_GET['pa_cwfile']);
}

?>