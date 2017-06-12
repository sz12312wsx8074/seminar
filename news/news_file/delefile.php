<?php require_once('../../base_home.php');

mysqli_select_db($link, $database);

$file_name = $_GET["file"];
$news_no = $_GET["news_no"];
$file_move=$file_name;

$sql = sprintf("SELECT * FROM news WHERE news_no = '$news_no' ");
$result = mysqli_query($link, $sql) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($result);

$sqlDel = sprintf("DELETE FROM news_file WHERE file_name = '%s' AND news_no = '%s'", $file_name, $news_no);
$sqlD = mysqli_query($link, $sqlDel) or die (mysqli_error());

unlink(iconv('UTF-8','BIG5',$file_move));

exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=../news_update.php?news_no='.$row_data['news_no'].'>');

?>
