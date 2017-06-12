<?php require_once('../base_home.php');


$up_no=$_POST['up_no'];

$sql_time = "SELECT * FROM time_job where time_no = 1"; // 期間
$result_time = mysqli_query($link,$sql_time);
$time_data = mysqli_fetch_assoc($result_time);

$current_time = date("Y-m-d");
if ($current_time > $time_data['job_end_date']){
    echo "<script>alert('以超過論文截稿時間!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=login.php?th=$th>");
	return;
}

$query_data = "SELECT upsort_num FROM upload order by up_no DESC LIMIT 1";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);

$pdf = strrchr($_FILES['up_pdf']['name'], '.'); //取得副檔名 
$pdf_name = $row_data['upsort_num'].$pdf;
$pdf_ext = array('.pdf');

$word = strrchr($_FILES['up_word']['name'], '.'); //取得副檔名 
$word_name = $row_data['upsort_num'].$word;
$word_ext = array('.doc', '.docx');

$arr_ext = array('.pdf', '.doc', '.docx');

if(isset($_POST['insert'])){
	if($_FILES['up_word']['error']==4){ //只有pdf，沒有word
		switch ($_FILES['up_pdf']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");	
				// $this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
			break;
			default:
				if(!in_array($pdf, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				}else{
					$sqlUpd = sprintf("UPDATE upload SET up_pdf = '%s', up_status = '已上傳', up_upload = '1' where up_no = $up_no", $pdf_name);
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['up_pdf']['tmp_name'], 'upload/'.$th.'/'. iconv("utf-8", "big5", $pdf_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=paper_list.php?th=$th>");
					}
				}
		}
	}else if($_FILES['up_pdf']['error']==4){ //只有word，沒有pdf
		switch ($_FILES['up_word']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失    
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");	
				// $this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
			break;
			default:
				if(!in_array($word, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				}else{
					$sqlUpd = sprintf("UPDATE upload SET up_word = '%s', up_status = '已上傳', up_upload = '1' where up_no = $up_no", $word_name);
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['up_word']['tmp_name'], 'upload/'.$th.'/'.iconv("utf-8", "big5", $word_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=paper_list.php?th=$th>");
					}
				}
		}
	}else if($_FILES['up_word']['error']!=4 && $_FILES['up_pdf']['error']!=4){ //pdf、word都有
		switch ($_FILES['up_pdf']['error'] || $_FILES['up_word']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失    
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				// $this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
			break;
			default:
				if(!in_array($pdf, $pdf_ext) && !in_array($word, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $arr_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";		
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				}else if(!in_array($pdf, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				}else if(!in_array($word, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";		
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
				}else{
					$sqlUpd = sprintf("UPDATE upload SET up_pdf = '%s', up_word = '%s', up_status = '已上傳', up_upload = '1' where up_no = $up_no", $pdf_name, $word_name);
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['up_pdf']['tmp_name'], 'upload/'.$th.'/'. iconv("utf-8", "big5", $pdf_name));//複製檔案
						move_uploaded_file($_FILES['up_word']['tmp_name'], 'upload/'.$th.'/'. iconv("utf-8", "big5", $word_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=paper_list.php?th=$th>");
					}
				}
		}
	}
}


?>



<?php require_once('../base_footer.php')?>