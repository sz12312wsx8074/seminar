<?php require_once('../base_home.php');

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if($button_on==false){
	echo "<script>alert('研討會時間已過!');</script>";
	exit ("<script>location.href='/seminar/news/news.php?th=$th'</script>");
} 


$no = $_GET["news_no"];
$news_no = $_GET["news_no"];

$query_data = "SELECT * FROM news_file WHERE news_no ='$news_no'";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);

if($row_sum!=0){
	do{	
		$file_name = $row_data["file_name"];
		unlink("news_file/" .iconv('UTF-8','BIG5',$file_name));
	} while($row_data = mysqli_fetch_assoc($data));
}

$sqlDel = sprintf("DELETE FROM news WHERE news_no = '$no'");
$sqlD = mysqli_query($link, $sqlDel) or die (mysqli_error());
if($sqlD){
	echo "<script>alert('刪除成功');</script>";
	exit("<script>location.href='news.php?th=$th';</script>");
}

?>