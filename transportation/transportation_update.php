<?php require_once('../base_home.php');
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM transportation where tr_no";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

if(isset($_POST['update'])){
	if(mb_strlen($_POST['tr_address'],'utf-8')>80){
		echo "<script>alert('地址字數超過80');</script>";
	}else if(empty($_POST['tr_content']) or empty($_POST['tr_address'])> 0){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}else{		
		$sqlUpd = sprintf("UPDATE transportation SET tr_content='%s', tr_address='%s' WHERE tr_no ",
		$_POST['tr_content'], $_POST['tr_address']);

		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		if($sqlU){
			echo "<script>alert('已修改!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=1;url=transportation.php?th=$th>");
		}
	}
}
?>



<div id=content class="col-sm-9">
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>  <!--文字編輯器套件-->
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->
<h1>修改交通指南</h1>
<input type="hidden" id="tr_no" name="tr_no" value="<?php echo $row_data['tr_no'] ;?>">
<form class="form-horizontal form-text-size" role="form"  method="POST" action="transportation_update.php?th=<?php echo $th; ?>" enctype="multipart/form-data">
<hr>

<div class="form-group">
    <label class="control-label col-sm-2" for="tr_content"> <font color="red">*</font>內容：</label>
    <div class="col-sm-10">
		<textarea type="text" id="tr_content" name="tr_content"><?php echo $row_data['tr_content'] ?> </textarea>
    </div>
</div>

<div class="form-group">
<?php if(empty($_POST['tr_address'])){ ?><label class="control-label col-sm-2"> <font color="red">*</font> <?php } ?>
地址：</label>
<div class="col-sm-10">
<input class="input" type="text" id="tr_address" name="tr_address" value="<?php echo $row_data['tr_address'] ;?>" required/>
<font color="red">字數限制80</font>
<br><br>
<input id="submit" type="submit" class="btn btn-primary" name="update" value="修改">
</div>
</div>



</form>
<br>
</div>
</body>
<?php require_once('../base_footer.php')?>
