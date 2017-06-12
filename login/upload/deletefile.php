<?php require_once('../../base_home.php');

$file_name = $_GET["file"];
$ext = strrchr($file_name,'.');
$lin = strlen($ext);
$seat = substr($file_name, 0, -$lin);
$file = $th.'/'.$file_name;

if($ext==".pdf"){
	$sqlUpd = sprintf("UPDATE upload SET up_pdf='%s' WHERE upsort_num='%s'", null, $seat);
	$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
	if($sqlU){
		unlink($file);
	}
}else if($ext==".doc" || $ext==".docx"){
	$sqlUpd = sprintf("UPDATE upload SET up_word='%s' WHERE upsort_num='%s'", null, $seat);
	$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
	if($sqlU){
		unlink($file);		
	}
}

$sql = sprintf("SELECT up_no, up_pdf, up_word, up_upload FROM upload WHERE upsort_num = '$seat' ");
$result = mysqli_query($link, $sql) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($result);
$up_no = $row_data['up_no'];

if($row_data['up_pdf'] == NULL && $row_data['up_word'] == NULL){
	$sqlUpd = sprintf("UPDATE upload SET up_status='%s', up_upload='%s' WHERE upsort_num='%s'", '已投稿', 0, $seat);
	$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
	echo "<script>alert('已刪除檔案!!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_update.php?th=$th&up_no=$up_no'>"); 
}else{
	echo "<script>alert('已刪除檔案!!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_update.php?th=$th&up_no=$up_no'>");
}

?>