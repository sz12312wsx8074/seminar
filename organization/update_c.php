<?php require_once('../base_home.php');

if($_GET){
	$c_no=$_GET["c_no"];
}


if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}


if($button_on==false){
	echo "<script>alert('研討會時間已過!');</script>";
	exit ("<script>location.href='/seminar/organization/organizers.php?th=$th'</script>");
} 

if(isset($_POST['submit'])){
	if($_POST['position']=='選擇單位'){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}
	else{
		
		$sqlUpd = sprintf("UPDATE  organizers_c SET position='%s',name='%s',identity='%s'  WHERE c_no='$c_no' "
		,$_POST['position'],$_POST['name'],$_POST['identity']);
		$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
					echo "<script>alert('已修改!!');</script>";  //改版改這裡~~~~~~~~"已修改"改成彈跳視窗
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=organizers.php?th=$th>");  //改版改這裡~~~~~~~~ 過0.1秒後回原本頁面 那個content=0.1就等於秒數
			}
	}
}

$query_data = "SELECT * FROM organizers_c where c_no='$c_no'";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);









?>







<html>
<div id=content class="col-sm-9">
<form  class="form-horizontal form-text-size" method="POST" action="update_c.php?c_no=<?php echo $c_no ?>&th=<?php echo $th ?>">

<body>

<h1>委員會修改</h1>
<hr>
<input type="hidden" id="c_no" name="c_no" value="<?php echo $row_data['c_no']; ?> ">
<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>選擇職位</label>
	<div class="col-sm-3">
		<select class="form-control" name="position" id="position">
			<option <?php if($row_data['position']=='榮譽主席'){ ?> selected="selected" <?php } ?> >榮譽主席</option>
			<option <?php if($row_data['position']=='榮譽副主席'){ ?> selected="selected" <?php } ?> >榮譽副主席</option>
			<option <?php if($row_data['position']=='大會主席'){ ?> selected="selected" <?php } ?> >大會主席</option>
			<option <?php if($row_data['position']=='論壇主席'){ ?> selected="selected" <?php } ?> >論壇主席</option>
			<option <?php if($row_data['position']=='議程主席'){ ?> selected="selected" <?php } ?> >議程主席</option>
		</select>
	</div>
</div>



<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>名稱：</label>
	<div class="col-sm-3">
		<input  type="text" name="name" id="name" value="<?php echo $row_data['name']; ?>" required/>
	</div>
</div>
	
	
	
<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>身分：</label>
	<div class="col-sm-3">
		<input type="text" name="identity" id="identity" value="<?php echo $row_data['identity']; ?>" required/>
	</div>
</div>	
<br>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<input type="submit" class="btn btn-primary" name="submit" value="儲存"/>
	</div>
</div>



</body>
</form>
</div>
</html>




<?php require_once('../base_footer.php')?>