<?php //這是create DB之後強制設定時間的頁面 還沒寫完整 
require_once('seminar_connect.php');

$th = $_GET['th'];


$query_data = "SELECT * FROM time_job order by time_no ASC" ;
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

$query_data1 = "SELECT * FROM important_date WHERE where_from = '3' ";
$data1 = mysqli_query($link,$query_data1) or die(mysqli_error());
$row_data1 = mysqli_fetch_assoc($data1);


$time = mysqli_query($link,$query_data) or die(mysqli_error());
$row_time = mysqli_fetch_assoc($time);
$time_have = true;
do{
	if($row_time['job_end_date']==null){
		$time_have = false;
	}
}while($row_time = mysqli_fetch_assoc($time));

if($time_have==true){
	exit ("<script>location.href='home/home.php?th=$th'</script>");
}

?>

<html>

<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<h1>作業時間</h1>
<hr>
<font color="red">*請先完成時間設定才可進到主頁面</font>

<form method="POST" action="time_job/time_job.php?th=<?php echo $th; ?>" enctype="multipart/form-data">

<table class="table table-striped table-hover" >
	<thead>
		<tr>
			<th></th>
			<th>作業名稱</th>
			<th>日期</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php $i=0;
		do{ $i++?>
		<tr>
			<td><?php echo $i;?></td>
			<td><?php echo $row_data['job_name']; ?></td>
			<?php if($row_data['job_start_date']==null){ ?>
				<td>尚未設定日期</td>
				<?php if($button_on){ ?>
					<td><input type="button" class="btn btn-primary" name="update" id="update" value="設定" onClick="window.open('time_job/time_job_update.php?th=<?php echo $th; ?>&job=<?php echo $row_data["job_name"]; ?>' , '設定', config='height=200,width=550')"></td>
				<?php } ?>
			<?php }else { ?>
				<td><?php echo $row_data['job_start_date']." ~ ".$row_data['job_end_date']; ?></td>
				<?php if($button_on){ ?>
					<td><input type="button" class="btn btn-primary" name="update" id="update" value="修改" onClick="window.open('time_job/time_job_update.php?th=<?php echo $th; ?>&job=<?php echo $row_data["job_name"]; ?>' , '修改', config='height=200,width=550')"></td>
				<?php } ?>
			<?php } ?>
		</tr>
		<?php }while($row_data = mysqli_fetch_assoc($data)); ?>
	</tbody>
</table>

<input type="button" class="btn btn-primary" onClick="self.location.href='/seminar/seminar_th.php'" value="回屆數選擇">

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
</html>