<?php require_once('../base_home.php');


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
	if($_POST['position']=='選擇職位'){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}
	else{
		
		$sqlUpd = sprintf("INSERT INTO organizers_c (position,name,identity) values (%s,%s,%s)",
		'"'.$_POST['position'].'"','"'.$_POST['name'].'"','"'.$_POST['identity'].'"');
		$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
					echo "<script>alert('已新增!!');</script>";  //改版改這裡~~~~~~~~"已修改"改成彈跳視窗
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=organizers.php?th=$th>");  //改版改這裡~~~~~~~~ 過0.1秒後回原本頁面 那個content=0.1就等於秒數
			}
	}
}










?>







<html>
<div id=content class="col-sm-9">
<form class="form-horizontal form-text-size" method="POST" action="insert_c.php?th=<?php echo $th ?>">

<body>

<h1>委員會新增</h1>
<hr>
<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>選擇職位</label>
	<div class="col-sm-3">
		<select class="form-control" name="position" id="position">
			<option  selected="selected">選擇職位</option>
			<option>榮譽主席</option>
			<option>榮譽副主席</option>
			<option>大會主席</option>
			<option>論壇主席</option>
			<option>議程主席</option>
		</select>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>名稱：</label>
	<div class="col-sm-3">
		<input  type="text" name="name" id="name"
		<?php if(!empty($_POST['name'])){ ?> value="<?php echo $_POST['name'] ?>"  <?php } ?> required/>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>身分：</label>
	<div class="col-sm-3">
		<input  type="text" name="identity" id="identity"
		<?php if(!empty($_POST['identity'])){ ?> value="<?php echo $_POST['identity'] ?>"  <?php } ?> required/>
	</div>
</div>

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