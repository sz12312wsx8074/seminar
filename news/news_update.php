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

if($_GET){
	$news_no=$_GET["news_no"];
}


$query_data = "SELECT * FROM news where news_no='$news_no'";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);




if(isset($_POST['submit'])){
	$sqlIns = sprintf("UPDATE news set news_title='%s' , news_date='%s' ,news_contant='%s' WHERE news_no = '$news_no'"
	,$_POST['news_title'],$_POST['news_date'],$_POST['news_contant']);
	$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
	
	$news_no = $news_no+1;
	//foreach($_FILES['file']['tmp_name'] as $key => $tmp_name ){
	//	$file_name = $_FILES['file']['name'][$key];   
	//	$file_tmp_name = $_FILES['file']['tmp_name'][$key];
	//	$file = sprintf("INSERT INTO taiwan_game.news_file (file_name,news_no) value ('$file_name','$news_no')");
	//	$files = mysqli_query($link, $file) or die('error file MYSQL');
	//	move_uploaded_file($file_tmp_name,"../news_upload/".iconv('UTF-8','BIG5',$file_name));}					

	if($sqlI){
		echo "<script>alert('已修改!!');</script>"; 
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=news.php?th=$th>");
	}
}
			
$query_file = "SELECT * FROM news_file WHERE news_no='$news_no'";
$files = mysqli_query($link,$query_file);
$file_data= mysqli_fetch_assoc($files);
$file_num = mysqli_num_rows($files);
			

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
			// var button = document.createElement("button");
			li.innerHTML = input.files[i].name;
			// button.innerHTML = "清除";
			// button.type = "submit";
			// button.onClick(alert("hi"));
			ul.appendChild(li);
			// ul.appendChild(button);
			// removee.appendChild(button);
			
			
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

<div class="page-header">
	<h1>修改最新消息</h1>
</div>

<form id="form"  class="form-horizontal form-text-size" role="form"  action="news_run.php?news_no=<?php echo $news_no ?>&th=<?php echo $th ?>" method="post" enctype="multipart/form-data">
<input type="hidden" id="news_no" name="news_no" value="<?php echo $news_no ?>">

<div class="form-group">
	<label class="control-label col-sm-2" for="news_title"><font color="red">*</font>標題：</label>
	<div class="col-sm-10">
		<input type="text" id="news_title" name="news_title" value="<?php echo $row_data['news_title']; ?>" required/><br>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2" for="news_date"><font color="red">*</font>日期：</label>
	<div class="col-sm-10">
		<input type="text" name="news_date" id="news_date" onClick="WdatePicker()" value="<?php echo $row_data['news_date']; ?>" required/><br>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-2" for="news_contant"><font color="red">*</font>內容：</label>
	<div class="col-sm-10">
		<textarea id="news_contant" name="news_contant"><?php echo $row_data['news_contant'] ?> </textarea><br>
	</div>
</div>

<?php if($file_num!=0){ ?>
	<div class="form-group">
		<label class="control-label col-sm-2" for="news_file">原來檔案：</label>
		<div class="col-sm-10">
			<?php do{
				if($file_data['news_no'] == $news_no){ ?>
					<a Target="_blank" href="../news/news_file/<?php echo $file_data['file_name']; ?> "><?php echo $file_data['file_name']; ?></a>
					<input type="button" class="btn btn-default" name="delete" id="delete" value="刪除" onclick="delete_Case(true, '<?php echo $file_data['file_name']; ?>', '<?php echo $news_no ?>')"><br>
			<?php echo '<br>'; 
				}
			}while($file_data= mysqli_fetch_assoc($files)); ?>
		</div>
	</div>
<?php } ?>





<div class="form-group">
	<label class="control-label col-sm-2" for="file">上傳檔案：</label>
	<div class="col-sm-10">
		<input type="file" name="file[]" id="file" onChange="makeFileList();" multiple />
		<div id="fileList"></div>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<input id="update" class="btn btn-primary" type="submit" name="update" value="修改"/>
	</div>
</div>

<br><br><br><br><br>
</form>
<br>
<!--改版改這裡~這裡以下都要改成這樣-->

<script>
function delete_Case(single, file, no) {
	var dele = confirm("確定要刪除這個檔案嗎？");
	if (dele == true){
		if (single){
			location.href='news_file/delefile.php?file='+file+'&news_no='+no;
		}else{
			document.getElementById('dele').type = 'submit';
		}
	}
}
</script>

</div>
</body>
<?php require_once('../base_footer.php')?>