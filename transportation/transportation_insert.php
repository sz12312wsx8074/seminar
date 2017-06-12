<?php require_once('../base_home.php');
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if(isset($_POST['submit'])){
	if(mb_strlen($_POST['tr_address'],'utf-8')>80){
		echo "<script>alert('地址字數超過80');</script>";
	}else if(empty($_POST['tr_content']) or empty($_POST['tr_address'])> 0){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}else{
		$sqlIns = sprintf("INSERT INTO transportation (tr_content, tr_address)values (%s, %s)",'"'.$_POST['tr_content'].'"', '"'.$_POST['tr_address'].'"');
		$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");

		if($sqlI){
			echo "<script>alert('已新增!!');</script>";
			exit("<meta http-equiv=REFRESH CONTENT=0.1;url=transportation.php?th=$th>");
				}
			}
		}
?>

<div id=content class="col-sm-9">
<h1>新增交通指南</h1>
<hr>
<form class="form-horizontal form-text-size" role="form" action="transportation_insert.php?th=<?php echo $th; ?>" method="post" enctype="multipart/form-data">
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->
<div class="form-group">
<label class="control-label col-sm-2" ><font color="red">*</font>內文：</label>
	<div class="col-sm-10">
		<textarea id="tr_content" name="tr_content"><?php if(isset($_POST['cancel'])){echo "";}else{if(!empty($_POST['tr_content'])){ echo $_POST['tr_content']; }} ?></textarea>
	<br><br>
	</div>

<label class="control-label col-sm-2"><font color="red">*</font>地址：</label>
	<div class="col-sm-10">
		<input type="text" id="tr_address" name="tr_address" size="50" required <?php if(!empty($_POST['tr_address'])){ ?> value="<?php echo $_POST['tr_address'] ?>"  <?php } ?>/>
		<font color="red">字數限制80</font>
	</div>
	<br><br>
</div>

<div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
		<input type="submit" class="btn btn-primary" name="submit" value="送出"/>
	</div>
</div>
</form>
<br>
</div>
</body>

<?php require_once('../base_footer.php')?>