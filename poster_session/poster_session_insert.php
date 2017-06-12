<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$arr_ext_pdf = array('.pdf');
$arr_ext_word = array('.doc', '.docx');

if(isset($_POST['submit'])){
	if($_FILES['file_pdf']['error']==4 and $_FILES['file_word']['error']==4){
		echo "<script>alert('沒有找到要上傳的檔案');</script>";
	}elseif($_FILES['file_pdf']['error']==4 and $_FILES['file_word']['error']!=4){
		$ext_word = strrchr($_FILES['file_word']['name'],'.');
		$file_name= $th."poster_session".$ext_word;
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
					$sqlIns = sprintf("INSERT INTO poster_session (ps_word) values (%s)", '"'.$file_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					
					if($sqlI){
					move_uploaded_file($_FILES['file_word']['tmp_name'], "../file/".$th."/". iconv("utf-8", "big5", $file_name));
					echo "<script>alert('已新增!!');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=poster_session.php?th=$th>");
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
					$sqlIns = sprintf("INSERT INTO poster_session (ps_pdf) values (%s)", '"'.$th."poster_session.pdf".'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					
					if($sqlI){
					move_uploaded_file($_FILES['file_pdf']['tmp_name'], "../file/".$th."/". iconv("utf-8", "big5", $th."poster_session.pdf"));
					echo "<script>alert('已新增!!');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=poster_session.php?th=$th>");
					}
			}
		}
	}else{
		$ext_pdf = strrchr($_FILES['file_pdf']['name'],'.');
		$ext_word = strrchr($_FILES['file_word']['name'],'.');
		$file_name= $th."poster_session".$ext_word;
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
							$sqlIns = sprintf("INSERT INTO poster_session (ps_pdf, ps_word) values (%s, %s)", '"'.$th."poster_session.pdf".'"', '"'.$file_name.'"');
							$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
							
							if($sqlI){
							move_uploaded_file($_FILES['file_pdf']['tmp_name'], "../file/".$th."/". iconv("utf-8", "big5", $th."poster_session.pdf"));
							move_uploaded_file($_FILES['file_word']['tmp_name'], "../file/".$th."/". iconv("utf-8", "big5", $file_name));
							echo "<script>alert('已新增!!');</script>";
							exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=poster_session.php?th=$th>");
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
<form method="POST" action="poster_session_insert.php?th=<?php echo $th; ?>" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">

<h1>新增海報議程</h1>
<hr>
<label class="control-label" > <font color="red">*</font>檔案上傳：(可同時上傳PDF、WORD)</label>
<br>
<label class="control-label" >PDF上傳：</label>
<input type="file" name="file_pdf" id="file_pdf" />
<label class="control-label"></font>or</label>
<br>
<label class="control-label" >Word上傳：</label>
<input type="file" name="file_word" id="file_word" />
<br><br>
<input type="submit" class="btn btn-primary" name="submit" id="submit" value="送出">

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>