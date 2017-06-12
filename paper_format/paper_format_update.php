<?php require_once('../base_home.php');

$query_data = "SELECT * FROM paper_format WHERE pa_no = '1' ";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);

?>

<div id=content class="col-sm-9">

<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>  <!--文字編輯器套件-->
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->


<h1>修改論文格式</h1>

<form class="form-horizontal form-text-size" method="POST" action="paper_format_run.php?th=<?php echo $th; ?>" enctype="multipart/form-data">
<hr>

<!--<input type="hidden" name="pa_no" value="<?php echo $pa_no ;?>">-->

<div class="form-group">
    <label class="control-label col-sm-2" for="pa_content"> <font color="red">*</font>內容：</label>
    <div class="col-sm-10">
		<textarea type="text" id="pa_content" name="pa_content"><?php echo $row_data['pa_content'] ?> </textarea>
    </div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2">原始檔案：</label>
	<div class="col-sm-10 col-sm-offset-2">
		<?php if($row_data['pa_ewfile'] != NULL){ ?>
			Word version(English)：<a href="../paper_format/paper_format_file/downloadfile.php?pa_ewfile=<?php echo $row_data['pa_ewfile'];?>">English</a>
			<input class="btn btn-default" type="button" id="delete" name="delete" value="刪除" onclick="delete_Case(true, '<?php echo $row_data['pa_ewfile']; ?>')"/>
		<?php }else{ ?>
			Word version(English)：
		<?php } ?>
		<br><br>

		<?php if($row_data['pa_cwfile'] != NULL){ ?>
			Word version(中文版)：<a href="../paper_format/paper_format_file/downloadfile.php?pa_cwfile=<?php echo $row_data['pa_cwfile'];?>">中文版</a>
			<input class="btn btn-default" type="button" id="delete" name="delete" value="刪除" onclick="delete_Case(true, '<?php echo $row_data['pa_cwfile']; ?>')"/>
		<?php }else{ ?>
			Word version(中文版)：
		<?php } ?>
		<br><br>

		<?php if($row_data['pa_epfile'] != NULL){ ?>
			PDF version(English)：<a Target="_blank" href="../paper_format/paper_format_file/<?php echo $row_data['pa_epfile'];?>">English</a>
			<input class="btn btn-default" type="button" id="delete" name="delete" value="刪除" onclick="delete_Case(true, '<?php echo $row_data['pa_epfile']; ?>')"/>
		<?php }else{ ?>
			PDF version(English)：
		<?php } ?>
		<br><br>

		<?php if($row_data['pa_cpfile'] != NULL){ ?>
			PDF version(中文版)：<a Target="_blank" href="../paper_format/paper_format_file/<?php echo $row_data['pa_cpfile'];?>">中文版</a>
			<input class="btn btn-default" type="button" id="delete" name="delete" value="刪除" onclick="delete_Case(true, '<?php echo $row_data['pa_cpfile']; ?>')"/>
		<?php }else{ ?>
			PDF version(中文版)：
		<?php } ?>
		<br>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2">上傳新檔：</label>
	<div class="col-sm-10 col-sm-offset-2">
		Word version(English)：<input type="file" id="pa_ewfile" name="pa_ewfile"/>
		<br>

		Word version(中文版)：<input type="file" id="pa_cwfile" name="pa_cwfile"/>
		<br>

		PDF version(English)：<input type="file" id="pa_epfile" name="pa_epfile"/>
		<br>

		PDF version(中文版)：<input type="file" id="pa_cpfile" name="pa_cpfile"/>
		<br>
		
		<input class="btn btn-primary" type="submit" id="update" name="update" value="修改"/>
	</div>
</div>

</form>
<br>

</div>

<script>
function delete_Case(single, file) {
	var dele = confirm("確定要刪除這個檔案嗎？");
	if (dele == true){
		if (single){
			location.href='../paper_format/paper_format_file/deletefile.php?th=<?php echo $th;?>&file='+file;
		}else{
			document.getElementById('dele').type = 'submit';
		}
	}
}
</script>

</div> <!--wrapper-->
</body>

<?php require_once('../base_footer.php')?>