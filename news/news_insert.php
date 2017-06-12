 <?php require_once('../base_home.php'); 

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if($button_on==false){
	echo "<script>alert('研討會時間已過!');</script>";
	exit ("<script>location.href='/seminar/news/news.php?th=$th'</script>");
} 

?>

<script>
	
	function makeFileList() {
		var input = document.getElementById("file");
		var ul = document.getElementById("fileList");
		
		
		while (ul.hasChildNodes()) {
			ul.removeChild(ul.firstChild);
		}
		for (var i = 0; i < input.files.length; i++) {
			var li = document.createElement("li");
			li.innerHTML = input.files[i].name;
			ul.appendChild(li);			
		}

		if(!ul.hasChildNodes()) {
			var li = document.createElement("li");
			li.innerHTML = 'No Files Selected';
			ul.appendChild(li);
		}
	}
</script>

<div id=content class="col-sm-9"> 
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>  <!--文字編輯器套件-->
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->

<h1>新增最新消息</h1>
<hr>

<form id="form"  class="form-horizontal form-text-size" role="form"  action="news_run.php?th=<?php echo $th ?>" method="post" enctype="multipart/form-data">
<div class="form-group">
	<label class="control-label col-sm-2" for="news_title"><font color="red">*</font>標題：</label>
	<div class="col-sm-10">
		<input type="text" id="news_title" size="30" name="news_title"
		<?php if(!empty($_POST['news_title'])){ ?> value="<?php echo $_POST['news_title'] ?>"  <?php } ?>   required/><br>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2" for="news_date"><font color="red">*</font>日期：</label>
	<div class="col-sm-10">
		<input type="text" size="30" name="news_date" id="news_date" onClick="WdatePicker()"
		<?php if(!empty($_POST['news_date'])){ ?> value="<?php echo $_POST['news_date'] ?>"  <?php } ?> required/><br>
	</div>
</div>


<div class="form-group">
	<label class="control-label col-sm-2" for="news_contant"><font color="red">*</font>內容：</label>
	<div class="col-sm-10">
		<textarea cols="40" rows="20" id="news_contant" name="news_contant" 
		<?php if(!empty($_POST['news_contant'])){ ?> value="<?php echo $_POST['news_contant'] ?>"  <?php } ?>></textarea><br>
	</div>
</div>
		
<div class="form-group">
	<label class="control-label col-sm-2" for="file">上傳檔案：</label>
	<div class="col-sm-10">
		<input type="file" name="file[]" id="file" onChange="makeFileList();" multiple /><br><br>
		<div id="fileList"></div>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-10 col-sm-offset-2">
		<input id="insert"  class="btn btn-primary" type="submit" name="insert" value="送出"/>
	</div>
</div>

</form>
<br>
<!--改版改這裡~這裡以下都要改成這樣-->
</div>
</body>
<?php require_once('../base_footer.php')?>