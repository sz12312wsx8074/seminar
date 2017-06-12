<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM special_session where ss_no=1";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data); 

$arr_ext_pdf = array('.pdf');
$arr_ext_word = array('.doc', '.docx');

if(isset($_POST['update'])){
	if($_FILES['file_pdf']['error']==4 and $_FILES['file_word']['error']==4){
		echo "<script>alert('已修改!!');</script>";
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=special_session.php?th=$th>");
	}elseif($_FILES['file_pdf']['error']==4 and $_FILES['file_word']['error']!=4){
		$ext_word = strrchr($_FILES['file_word']['name'],'.');
		$file_name= $th."special_session".$ext_word;
		if(!in_array($ext_word, $arr_ext_word)){
			$file_extension='Word檔案上傳失敗，只允許 '.implode('、', $arr_ext_word).' 副檔名';
			echo "<script>alert('$file_extension');</script>";
		}else{
			switch ( $_FILES['file_word']['error'] ){
				case 1: 
					echo "<script>alert('Word檔案大小超出了伺服器上傳限制');</script>";
				break;  
				case 2:  
					echo "<script>alert('要上傳的Word檔案大小超出瀏覽器限制');</script>";
				break;  
				case 3:
					echo "<script>alert('Word檔案僅部分被上傳');</script>"; 
				break;  
				case 4:
					echo "<script>alert('沒有找到要上傳的Word檔案');</script>";
				break;
				case 5:  
					echo "<script>alert('伺服器臨時Word檔案遺失');</script>";
				break;  
				case 6:  
					echo "<script>alert('Word檔案寫入到站存資料夾錯誤');</script>";
				break;  
				case 7:  
					echo "<script>alert('無法寫入硬碟');</script>";
				break;  
				case 8: 
					echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				break;
				default:
					if($row_data['ss_word']!=null){
						$delfile_name="../file/".$th."/". $row_data['ss_word'];
						unlink($delfile_name);
					}
					$sqlUpd = sprintf("UPDATE special_session SET ss_word ='%s' WHERE ss_no=1", $file_name, 1);
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					
					if($sqlU){
						move_uploaded_file($_FILES['file_word']['tmp_name'], "../file/".$th."/". iconv("utf-8", "big5", $file_name));
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=special_session.php?th=$th>");
					}
			}
		}
	}elseif($_FILES['file_word']['error']==4 and $_FILES['file_pdf']['error']!=4){
		$ext_pdf = strrchr($_FILES['file_pdf']['name'],'.');
		if(!in_array($ext_pdf, $arr_ext_pdf)){
			$file_extension='PDF檔案上傳失敗，只允許 '.implode('、', $arr_ext_pdf).' 副檔名';
			echo "<script>alert('$file_extension');</script>";
		}else{
			switch ( $_FILES['file_pdf']['error'] ){
				case 1:  
					echo "<script>alert('PDF檔案大小超出了伺服器上傳限制');</script>";
				break;  
				case 2:  
					echo "<script>alert('要上傳的PDF檔案大小超出瀏覽器限制');</script>";
				break;  
				case 3:
					echo "<script>alert('PDF檔案僅部分被上傳');</script>";
				break;  
				case 4:
					echo "<script>alert('沒有找到要上傳的PDF檔案');</script>";
				break;  
				case 5:  
					echo "<script>alert('伺服器臨時PDF檔案遺失');</script>";
				break;  
				case 6:  
					echo "<script>alert('PDF檔案寫入到站存資料夾錯誤');</script>";
				break;  
				case 7:  
					echo "<script>alert('無法寫入硬碟');</script>";
				break;  
				case 8: 
					echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";			
				break;
				default:
					if($row_data['ss_pdf']!=null){
						$delfile_name="../file/".$th."/". $row_data['ss_pdf'];
						unlink($delfile_name);
					}
					$sqlUpd = sprintf("UPDATE special_session SET ss_pdf ='%s' WHERE ss_no=1", $th."special_session.pdf", 1);
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					
					if($sqlU){
						move_uploaded_file($_FILES['file_pdf']['tmp_name'], "../file/".$th."/". iconv("utf-8", "big5", $th."special_session.pdf"));
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=special_session.php?th=$th>");
					}
			}
		}
	}else{
		$ext_pdf = strrchr($_FILES['file_pdf']['name'],'.');
		$ext_word = strrchr($_FILES['file_word']['name'],'.');
		$file_name= $th."special_session".$ext_word;
		if(!in_array($ext_word, $arr_ext_word)){
			$file_extension='Word檔案上傳失敗，只允許 '.implode('、', $arr_ext_word).' 副檔名';
			echo "<script>alert('$file_extension');</script>";
		}elseif(!in_array($ext_pdf, $arr_ext_pdf)){
			$file_extension='PDF檔案上傳失敗，只允許 '.implode('、', $arr_ext_pdf).' 副檔名';
			echo "<script>alert('$file_extension');</script>";
		}else{
			switch ( $_FILES['file_word']['error'] ){
				case 1: 
					echo "<script>alert('Word檔案大小超出了伺服器上傳限制');</script>";
				break;  
				case 2:  
					echo "<script>alert('要上傳的Word檔案大小超出瀏覽器限制');</script>";
				break;  
				case 3:
					echo "<script>alert('Word檔案僅部分被上傳');</script>"; 
				break;  
				case 4:
					echo "<script>alert('沒有找到要上傳的Word檔案');</script>";
				break;
				case 5:  
					echo "<script>alert('伺服器臨時Word檔案遺失');</script>";
				break;  
				case 6: 
					echo "<script>alert('Word檔案寫入到站存資料夾錯誤');</script>";
				break;  
				case 7:  
					echo "<script>alert('無法寫入硬碟');</script>";
				break;  
				case 8: 
					echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				break;
				default:
					switch ( $_FILES['file_pdf']['error'] ){
						case 1:  
							echo "<script>alert('PDF檔案大小超出了伺服器上傳限制');</script>";
						break;  
						case 2:  
							echo "<script>alert('要上傳的PDF檔案大小超出瀏覽器限制');</script>";
						break;  
						case 3:
							echo "<script>alert('PDF檔案僅部分被上傳');</script>";
						break;  
						case 4:
							echo "<script>alert('沒有找到要上傳的PDF檔案');</script>";
						break;  
						case 5:  
							echo "<script>alert('伺服器臨時PDF檔案遺失');</script>";
						break;  
						case 6:  
							echo "<script>alert('PDF檔案寫入到站存資料夾錯誤');</script>";
						break;  
						case 7:  
							echo "<script>alert('無法寫入硬碟');</script>";
						break;  
						case 8: 
							echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";			
						break;
						default:
							if($row_data['ss_pdf']!=null){
								$delfile_name="../file/".$th."/". $row_data['ss_pdf'];
								unlink($delfile_name);
							}
							if($row_data['ss_word']!=null){
								$delfile_name="../file/".$th."/". $row_data['ss_word'];
								unlink($delfile_name);
							}
							
							$sqlUpd = sprintf("UPDATE special_session SET ss_pdf='%s', ss_word='%s' WHERE ss_no=1", $th."special_session.pdf", $file_name, 1);
							$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");

							if($sqlU){
								move_uploaded_file($_FILES['file_pdf']['tmp_name'], "../file/".$th."/". iconv("utf-8", "big5", $th."special_session.pdf"));
								move_uploaded_file($_FILES['file_word']['tmp_name'], "../file/".$th."/". iconv("utf-8", "big5", $file_name));
								echo "<script>alert('已修改!!');</script>";
								exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=special_session.php?th=$th>");
							}
					}
			}
		}
	}
}

