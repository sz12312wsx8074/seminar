<?php require_once('../base_home.php');

 

$query_data = "SELECT * FROM paper_format";
$data = mysqli_query($link,$query_data);
$row_data= mysqli_fetch_assoc($data);

?>

<div id=content class="col-sm-9">

<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>  <!--文字編輯器套件-->
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->


<h1>新增論文格式</h1>

<form class="form-horizontal form-text-size" method="POST" action="paper_format_run.php?th=<?php echo $th; ?>" enctype="multipart/form-data">
<hr>

<div class="form-group">
    <label class="control-label col-sm-2" for="pa_content"> <font color="red">*</font>內容：</label>
    <div class="col-sm-10">
		<textarea id="pa_content" name="pa_content" ></textarea>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2" >上傳檔案：</label><br>
	<div class="col-sm-10 col-sm-offset-2">
		Word version(English)：<input type="file" id="pa_ewfile" name="pa_ewfile"/>
		<br>

		Word version(中文版)：<input type="file" id="pa_cwfile" name="pa_cwfile"/>
		<br>

		PDF version(English)：<input type="file" id="pa_epfile" name="pa_epfile"/>
		<br>

		PDF version(中文版)：<input type="file" id="pa_cpfile" name="pa_cpfile"/>
		<br>

		<input class="btn btn-primary" type="submit" id="insert" name="insert" value="送出"/>
	</div>
</div>
	
</form>
<br>

</div>
</div> <!--wrapper-->
</body>

<?php require_once('../base_footer.php')?>