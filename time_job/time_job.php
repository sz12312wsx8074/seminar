<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM time_job order by time_no ASC" ;
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

?>


<html>

<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<h1>作業時間</h1>
<hr>
<form method="POST" action="time_job.php?th=<?php echo $th; ?>" enctype="multipart/form-data">

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
					<td><input type="button" class="btn btn-primary" name="update" id="update" value="設定" onClick="window.open('time_job_update.php?th=<?php echo $th; ?>&job=<?php echo $row_data["job_name"]; ?>' , '設定', config='height=200,width=550')"></td>
				<?php } ?>
			<?php }else { ?>
				<td><?php echo $row_data['job_start_date']." ~ ".$row_data['job_end_date']; ?></td>
				<?php if($button_on){ ?>
					<td><input type="button" class="btn btn-primary" name="update" id="update" value="修改" onClick="window.open('time_job_update.php?th=<?php echo $th; ?>&job=<?php echo $row_data["job_name"]; ?>' , '修改', config='height=200,width=550')"></td>
				<?php } ?>
			<?php } ?>
		</tr>
		<?php }while($row_data = mysqli_fetch_assoc($data)); ?>
	</tbody>
</table>

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>