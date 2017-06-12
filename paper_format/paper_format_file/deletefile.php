<?php require_once('../../base_home.php');

$sql = sprintf("SELECT pa_no, pa_ewfile, pa_cwfile, pa_epfile, pa_cpfile FROM paper_format ");
$result = mysqli_query($link,$sql) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($result);

$file_name = $_GET["file"];
$ext = strrchr($file_name,'.');

if($ext==".pdf"){
	if($file_name == $row_data['pa_epfile']){
		$sqlUpd = sprintf("UPDATE paper_format SET pa_epfile='%s' WHERE pa_epfile = '$file_name' ", null);
	}else if($file_name == $row_data['pa_cpfile']){
		$sqlUpd = sprintf("UPDATE paper_format SET pa_cpfile='%s' WHERE pa_cpfile = '$file_name' ", null);
	}
	$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
	if($sqlU){		
		unlink($file_name);
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");	
	}
}else if($ext==".doc" || $ext==".docx"){
	if($file_name == $row_data['pa_ewfile']){
		$sqlUpd = sprintf("UPDATE paper_format SET pa_ewfile='%s' WHERE pa_ewfile = '$file_name' ", null);
	}else if($file_name == $row_data['pa_cwfile']){
		$sqlUpd = sprintf("UPDATE paper_format SET pa_cwfile='%s' WHERE pa_cwfile = '$file_name' ", null);
	}
	$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
	if($sqlU){
		unlink($file_name);
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
	}
}

?>