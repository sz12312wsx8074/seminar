<?php require_once('../base_home.php');
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if($_GET){
	$sb_no=$_GET["sb_no"];
}

if(isset($_POST['update'])){
	if(empty($_POST['sb_shift']) or empty($_POST['sb_explanation']) or empty($_POST['sb_line']) or empty($_POST['sb_time']) ){
		echo "<script>alert('資料不齊全，請重新輸入。');</script>";
	}else{
		$upd = $_POST['upd'];
		$sqlUpd = sprintf("UPDATE shuttle_bus SET sb_shift = '%s',sb_explanation = '%s',sb_line = '%s',sb_time = '%s' 
		WHERE sb_no = '$upd'", $_POST['sb_shift'], $_POST['sb_explanation'],$_POST['sb_line'], $_POST['sb_time']);

		$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
			echo "<script>alert('已修改!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=1;url=shuttle_bus.php?th=$th>");
		}
	}	
}

$query_data = "SELECT * FROM shuttle_bus where sb_no='$sb_no' ";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
?>



<div id=content class="col-sm-9">
<form class="form-horizontal form-text-size" role="form" method="POST" action="shuttle_bus_update.php?sb_no=<?php echo $sb_no; ?>&th=<?php echo $th; ?>">
<h1>修改接駁車</h1>
<hr>
<input type="hidden" id="upd" name="upd" value="<?php echo $row_data['sb_no'] ;?>">

<div class="form-group">
<?php if(empty($_POST['sb_shift'])){ ?> <label class="control-label col-sm-2" for="sb_shift"><font color="red">*</font> <?php } ?>
班次：</label>
<div class="col-sm-10">
<input  class="input" type="text" id="sb_shift" name="sb_shift" value="<?php echo $row_data['sb_shift'] ;?>" required/><br><br>
</div>
</div>
  
<div class="form-group">  
<?php if(empty($_POST['sb_explanation'])){ ?><label class="control-label col-sm-2" for="sb_shift"><font color="red">*</font> <?php } ?>
說明：</label>
<div class="col-sm-10">
<input  class="input" type="text" id="sb_explanation" name="sb_explanation" value="<?php echo $row_data['sb_explanation'] ;?>" required/><br><br>
</div>
</div>

<div class="form-group">
<label class="control-label col-sm-2"><font color="red">*</font>單程 / 來回：</label>
	<div class="col-sm-3">
		<select class="form-control" name="sb_line" id="sb_line">
			<option <?php if($row_data['sb_line']=='單程'){ ?> selected="selected" <?php } ?> >單程</option>
			<option <?php if($row_data['sb_line']=='來回'){ ?> selected="selected" <?php } ?> >來回</option>			
		</select>
	</div>
</div>
	
<div class="form-group"> 
<?php if(empty($_POST['sb_time'])){ ?> <label class="control-label col-sm-2" for="sb_shift"><font color="red">*</font> <?php } ?>
發車時間：</label>
<div class="col-sm-10">
<input  class="input" type="text" id="sb_time" name="sb_time" value="<?php echo $row_data['sb_time'] ;?>" required/><br><br>
</div>
</div>
<div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
		<input id="submit" type="submit" class="btn btn-primary" name="update" value="修改">
   </div>
  </div>
</form>
<br>
</div>
</body>
<?php require_once('../base_footer.php')?>
