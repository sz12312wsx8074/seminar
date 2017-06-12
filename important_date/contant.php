<?php require_once('../base_home.php');
$query_data = "SELECT * FROM important_date ORDER BY date_no ASC";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);


if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php?th=$th"</script>');
	return;
}

if($button_on==false){
	echo "<script>alert('研討會時間已過!');</script>";
	exit ("<script>location.href='/seminar/important_date/important_date.php?th=$th'</script>");
} 

if(isset($_POST['submit'])){
	if(empty($_POST['contant'])){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}
	else{
		$sqlUpd = sprintf("UPDATE important_date SET contant='%s' ORDER BY date_no ASC ",$_POST['contant']);
		$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
					echo "<script>alert('已修改!!');</script>";  //改版改這裡~~~~~~~~"已修改"改成彈跳視窗
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=important_date.php?th=$th>");  //改版改這裡~~~~~~~~ 過0.1秒後回原本頁面 那個content=0.1就等於秒數
			}
	}
}


?>

<html>
<div id=content class="col-sm-9">
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>  <!--文字編輯器套件-->
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->
<form class="form-horizontal form-text-size" method="POST" action="contant.php?th=<?php echo $th ?>">

<body>
<div class="page-header">
	<h1>修改敘述</h1>
</div>
<br><br>
<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>敘述：</label>
	<div class="col-sm-10">
		<textarea id="contant" name="contant"> <?php echo $row_data['contant'];?> </textarea><br>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-10 col-sm-offset-2">
		<input type="submit" class="btn btn-primary" name="submit" value="送出"/>
	</div>
</div>

</body>
</form>
</div>
</html>







<?php require_once('../base_footer.php')?> 