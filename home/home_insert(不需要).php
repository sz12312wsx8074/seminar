 <?php require_once('../base_home.php');  
 
$arr_ext = array('.pdf', '.doc', '.docx');
$over = 0;
if(isset($_POST['submit'])){
	for($i=0;$i<=1;$i++){
		if($i==0){
			$type = $_FILES['file']['error'];
			$name = $_FILES['file']['name'];
		}
		else{
			$type = $_FILES['fileE']['error'];
			$name = $_FILES['fileE']['name'];
		}
		switch ($type){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				$over = 1;
				// $this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				$over = 1;
				//$this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				$over = 1;
				//$this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
			break;  
			//case 4:
				//echo "<script>alert('沒有找到要上傳的檔案');</script>";
				//$this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
			//break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				$over = 1;
				//$this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失    
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				$over = 1;
				 //$this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				$over = 1;
				//$this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";			
				$over = 1;
				// $this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
			break;
			default:
				$ext = strrchr($name,'.');
				if($i==0){
					$a = "AIT2016_CFPs1";
					$an = $a . $ext;
					//echo "<script>alert('$an');</script>";
				}
				if($i==1){
					$b = "AIT2016_CFPs2";
					$bn = $b . $ext;
					//echo "<script>alert('$bn');</script>";
				}
				//echo "<script>alert('$type'+''x);</script>";
				if($type!=4){
					if(!in_array($ext, $arr_ext)){
						$file_extension='檔案上傳失敗，只允許 '.implode('、', $arr_ext).' 副檔名';
						$over = 1;
						echo "<script>alert('$file_extension');</script>";
					}
					else{
						echo "<script>alert('$over');</script>";
						if($i==1 and $over!=1){
							$sqlIns = sprintf("INSERT INTO home (home_title,home_contant,
							list_title,list_contant,home_CNfile,home_ENfile) values (%s,%s,%s,%s,'$an','$bn')",
							'"'.$_POST['home_title'].'"','"'.$_POST['home_contant'].'"',
							'"'.$_POST['list_title'].'"','"'.$_POST['list_contant'].'"');
							$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
							if($sqlI){
								//echo "<script>alert('1');</script>";
								move_uploaded_file($_FILES['file']['tmp_name'], "../file/" . iconv("utf-8", "big5", "AIT2016_CFPs1".$ext));
								//echo "<script>alert('2');</script>";
								move_uploaded_file($_FILES['fileE']['tmp_name'], "../file/" . iconv("utf-8", "big5", "AIT2016_CFPs2".$ext));
								echo "<script>alert('已新增!!');</script>";
								exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=home.php>');
							}
						}
					}
				}
		}
	}
}	
	

?>

<html>
<body>
<div id=content>
<form method="POST" action="home_insert.php" enctype="multipart/form-data">
<h1>修改介紹</h1>
標題:<input type="text" id="home_title" name="home_title"  value="<?php if(isset($_POST['cancel'])){echo "";}else{if(!empty($_POST['home_title'])){ echo $_POST['home_title']; }} ?>"  required/><br>

<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->
<font color="red">*</font>
內文：<textarea id="home_contant" name="home_contant" ><?php if(isset($_POST['cancel'])){echo "";}else{if(!empty($_POST['home_contant'])){ echo $_POST['home_contant']; }} ?></textarea>
<br><br>
中文檔案:<input type="file" name="file" id="file" required/><br>
英文檔案:<input type="file" name="fileE" id="fileE" required/><br>
<h1>修改種類</h1>
種類標題:<input type="text" id="list_title" name="list_title"  value="<?php if(isset($_POST['cancel'])){echo "";}else{if(!empty($_POST['list_title'])){ echo $_POST['list_title']; }} ?>"  required/><br>
<?php if(empty($_POST['list_contant']) or isset($_POST['cancel'])){ ?> <font color="red">*</font> <?php } ?>
內文：<textarea id="lilst_content" name="list_contant" ><?php if(isset($_POST['cancel'])){echo "";}else{if(!empty($_POST['list_contant'])){ echo $_POST['list_contant']; }} ?></textarea>
<br><br>


<input id="submit" name="submit" type="submit" value="儲存">
</form>
</div>

</body>
</html>


