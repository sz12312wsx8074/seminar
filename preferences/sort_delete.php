<?php require_once('../seminar_connect.php');

$sort_abbreviation = $_GET["sort_abbreviation"];

$sqlDel = sprintf("DELETE FROM sort WHERE sort_abbreviation='%s'", $sort_abbreviation);
$sqlD = mysqli_query($link, $sqlDel) or die(mysqli_error());

if($sqlD){
	exit("<script>location.href='sort.php?th=$th';</script>");
}

?>