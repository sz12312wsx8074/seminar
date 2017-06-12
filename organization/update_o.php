<?php require_once('../base_home.php');

if($_GET){
	$org_no=$_GET["org_no"];
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
	if($_POST['unit']=='選擇單位'){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}
	else{
		
		$sqlUpd = sprintf("UPDATE  organizers SET unit='%s',org_name='%s',english='%s'  WHERE org_no='$org_no' "
		,$_POST['unit'],$_POST['org_name'],$_POST['english']);
		$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
					echo "<script>alert('已修改!!');</script>";  //改版改這裡~~~~~~~~"已修改"改成彈跳視窗
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=organizers.php?th=$th>");  //改版改這裡~~~~~~~~ 過0.1秒後回原本頁面 那個content=0.1就等於秒數
			}
	}
}

$query_data = "SELECT * FROM organizers where org_no='$org_no'";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);









?>







<html>
<div id=content class="col-sm-9">
<form class="form-horizontal form-text-size" method="POST" action="update_o.php?org_no=<?php echo $org_no ?>&th=<?php echo $th ?>">

<body>

<h1>主辦單位修改</h1>
<hr>
<br>
<input type="hidden" id="org_no" name="org_no" value="<?php echo $row_data['org_no']; ?> ">
<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>選擇單位：</label>
	<div class="col-sm-3">
		<select class="form-control" name="unit" id="unit">
			<option <?php if($row_data['unit']=='指導單位'){ ?> selected="selected" <?php } ?> >指導單位</option>
			<option <?php if($row_data['unit']=='主辦單位'){ ?> selected="selected" <?php } ?> >主辦單位</option>
			<option <?php if($row_data['unit']=='協辦單位'){ ?> selected="selected" <?php } ?> >協辦單位</option>
		</select>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2" for="org_name"><font color="red">*</font>名稱：</label>
	<div class="col-sm-10">
		<input  type="text" name="org_name" id="org_name" value="<?php echo $row_data['org_name']; ?>" required/>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2" for="english"><font color="red">*</font>英文名稱：</label>
	<div class="col-sm-10">
		<input type="text" name="english" id="english" value="<?php echo $row_data['english']; ?>" required/>
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