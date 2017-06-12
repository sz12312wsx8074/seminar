<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$arr_ext = array('.jpg', '.png', '.bmp', '.gif', '.tif', '.pcx', '.psd', '.jpeg');

if(isset($_POST['submit'])){
	if(empty($_POST['ifp_content'])){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}else{
		switch ( $_FILES['file']['error']){  
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
					echo '圖片上傳失敗，只允許 '.implode('、', $arr_ext).' 副檔名';
				}else{
					$sqlIns = sprintf("INSERT INTO instructions_for_poster (ifp_content, ifp_img) values (%s, %s)",
					'"'.$_POST['ifp_content'].'"', '"'.$file_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					
					if($sqlI){
						move_uploaded_file($_FILES['file']['tmp_name'], "../file/".$th."/". iconv("utf-8", "big5", $file_name));
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=instructions_for_poster.php?th=$th>");
					}
				}
		}
	}
}	

?>


<html>
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<form method="POST" action="instructions_for_poster_insert.php?th=<?php echo $th; ?>" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">
<!--class="form-control"-->   <!--改版改這裡~~~~~~~ -->
<h1>新增論文海報演示說明</h1>
<hr>
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->

<font color="red">*</font>
內文：<textarea id="ifp_content" name="ifp_content"><?php if(isset($_POST['cancel'])){echo "";}else{if(!empty($_POST['ifp_content'])){ echo $_POST['ifp_content']; }} ?></textarea>
<br><br>

<font color="red">*</font>
說明圖片：<input type="file" name="file" id="file" required/>
<br><br>

<input type="submit" class="btn btn-primary" name="submit" id="submit" value="送出">


</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>