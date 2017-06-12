<?php require_once('../base_home.php');


$no = $_GET["up_no"];

$sql = "SELECT up_pdf, up_word FROM upload where up_no = '$no'";
$result = mysqli_query($link,$sql) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($result);

if($row_data['up_pdf']!=null){
	$delfile_pdf="upload/" .$th.'/'.$row_data['up_pdf'];
	unlink($delfile_pdf);//將檔案刪除
}

if($row_data['up_word']!=null){
	$delfile_word="upload/" .$th.'/'.$row_data['up_word'];
	unlink($delfile_word);//將檔案刪除
}

$sqlDel = sprintf("DELETE FROM upload WHERE up_no = '$no'");
$sqlU = mysqli_query($link, $sqlDel) or die(mysqli_error());
if($sqlU){
	exit("<meta http-equiv=REFRESH CONTENT=0.1;url=paper_list.php?th=$th>");
}

?>