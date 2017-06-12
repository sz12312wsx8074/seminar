<?php require_once('../base_home.php');  

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if($button_on==false){
	echo "<script>alert('研討會時間已過!');</script>";
	exit ("<script>location.href='/seminar/home/home.php?th=$th'</script>");
} 

$query_data = "SELECT * FROM home ";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);  

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
					$a = "AIT".$th."_CFPs1";
					$an = $a . $ext;
				}
				if($i==1){
					$b = "AIT".$th."_CFPs2";
					$bn = $b . $ext;
				}
				//if($type!=4){
					if(!in_array($ext, $arr_ext) and $type!=4){
						$file_extension='檔案上傳失敗，只允許 '.implode('、', $arr_ext).' 副檔名';
						$over = 1;
					}
					else{
						if($i==1 and $over!=1){
							$CFPs1 = "AIT".$th."_CFPs1";
							$CFPs2 = "AIT".$th."_CFPs2";
							$sqlIns = sprintf("UPDATE home SET home_title='%s',home_contant='%s',list_title='%s',list_contant='%s' ",
							$_POST['home_title'],$_POST['home_contant'],$_POST['list_title'],$_POST['list_contant']);
							
							$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");

							if($an){ 
								if($an==$CFPs1){$an = $row_data['home_CNfile'];}
								$file_sqlins = sprintf("UPDATE home SET home_CNfile='$an'");
								$file_sqli = mysqli_query($link, $file_sqlins) or die ("MYSQL Error");
							}
							
							if($bn){ 
								if($bn==$CFPs2){$bn = $row_data['home_ENfile'];}
								$fileE_sqlins = sprintf("UPDATE home SET home_ENfile='$bn'");
								$fileE_sqli = mysqli_query($link, $fileE_sqlins) or die ("MYSQL Error");

							}
							
							if($sqlI==1 and $file_sqli==1 and $fileE_sqli==1 ){
								
								move_uploaded_file($_FILES['file']['tmp_name'], "../file/$th/" . iconv("utf-8", "big5", "$an"));
								move_uploaded_file($_FILES['fileE']['tmp_name'], "../file/$th/" . iconv("utf-8", "big5", "$bn"));
								echo"<script>alert('已修改！');</script>";
								exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=home.php?th=$th>");
							}
						}
					}
				//}
		}
	}
}	

if($row_data['home_title']=='請輸入標題'&&$row_data['home_contant']=='請輸入敘述'&&$row_data['list_title']=='請輸入敘述'&&$row_data['list_contant']=='請輸入內容'&&$row_data['home_CNfile']=='0'){
	$new = true;
}else{
	$new = false;
}

?>

<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->

<html>
<body>
<div id=content class="col-sm-9">
<form class="form-horizontal form-text-size" method="POST" action="home_update.php?th=<?php echo $th ?>" enctype="multipart/form-data">
	<?php if($new){ ?>
		<h1>新增介紹</h1>
	<?php }else{ ?>
		<h1>修改介紹</h1><?php } ?>
<hr>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="form-group">
			<label class="control-label col-sm-2"><font color="red">*</font>標題:</label>
			<div class="col-sm-10">
				<input type="text" id="home_title" name="home_title"  value="<?php echo $row_data['home_title']; ?>" required/>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<label class="control-label col-sm-2"><font color="red">*</font>內文：</label>
			<div class="col-sm-10">
				<textarea id="home_contant" name="home_contant" ><?php echo $row_data['home_contant']; ?></textarea>
			</div>
		</div>
		<?php if($row_data['home_CNfile']=='0' and $row_data['home_ENfile']=='0'){ ?>
			<div class="form-group">
				<label class="control-label col-sm-2"><font color="red">*</font>中文檔案:</label>
				<div class="col-sm-10">
					<input type="file" name="file" id="file" required/>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2"><font color="red">*</font>英文檔案:</label>
				<div class="col-sm-10">
					<input type="file" name="fileE" id="fileE" required/>
				</div>
			</div>
		<?php }
		else{?>
			<div class="form-group">
				<label class="control-label col-sm-2">更改中文檔案:</label>
				<div class="col-sm-10">
					<input type="file" name="file" id="file" />
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">更改英文檔案:</label>
				<div class="col-sm-10">
					<input type="file" name="fileE" id="fileE" />
				</div>
			</div>
		<?php } ?>
	</div>
</div>


	<?php if($new){ ?>
		<h1>新增種類</h1>
	<?php }else{ ?>
		<h1>修改種類</h1><?php } ?>
<hr>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="form-group">
			<label class="control-label col-sm-2"><font color="red">*</font>種類標題:</label>
			<div class="col-sm-10">
				<input type="text" id="list_tile" name="list_title" value="<?php echo $row_data['home_title']; ?>" required/>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="form-group">
			<label class="control-label col-sm-2"><font color="red">*</font>內文：</label>
			<div class="col-sm-10">
			<textarea id="lilst_content" name="list_contant" ><?php echo $row_data['list_contant']; ?></textarea>
			</div>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-10 col-sm-offset-2">
		<input class="btn btn-primary" id="submit" name="submit" type="submit" value="<?php if($new){ ?>新增<?php }else{ ?>修改<?php } ?>">
	</div>
</div>


</body>
</html>