?>


<html>
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<!--class="form-control"-->
<form method="POST" action="special_session_update.php?th=<?php echo $th; ?>" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">

<h1>修改特別議程</h1>
<hr>
<label class="control-label" >原始檔案</label><br>
<?php if($row_data['ss_pdf']!=null and $row_data['ss_word']!=null){ ?>
	<label class="control-label" >PDF</label><br>
	<button type="button" class="btn btn-link btn-sm" ><a Target="_blank" href="../file/<?php echo $th."/".$row_data['ss_pdf']; ?>">檔案預覽</a></button>　
	<button type="button" class="btn btn-link btn-sm" ><a href="../file/downloadfile.php?th=<?php echo $th; ?>&file=<?php echo $row_data['ss_pdf']; ?>">檔案下載</a></button>
	<input type="button" class="btn btn-primary" name="delete" id="delete" value="刪除" onclick="delect_Case(true, '<?php echo $row_data['ss_pdf']; ?>')">
	<br>
	<label class="control-label" >WORD</label><br>
	<button type="button" class="btn btn-link btn-sm" ><a href="../file/downloadfile.php?th=<?php echo $th; ?>&file=<?php echo $row_data['ss_word']; ?>">檔案下載</a></button>
	<input type="button" class="btn btn-primary" name="delete" id="delete" value="刪除" onclick="delect_Case(true, '<?php echo $row_data['ss_word']; ?>')"><br>
<?php }else{
	if($row_data['ss_pdf']!=null){ ?>
	<label class="control-label" >PDF</label><br>
	<button type="button" class="btn btn-link btn-sm" ><a Target="_blank" href="../file/<?php echo $th."/".$row_data['ss_pdf']; ?>">檔案預覽</a></button>　
	<button type="button" class="btn btn-link btn-sm" ><a href="../file/downloadfile.php?th=<?php echo $th; ?>&file=<?php echo $row_data['ss_pdf']; ?>">檔案下載</a></button>
<?php } ?>
<?php if($row_data['ss_word']!=null){ ?>
	<label class="control-label" >WORD</label><br>
	<button type="button" class="btn btn-link btn-sm" ><a href="../file/downloadfile.php?th=<?php echo $th; ?>&file=<?php echo $row_data['ss_word']; ?>">檔案下載</a></button>
<?php } 
} ?>

<br><br><br>

<label class="control-label" >修改檔案上傳：(可同時上傳PDF、WORD)</label><br>
<label class="control-label" >PDF檔案上傳：</label>
<input type="file" name="file_pdf" id="file_pdf" />
<label class="control-label" >or</label>
<br>
<label class="control-label" >Word檔案上傳：</label>
<input type="file" name="file_word" id="file_word" />

<br><br>

<input type="submit" class="btn btn-primary" name="update" id="update" value="修改">

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
<script>
function delect_Case(single, file) {
	var dele = confirm("確定要刪除這個檔案嗎？");
	if (dele == true){
		if (single){
			location.href='../file/deletefile.php?th=<?php echo $th; ?>&file='+file;
		}else{
			document.getElementById('dele').type = 'submit';
		}
	}
}
</script>

</body>
<?php require_once('../base_footer.php')?>