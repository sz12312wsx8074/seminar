<?php require_once('../base_home.php');

$related = $_GET["cl_related"];

$sqlDel = sprintf("UPDATE committee_list SET cl_history='1' WHERE cl_related='$related'");
$sqlD = mysqli_query($link_generic, $sqlDel) or die (mysqli_error());
if($sqlD){
	exit('<script>location.href="committee_list.php";</script>');
}
?>