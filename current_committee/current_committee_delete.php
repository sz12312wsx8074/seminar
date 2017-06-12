<?php require_once('../base_home.php');

$email = $_GET['cc_email'];

$sqlDel = sprintf("DELETE FROM current_committee WHERE cc_email= '$email'");
$sqlD = mysqli_query($link, $sqlDel) or die (mysqli_error());

if($sqlD){
	exit("<script>location.href='current_committee.php?th=$th';</script>");
}
?>