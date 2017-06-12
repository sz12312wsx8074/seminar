<?php require_once('../base_home.php');



$sql_time = "SELECT * FROM time_job where time_no = 3"; // 期間
$result_time = mysqli_query($link,$sql_time);
$time_data = mysqli_fetch_assoc($result_time);

$current_time = date("Y-m-d");
if ($current_time > $time_data['job_end_date']){
    echo "<script>alert('以超過論文截稿時間!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=login.php?th=$th>");
	return;
}

$sql = "SELECT * FROM accepted_list";
$result = mysqli_query($link,$sql) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($result);
$row_sum = mysqli_num_rows($result);

$arr_ext = array('.doc', '.docx');

if(isset($_POST['insert'])){
	switch ($_FILES['ca_authorize']['error']){ 
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				// $this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				// $this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				// $this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				// $this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				// $this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失 
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				// $this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				// $this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";		
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				// $this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
			break;
		default:
			$authorize = strrchr($_FILES['ca_authorize']['name'], '.'); //取得副檔名 
			$ca_authorize_name = $th."_authorize".$authorize;
			if(!in_array($authorize, $arr_ext)){
				$file_extension='檔案上傳失敗，只允許 '.implode('、', $arr_ext).' 副檔名';
				echo "<script>alert('$file_extension');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
			}else if($row_sum == 0){
				$sqlIns = sprintf("INSERT INTO accepted_list (ca_authorize) VALUES (%s)", '"'.$ca_authorize_name.'"');
				$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
				if($sqlI){
					move_uploaded_file($_FILES['ca_authorize']['tmp_name'], '../login/camera_ready/'. $th.'/'.iconv("utf-8", "big5", $ca_authorize_name));//複製檔案
					echo "<script>alert('已新增!!');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				}
			}else{
				$sqlUpd = sprintf("UPDATE accepted_list SET ca_authorize = '%s' WHERE ac_no = '1' ", $ca_authorize_name, 1);
				$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
				if($sqlU){
					move_uploaded_file($_FILES['ca_authorize']['tmp_name'], '../login/camera_ready/'.$th.'/'.iconv("utf-8", "big5", $ca_authorize_name));//複製檔案
					echo "<script>alert('已新增!!');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				}
			}
	}
}

if(isset($_POST['update'])){
	switch($_FILES['ca_authorize']['error']){  
		case 1:  
			echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
			//$this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
		break;  
		case 2:  
			echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
			//$this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
		break;  
		case 3:
			echo "<script>alert('檔案僅部分被上傳');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
			//$this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
		break;  
		case 4:
			echo "<script>alert('沒有找到要上傳的檔案');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
			//$this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
		break;  
		case 5:  
			echo "<script>alert('伺服器臨時檔案遺失');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
			//$this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失    
		break;  
		case 6:  
			echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
			//$this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
		break;  
		case 7:  
			echo "<script>alert('無法寫入硬碟');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
			//$this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
		break;  
		case 8: 
			echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
			//$this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
		break;
		default:			
			$authorize = strrchr($_FILES['ca_authorize']['name'], '.'); //取得副檔名 
			$ca_authorize_name = $th."_authorize".$authorize;
			if(!in_array($authorize, $arr_ext)){
				$file_extension='檔案上傳失敗，只允許 '.implode('、', $arr_ext).' 副檔名';
				echo "<script>alert('$file_extension');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
			}else{
				if($row_data['ca_authorize'] != null){
					$delfile_name='../login/camera_ready/'.$th.'/' . $row_data['ca_authorize'];
					unlink($delfile_name);
				}
				
				$sqlUpd = sprintf("UPDATE accepted_list SET ca_authorize = '%s' WHERE ac_no = '1' ", $ca_authorize_name, '1');
				$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
				if($sqlU){
					move_uploaded_file($_FILES['ca_authorize']['tmp_name'],'../login/camera_ready/'.$th.'/'. iconv("utf-8", "big5", $ca_authorize_name));//複製檔案
					echo "<script>alert('已修改!!');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready.php?th=$th'>");
				}
			}
	}	
}
	
?>
