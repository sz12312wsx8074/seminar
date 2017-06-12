<?php require_once('../base_home.php');

$query_data = "SELECT * FROM accepted_list";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_num = mysqli_num_rows($data);

$query_time = "SELECT * FROM time_job WHERE time_no = 5 ";
$time = mysqli_query($link,$query_time) or die(mysqli_error());
$row_time = mysqli_fetch_assoc($time);

$current_time = date("Y-m-d");

if(isset($_POST['update'])){
	$sql = "SELECT * FROM accepted_list WHERE ac_no = $ac_no ";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	$result_data = mysqli_fetch_assoc($result);
}

?>

<div id=content class="col-sm-9">

<?php if ($current_time > $row_time['job_end_date']){?>
	<h1>授權書</h1>
	<form class="form-horizontal form-text-size" method="POST">
	<hr>
	
	此屆的授權書：
	<a Target="_blank" href="../login/camera_ready/<?php echo $th; ?>/<?php echo $result_data['ca_authorize'];?>"><?php echo $result_data['ca_authorize'];?></a>
	
	</form>
<?php }else if($row_data['ca_authorize'] == NULL){?>
	<h1>新增授權書</h1>
	<form class="form-horizontal form-text-size" method="POST" action="camera_ready_run.php?th=<?php echo $th; ?>" enctype="multipart/form-data" >
	<hr>
	
	<font color="red">※只能上傳word檔※</font>
	<br><br>
	
	<font color="red">*</font>
	授權書上傳：<input type="file" id="ca_authorize" name="ca_authorize" />
	<br>
	
	<input class="btn btn-primary" type="submit" id="insert" name="insert" value="確認上傳" />

	</form>
<?php }else{?>
	<h1>修改授權書</h1>
	<form class="form-horizontal form-text-size" method="POST" action="camera_ready_run.php?th=<?php echo $th; ?>" enctype="multipart/form-data" >
	<hr>

	<!--<input type="hidden" name="ac_no" value="<?php echo $ac_no ;?>" />-->

	<font color="red">※只能上傳word檔※</font>
	<br><br>
	
	<?php if($result_data['ca_authorize'] != NULL){?>
		已上傳的授權書：
		<a Target="_blank" href="../login/camera_ready/<?php echo $th; ?>/<?php echo $result_data['ca_authorize'];?>"><?php echo $result_data['ca_authorize'];?></a>
	<?php }?>
	<br><br>

	<font color="red">*</font>
	授權書上傳：<input type="file" id="ca_authorize" name="ca_authorize" />
	<br>

	<input class="btn btn-primary" type="submit" id="update" name="update" value="修改" />
<?php }?>
	</form>

	</div>

	</div> <!--wrapper-->
	</body>

<?php require_once('../base_footer.php')?>