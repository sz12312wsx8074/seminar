<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM instructions_for_poster where ifp_no=1";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

$arr_ext = array('.jpg', '.png', '.bmp', '.gif', '.tif', '.pcx', '.psd', '.jpeg');

if(isset($_POST['update'])){
	if(empty($_POST['ifp_content'])){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}else{
		if($_FILES['file']['error']==4){
			$sqlUpd = sprintf("UPDATE instructions_for_poster SET ifp_content='%s' WHERE ifp_no=1 ", $_POST['ifp_content']);
			$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
			if($sqlU){
				echo "<script>alert('已修改!!');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=1;url=instructions_for_poster.php?th=$th>");
			}
		}else{
			switch( $_FILES['file']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				// $this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				//$this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				//$this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				//$this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				//$this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失    
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				 //$this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				//$this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";			
				// $this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
			break;
			default:
				$ext = strrchr($_FILES['file']['name'],'.');
				$file_name = $th."instructions_for_poster".$ext;
				if(!in_array($ext, $arr_ext)){
					$file_extension='圖片上傳失敗，只允許 '.implode('、', $arr_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
				}else{
					$sqlUpd = sprintf("UPDATE instructions_for_poster SET ifp_content='%s', ifp_img='%s' WHERE ifp_no=1", $_POST['ifp_content'], $file_name);
					$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
					
					if($sqlU){
						$file_move="../file/".$th."/". $row_data['ifp_img'];
						unlink($file_move);
						move_uploaded_file($_FILES['file']['tmp_name'], "../file/".$th."/". iconv("utf-8", "big5", $file_name));
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=instructions_for_poster.php?th=$th>");
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
<form method="POST" action="instructions_for_poster_update.php?th=<?php echo $th; ?>" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">
<h1>修改論文海報演示說明</h1>
<hr>
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->

<font color="red"> * </font>
內文：<textarea id="ifp_content" name="ifp_content"><?php echo $row_data['ifp_content']; ?></textarea>
<br>

<font color="red">*</font>
原說明圖片：<img src="../file/<?php echo $th."/".$row_data['ifp_img']; ?>" width="30%" height="30%" /></text><br>
<br>
更改說明圖片：<input type="file" name="file" id="file" />
<br><br>

<input type="submit" class="btn btn-primary" name="update" id="update" value="修改">

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>