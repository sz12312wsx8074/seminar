<?php require_once('../base_home.php');

if(!isset($_SESSION['re_mail'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login/login.php"</script>');
	return;
}

$re_mail = $_GET["re_mail"];

$time = "SELECT * FROM important_date where where_from = '3'";
$time_data = mysqli_query($link,$time);
$row_time = mysqli_fetch_assoc($time_data);
$start_date = $row_time['date_date'];

$date=date_create("$start_date");
date_sub($date,date_interval_create_from_date_string("7 days"));
$end_time = date_format($date,"Y-m-d");
$now = date("Y-m-d");

if($end_time<$now){
	echo "<script>alert('已超過修改時間!');</script>";
	exit ("<script>location.href='/seminar/bus_enrolment/bus_enrolment_index.php?th=$th'</script>");
}

$sqlDel = sprintf("DELETE FROM bus_enrolment WHERE re_mail ='$re_mail'");
$sqlD = mysqli_query($link, $sqlDel) or die (mysqli_error());
if($sqlD){
	echo "<script>alert('取消成功');</script>";
	exit("<script>location.href='bus_enrolment_index.php?th=$th';</script>");
}

?>