<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM banquet where bt_no=1";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);


if(isset($_POST['update'])){
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
		
		$sqlUpd = sprintf("UPDATE banquet SET bt_hotel='%s', bt_address='%s', bt_contact_person='%s', bt_phone_number='%s' WHERE bt_no=1 ",
		$_POST['bt_hotel'], $_POST['bt_address'], $_POST['bt_contact_person'], $_POST['bt_phone_number']);

		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		if($sqlU){
			echo "<script>alert('已修改!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=1;url=banquet.php?th=$th>");
		}
	}
}

?>

<html>

<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<h1>修改晚宴內容</h1>
<hr>
<form class="form-horizontal form-text-size" role="form"  method="POST" action="banquet_update.php?th=<?php echo $th; ?>" enctype="multipart/form-data">

<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>飯店名稱：</label>
	<div class="col-sm-10">
		<input type="text" id="bt_hotel" name="bt_hotel" value="<?php echo $row_data['bt_hotel']; ?>" required/>
		<font color="red">字數限制50</font>
	</div>
	<br><br>
	<label class="control-label col-sm-2"><font color="red">*</font>飯店地址：</label>
	<div class="col-sm-10">
		<input type="text" id="bt_address" name="bt_address" size="50" value="<?php echo $row_data['bt_address']; ?>" required/>
		<font color="red">字數限制80</font>
	</div>
	<br><br>
	<label class="control-label col-sm-2"><font color="red">*</font>聯絡人：</label>
	<div class="col-sm-10">
		<input type="text" id="bt_contact_person" name="bt_contact_person" value="<?php echo $row_data['bt_contact_person']; ?>" required/>
		<font color="red">字數限制20</font>
	</div>
	<br><br>
	<label class="control-label col-sm-2"><font color="red">*</font>聯絡電話：</label>
	<div class="col-sm-10">
		<input type="text" id="bt_phone_number" name="bt_phone_number" value="<?php echo $row_data['bt_phone_number']; ?>" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" required/>
		<font color="red">例:09xxxxxxxx</font>
	</div>
</div>
<div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
		<input type="submit" class="btn btn-primary" name="update" id="update" value="修改">
	</div>
</div>
</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>