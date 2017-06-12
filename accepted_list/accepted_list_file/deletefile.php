<?php require_once('../../base_home.php');

$sql = "SELECT * FROM accepted_list";
$result = mysqli_query($link,$sql) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($result);

$file_name = $_GET["file"];
$ext = strrchr($file_name,'.');

if($ext == ".pdf"){
	$sqlUpd = sprintf("UPDATE accepted_list SET ac_pdf='%s' WHERE ac_pdf = '$file_name' ", null);
	$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
	if($sqlU){		
		unlink($file_name);
		echo "<script>alert('刪除成功');</script>";
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../accepted_list.php?th=$th'>");	
	}
}

?>