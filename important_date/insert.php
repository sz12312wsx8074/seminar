 <?php require_once('../base_home.php');


 if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if($button_on==false){
	echo "<script>alert('研討會時間已過!');</script>";
	exit ("<script>location.href='/seminar/important_date/important_date.php?th=$th'</script>");
} 
 
if(isset($_POST['insert'])){
	if(empty($_POST['date_content'])){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}
	else{
		
		$sqlUpd = sprintf("INSERT INTO important_date (date_content,date_date) values (%s,%s)",
		'"'.$_POST['date_content'].'"','"'.$_POST['date_date'].'"');
		$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
					echo "<script>alert('已新增!!');</script>";  //改版改這裡~~~~~~~~"已修改"改成彈跳視窗
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=important_date.php?th=$th>");  //改版改這裡~~~~~~~~ 過0.1秒後回原本頁面 那個content=0.1就等於秒數
			}
	}
}






?>


<html>
<div id=content class="col-sm-9">
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>  <!--文字編輯器套件-->
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->
<form class="form-horizontal form-text-size"  method="POST" action="insert.php?th=<?php echo $th ?>">

<body>

<h1>重要日期新增</h1>
<hr>

<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>日期：</label>
	<div class="col-sm-10">
		<input type="text" name="date_date" id="date_date" onClick="WdatePicker()"
		<?php if(!empty($_POST['date_date'])){ ?> value="<?php echo $_POST['date_date'] ?>"  <?php } ?> required>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>項目：</label>
	<div class="col-sm-10">
		<textarea cols="40" rows="20"  id="date_content" name="date_content"><?php if(!empty($_POST['date_content'])){ echo $_POST['date_content']; } ?></textarea>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-10 col-sm-offset-2">
		<input id="insert"  class="btn btn-primary" type="submit" name="insert" value="送出"/>
	</div>
</div>

</body>
</form>
</div>
</html>


<?php require_once('../base_footer.php')?>