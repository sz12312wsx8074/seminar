<?php require_once('../base_home.php');

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php?th=$th"</script>');
	return;
}
if($button_on==false){
	echo "<script>alert('研討會時間已過!');</script>";
	exit ("<script>location.href='/seminar/important_date/important_date.php?th=$th'</script>");
} 

$no = $_GET["date_no"];

$sqlDel = sprintf("DELETE FROM important_date WHERE date_no = '$no'");
$sqlD = mysqli_query($link, $sqlDel) or die (mysqli_error());
if($sqlD){
	echo "<script>alert('刪除成功');</script>";
	exit("<script>location.href='important_date.php?th=$th';</script>");
}

?>