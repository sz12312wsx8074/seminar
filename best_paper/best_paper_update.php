<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}
$sql = "SELECT * FROM best_paper";
$result = mysqli_query($link,$sql) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($result);
$row_sum = mysqli_num_rows($result);

if($_GET){
	$bp_no = $_GET['bp_no'];
}

$query_data = "SELECT * FROM best_paper where bp_no = '$bp_no'";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data2 = mysqli_fetch_assoc($data);




$pdf_ext = array('.pdf');


if(isset($_POST['update'])){
	if(empty($_POST['bp_content'])){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}
	switch($_FILES['file_pdf']['error']){  
		case 1:  
			echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='best_paper_update.php?th=$th&bp_no=$bp_no'>");
			//$this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
		break;  
		case 2:  
			echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='best_paper_update.php?th=$th&bp_no=$bp_no'>");
			//$this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
		break;  
		case 3:
			echo "<script>alert('檔案僅部分被上傳');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='best_paper_update.php?th=$th&bp_no=$bp_no'>");
			//$this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
		break;  
		case 4:
			echo "<script>alert('沒有找到要上傳的檔案');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='best_paper_update.php?th=$th&bp_no=$bp_no'>");
			//$this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
		break;  
		case 5:  
			echo "<script>alert('伺服器臨時檔案遺失');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='best_paper_update.php?th=$th&bp_no$bp_no'>");
			//$this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失    
		break;  
		case 6:  
			echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='best_paper_update.php?th=$th&bp_no=$bp_no'>");
			//$this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
		break;  
		case 7:  
			echo "<script>alert('無法寫入硬碟');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='best_paper_update.php?th=$th&bp_no=$bp_no'>");
			//$this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
		break;  
		case 8: 
			echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='best_paper_update.php?th=$th&bp_no=$bp_no'>");
			//$this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
		break;
		default:
			$pdf = strrchr($_FILES['file_pdf']['name'], '.'); //取得副檔名 
			$pdf_name = date('y')."_best_paper".$pdf;;				
			if(!in_array($pdf, $pdf_ext)){
				$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
				echo "<script>alert('$file_extension');</script>";
				// exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='best_paper_update.php?th=$th&bp_no=$bp_no'>");
			}else{
				$sqlUpd = sprintf("UPDATE best_paper SET bp_content='%s',bp_pdf = '%s' WHERE bp_no = '$bp_no' ", $_POST['bp_content'], $pdf_name);
				$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
				if($sqlU){
					move_uploaded_file($_FILES['file_pdf']['tmp_name'],"../file/$th/". iconv("utf-8", "big5", $pdf_name));//複製檔案
					echo "<script>alert('已修改!!');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='best_paper.php?th=$th&bp_no=$bp_no'>");
				}
			}
		}
	}

?>



<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>  <!--文字編輯器套件-->
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->

<h1>修改最佳論文獎</h1>
<form method="POST" action="best_paper_update.php?bp_no=<?php echo $bp_no ?>&th=<?php echo $th ?>" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">

<hr>


<div class="form-group">
    <label class="control-label col-sm-2" for="bp_content"> <font color="red">*</font>內容：</label>
    <div class="col-sm-10">
		<textarea type="text" id="bp_content" name="bp_content"><?php echo $row_data['bp_content'] ?> </textarea>
    </div>
</div>

<div class="form-group">
<label class="control-label col-sm-2">原始檔案</label><br>
<div class="col-sm-10 col-sm-offset-2">
<?php if($row_data['bp_pdf']!=null){ ?>
	<label class="control-label" >PDF</label><br>
	<a Target="_blank" href="../file/<?php echo $th ?>/<?php echo $row_data['bp_pdf']; ?>">檔案預覽</a>　
	<a href="../file/downloadfile.php?th=<?php echo $th ?>&file=<?php echo $row_data['bp_pdf']; ?>">檔案下載</a>
		
	
<?php }else{
	if($row_data['bp_pdf']!=null){ ?>
	<label class="control-label" >PDF</label><br>
	<a Target="_blank" href="../file/<?php echo $th ?>/<?php echo $row_data2['bp_pdf']; ?>">檔案預覽</a>
	<a href="../file/downloadfile.php?th=<?php echo $th ?>&file=<?php echo $row_data2['bp_pdf']; ?>">檔案下載</a>
	<?php } ?>
<?php } ?>
</div>
</div>
<br>

<div class="form-group">
<label class="control-label col-sm-2">修改檔案上傳</label>
<br>
<label class="control-label">PDF 檔案上傳：</label>
<div class="col-sm-10 col-sm-offset-2">
<br>
<input type="file" name="file_pdf" id="file_pdf" />
<br><br>
<input type="submit" class="btn btn-primary" name="update" id="update" value="修改">
</div>
</div>





</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
<script>
function delect_Case(single, file) {
	var dele = confirm("確定要刪除這個檔案嗎？");
	if (dele == true){
		if (single){
			location.href='../file/deletefile.php?file='+file;
		}else{
			document.getElementById('dele').type = 'submit';
		}
	}
}
</script>

</body>
<?php require_once('../base_footer.php')?>