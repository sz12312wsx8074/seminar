<?php require_once('../base_home.php');

$query_data = "SELECT * FROM accepted_list";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);

$query_time = "SELECT * FROM time_job WHERE time_no = 5 ";
$time = mysqli_query($link,$query_time) or die(mysqli_error());
$row_time = mysqli_fetch_assoc($time);


if(isset($_POST['update'])){
	$sql = "SELECT * FROM accepted_list ";
	$result = mysqli_query($link,$sql) or die(mysqli_error());
	$result_data = mysqli_fetch_assoc($result);
}

?>

<div id=content class="col-sm-9">

<?php 
if(!$button_on){?>
	<h1>接受列表</h1>
	<form class="form-horizontal form-text-size">
	<hr>
	
	此屆的接受列表：
	<a Target="_blank" href="../accepted_list/accepted_list_file/<?php echo $result_data['ac_pdf'];?>"><?php echo $result_data['ac_pdf'];?></a>
	
	</form>
<?php }elseif($row_data['ac_pdf'] == NULL){?>
	<h1>新增接受列表</h1>
	<form class="form-horizontal form-text-size" method="POST" action="accepted_list_run.php?th=<?php echo $th; ?>" enctype="multipart/form-data" >
	<hr>
	
	<font color="red">※只能上傳pdf檔※</font>
	<br><br>
	
	<font color="red">*</font>
	上傳pdf檔案：<input type="file" id="ac_pdf" name="ac_pdf" />
	<br>
	
	<input class="btn btn-primary" type="submit" id="insert" name="insert" value="確認上傳" />

	</form>
<?php }else{?>
	<h1>修改接受列表</h1>
	<form class="form-horizontal form-text-size" method="POST" action="accepted_list_run.php?th=<?php echo $th; ?>" enctype="multipart/form-data">
	<hr>

	<!--<input type="hidden" name="ac_no" value="<?php echo $ac_no ;?>" />-->
	
	<font color="red">※只能上傳pdf檔※</font>
	<br><br>
	
	<?php if($result_data['ac_pdf'] != NULL){?>
		已上傳的pdf檔案：
		<a Target="_blank" href="../accepted_list/accepted_list_file/<?php echo $result_data['ac_pdf'];?>"><?php echo $result_data['ac_pdf'];?></a>
		<input class="btn btn-default" type="button" id="delete" name="delete" value="刪除" onclick="delete_Case(true, '<?php echo $result_data['ac_pdf']; ?>')" />
	<?php }?>
	<br><br>

	<font color="red">*</font>
	pdf檔案上傳：<input type="file" id="ac_pdf" name="ac_pdf" />
	<br>

	<input class="btn btn-primary" type="submit" id="update" name="update" value="修改" />

	</form>
<?php }?>

</div>

<script>
function delete_Case(single, file) {
	var dele = confirm("確定要刪除這個檔案嗎？");
	if (dele == true){
		if (single){
			location.href='../accepted_list/accepted_list_file/deletefile.php?th=<?php echo $th;?>&file='+file;
		}else{
			document.getElementById('dele').type = 'submit';
		}
	}
}
</script>

</div> <!--wrapper-->
</body>

<?php require_once('../base_footer.php')?>