<?php require_once('../base_home.php');
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}
if(isset($_POST['submit'])){
	if(mb_strlen($_POST['bt_hotel'],'utf-8')>50){
		echo "<script>alert('飯店名稱字數超過50');</script>";
	}else if(mb_strlen($_POST['bt_address'],'utf-8')>80){
		echo "<script>alert('飯店地址字數超過80');</script>";
	}else if(mb_strlen($_POST['bt_contact_person'],'utf-8')>20){
		echo "<script>alert('聯絡人字數超過20');</script>";
	}else if(mb_strlen($_POST['bt_phone_number'],'utf-8')!=10){
				echo "<script>alert('聯絡電話格式錯誤，字數需等於10');</script>";
	}else if(substr($_POST['bt_phone_number'], 0, 2)!="09"){
		echo "<script>alert('聯絡電話格式錯誤，是以09開頭');</script>";
	}else if(empty($_POST['bt_hotel']) or empty($_POST['bt_address']) or empty($_POST['bt_contact_person'])or empty($_POST['bt_phone_number'])> 0){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}else{
		$sqlIns = sprintf("INSERT INTO banquet (bt_hotel, bt_address, bt_contact_person, bt_phone_number)values (%s, %s, %s, %s)",'"'.$_POST['bt_hotel'].'"', '"'.$_POST['bt_address'].'"', '"'.$_POST['bt_contact_person'].'"', '"'.$_POST['bt_phone_number'].'"');
		$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");

		if($sqlI){
			echo "<script>alert('已新增!!');</script>";
			exit("<meta http-equiv=REFRESH CONTENT=0.1;url=banquet.php?th=$th>");
				}
			}
		}
?>

<html>

<div id=content class="col-sm-9"> 
<h1>新增晚宴內容</h1>
<hr>
<form class="form-horizontal form-text-size" role="form" action="banquet_insert.php?th=<?php echo $th; ?>" method="post" enctype="multipart/form-data">

<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>飯店名稱：</label>
	<div class="col-sm-10">
		<input type="text" id="bt_hotel" name="bt_hotel" required <?php if(!empty($_POST['bt_hotel'])){ ?> value="<?php echo $_POST['bt_hotel'] ?>"  <?php } ?>/>
		<font color="red">字數限制50</font>
	</div>
	<br><br>
	<label class="control-label col-sm-2"><font color="red">*</font>飯店地址：</label>
	<div class="col-sm-10">
		<input type="text" id="bt_address" name="bt_address" size="50" required <?php if(!empty($_POST['bt_address'])){ ?> value="<?php echo $_POST['bt_address'] ?>"  <?php } ?>/>
		<font color="red">字數限制80</font>
	</div>
	<br><br>
	<label class="control-label col-sm-2"><font color="red">*</font>聯絡人：</label>
	<div class="col-sm-10">
		<input type="text" id="bt_contact_person" name="bt_contact_person" required <?php if(!empty($_POST['bt_contact_person'])){ ?> value="<?php echo $_POST['bt_contact_person'] ?>"  <?php } ?>/>
		<font color="red">字數限制20</font>
	</div>
	<br><br>
	<label class="control-label col-sm-2"><font color="red">*</font>聯絡電話：</label>
	<div class="col-sm-10">
		<input type="text" id="bt_phone_number" name="bt_phone_number" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" required <?php if(!empty($_POST['bt_phone_number'])){ ?> value="<?php echo $_POST['bt_phone_number'] ?>"  <?php } ?>/>
		<font color="red">例:09xxxxxxxx</font>
	</div>
</div>
<br>
<div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
		<input type="submit" class="btn btn-primary" name="submit" value="送出"/>
	</div>
</div>
</form>
<!--改版改這裡~這裡以下都要改成這樣-->
</div>
</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>