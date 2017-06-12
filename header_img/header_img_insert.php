<?php require_once('../seminar_connect.php');

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM header_img";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

$arr_ext = array('.jpg', '.JPG', '.png', '.PNG', '.bmp', '.gif', '.tif', '.pcx', '.psd', '.jpeg');

if(isset($_POST['submit'])){
	switch ( $_FILES['file']['error'] ){
		case 1: 
			echo "<script>alert('圖片大小超出了伺服器上傳限制');</script>";
		break;  
		case 2:  
			echo "<script>alert('要上傳的圖片大小超出瀏覽器限制');</script>";
		break;  
		case 3:
			echo "<script>alert('圖片僅部分被上傳');</script>"; 
		break;  
		case 4:
			echo "<script>alert('沒有找到要上傳的圖片');</script>";
		break;
		case 5:  
			echo "<script>alert('伺服器臨時圖片遺失');</script>";
		break;  
		case 6:  
			echo "<script>alert('圖片寫入到站存資料夾錯誤');</script>";
		break;  
		case 7:  
			echo "<script>alert('無法寫入硬碟');</script>";
		break;  
		case 8: 
			echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
		break;
		default:
			$ext = strrchr($_FILES['file']['name'],'.');
			if(!in_array($ext, $arr_ext)){
				$file_extension='圖片上傳失敗，只允許 '.'　'.implode('、', $arr_ext).' 副檔名';
				echo "<script>alert('$file_extension');</script>";
			}else{
				move_uploaded_file($_FILES['file']['tmp_name'], iconv("utf-8", "big5", $th."header_img".$ext));
				$sqlIns = sprintf("INSERT INTO header_img (header_img) values (%s)", '"'. $th."header_img".$ext .'"');
				$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
				echo "<script>alert('已上傳!!');</script>";
				exit('<script>window.opener.location.reload(); window.opener=null; window.close();</script>');
			}
	}
}

if(isset($_POST['update'])){
	if($_FILES['file']['error']!=4){
		switch ( $_FILES['file']['error'] ){
			case 1: 
				echo "<script>alert('圖片大小超出了伺服器上傳限制');</script>";
			break;  
			case 2:  
				echo "<script>alert('要上傳的圖片大小超出瀏覽器限制');</script>";
			break;  
			case 3:
				echo "<script>alert('圖片僅部分被上傳');</script>"; 
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的圖片');</script>";
			break;
			case 5:  
				echo "<script>alert('伺服器臨時圖片遺失');</script>";
			break;  
			case 6:  
				echo "<script>alert('圖片寫入到站存資料夾錯誤');</script>";
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
			break;
			default:
				$ext = strrchr($_FILES['file']['name'],'.');
				if(!in_array($ext, $arr_ext)){
					$file_extension='圖片上傳失敗，只允許 '.'　'.implode('、', $arr_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
				}else{
					unlink(iconv("utf-8", "big5", $row_data['header_img'] ));
					move_uploaded_file($_FILES['file']['tmp_name'], iconv("utf-8", "big5", $th."header_img".$ext));
					$sqlUpd = sprintf("UPDATE header_img SET header_img='%s'", $th."header_img".$ext);
					$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
					echo "<script>alert('已修改!!');</script>";
					exit('<script>window.opener.location.reload(); window.opener=null; window.close();</script>');
				}
		}
	}
	
}

?>


<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
	<form class="form-horizontal form-text-size" method="POST" action="header_img_insert.php?th=<?php echo $th; ?>" enctype="multipart/form-data">
	
	<?php if($totalRows_data==0){ ?>
	<title>新增頁首圖片</title>
	<center><h1>新增頁首圖片</h1></center><hr>
		<br>
		<div class="form-group"> 
			<center><input type="file" name="file" id="file" required /></center>
			<div class="col-sm-offset-2 col-sm-10">
			<br>
				<center><input type="submit" class="btn btn-primary" name="submit" id="submit" value="送出"></center>
			</div>
		</div>
	<?php }else{ ?>
	<title>修改頁首圖片</title>
	<center><h1>修改頁首圖片</h1></center><hr>
		<div class="form-group">
			<center><label class="control-label col-sm-2" for="file">現在頁首圖片：</label>
			<img src=<?php echo $row_data['header_img'];?> width="300" height="80" /></center>
		</div>
		<br>
		<div class="form-group">
			<center><label class="control-label col-sm-2" for="file">變更圖片：建議使用1350*150</label></center>
			<br>
			<center><input type="file" name="file" id="file" /></center>
		</div>
		<center><input type="submit" class="btn btn-primary" name="update" id="update" value="修改" /></center>
	<?php } ?>

	</form>
</div>
</body>