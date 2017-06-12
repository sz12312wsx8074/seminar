<?php require_once('../seminar_connect.php');



$query_data3 = "SELECT * FROM review_result order by rr_score_avg DESC, rr_num ASC";   //審查結果總表
$data3 = mysqli_query($link,$query_data3) or die(mysqli_error());
$row_data3 = mysqli_fetch_assoc($data3);
$totalRows_data3 = mysqli_num_rows($data3);

$filename=$th."審查結果".".xls";   // 建立檔名
header("Content-type:application/vnd.ms-excel"); // 送出header
header("Content-Disposition:filename=$filename");  // 指定檔名

?>

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>

<table border="1" cellpadding="5" cellspacing="0">
	<tr>
		<td>編號</td>
		<td>論文編號</td>
		<td>論文名稱</td>
		<td>分類</td>
		<td>分數一</td>
		<td>分數二</td>
		<td>平均分數</td>
		<td>委員一是否推薦</td>
		<td>委員二是否推薦</td>
		<td>備註</td>
	</tr>
<?php $i=0;
	do{ $i++ ?>
	<tr>
		<td><?php echo $i; ?></td>
		<td><?php echo $row_data3['rr_num']; ?></td>
		<td><?php echo $row_data3['rr_paper']; ?></td>
		<td><?php echo $row_data3['rr_sort']; ?></td>
		<td><?php echo $row_data3['rr_score_one']; ?></td>
		<td><?php echo $row_data3['rr_score_two']; ?></td>
		<td><?php echo $row_data3['rr_score_avg']; ?></td>
		<td><?php if($row_data3['rr_recommend_one']==1){ echo "是"; }else{ echo "否"; } ?></td>
		<td><?php if($row_data3['rr_recommend_two']==1){ echo "是"; }else{ echo "否"; } ?></td>
		<td><?php echo $row_data3['rr_remark']; ?></td>
	</tr>
	<?php }while($row_data3 = mysqli_fetch_assoc($data3)); ?>
</table>
</html>