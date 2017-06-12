<?php require_once('../base_home.php');

$query_data = "SELECT * FROM paper_format";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);

$word_ext = array('.doc', '.docx');
$pdf_ext = array('.pdf');
$file_ext = array('.doc', '.docx', '.pdf');

if(isset($_POST['insert'])){
	if(empty($_POST['pa_content'])){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4 && $_FILES['pa_epfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_ewfile、pa_epfile、pa_cwfile、pa_cpfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cwfile']['error'] || $_FILES['pa_epfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				eexit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$ewfile_name = $th."_format_E".$ewfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$epfile_name = $th."_format_E".$epfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_ewfile, pa_cwfile, pa_epfile, pa_cpfile) VALUES (%s, %s, %s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$ewfile_name.'"', '"'.$cwfile_name.'"', '"'.$epfile_name.'"', '"'.$cpfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4 && $_FILES['pa_epfile']['error']!=4){ //pa_ewfile、pa_cwfile、pa_epfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cwfile']['error'] || $_FILES['pa_epfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");			
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$ewfile_name = $th."_format_E".$ewfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$epfile_name = $th."_format_E".$epfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_ewfile,pa_cwfile, pa_epfile) VALUES (%s, %s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$ewfile_name.'"', '"'.$cwfile_name.'"', '"'.$epfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4 && $_FILES['pa_epfile']['error']!=4){ //pa_ewfile、pa_epfile、pa_cpfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cpfile']['error'] || $_FILES['pa_epfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");			
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$ewfile_name = $th."_format_E".$ewfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$epfile_name = $th."_format_E".$epfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_ewfile,pa_cpfile, pa_epfile) VALUES (%s, %s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$ewfile_name.'"', '"'.$cpfile_name.'"', '"'.$epfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_ewfile、pa_cwfile、pa_cpfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cwfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$ewfile_name = $th."_format_E".$ewfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_ewfile, pa_cwfile, pa_cpfile) VALUES (%s, %s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$ewfile_name.'"', '"'.$cwfile_name.'"', '"'.$cpfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_epfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_epfile、pa_cwfile、pa_cpfile
		switch ($_FILES['pa_epfile']['error'] || $_FILES['pa_cwfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名 
				if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$epfile_name = $th."_format_E".$epfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_epfile, pa_cwfile, pa_cpfile) VALUES (%s, %s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$epfile_name.'"', '"'.$cwfile_name.'"', '"'.$cpfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_epfile']['error']!=4){ //pa_ewfile、pa_epfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_epfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$ewfile_name = $th."_format_E".$ewfile;
					$epfile_name = $th."_format_E".$epfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_ewfile, pa_epfile) VALUES (%s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$ewfile_name.'"', '"'.$epfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4){ //pa_ewfile、pa_cwfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cwfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$ewfile_name = $th."_format_E".$ewfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_ewfile, pa_cwfile) VALUES (%s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$ewfile_name.'"', '"'.$cwfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_ewfile、pa_cpfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$ewfile_name = $th."_format_E".$ewfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_ewfile, pa_cpfile) VALUES (%s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$ewfile_name.'"',  '"'.$cpfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_epfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4){ //pa_epfile、pa_cwfile
		switch ($_FILES['pa_epfile']['error'] || $_FILES['pa_cwfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{					
					$epfile_name = $th."_format_E".$epfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_epfile, pa_cwfile) VALUES (%s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$epfile_name.'"', '"'.$cwfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
			}		
	}else if($_FILES['pa_epfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_epfile、pa_cpfile
		switch ($_FILES['pa_epfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名
				if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$epfile_name = $th."_format_E".$epfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_epfile, pa_cpfile) VALUES (%s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$epfile_name.'"', '"'.$cpfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_cwfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_cwfile、pa_cpfile
		switch ($_FILES['pa_cwfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名 
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名
				if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$cwfile_name = $th."_format_C".$cwfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_cwfile, pa_cpfile) VALUES (%s, %s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$cwfile_name.'"', '"'.$cpfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4){ //pa_ewfile
		switch ($_FILES['pa_ewfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");	
				}else{
					$ewfile_name = $th."_format_E".$ewfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_ewfile) VALUES (%s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$ewfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_cwfile']['error']!=4){ //pa_cwfile
		switch ($_FILES['pa_cwfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");			
			break;
			default:
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名 
				if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$cwfile_name = $th."_format_C".$cwfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_cwfile) VALUES (%s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$cwfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_epfile']['error']!=4){ //pa_epfile
		switch ($_FILES['pa_epfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");			
			break;
			default:
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$epfile_name = $th."_format_E".$epfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_epfile) VALUES (%s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$epfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_cpfile']['error']!=4){ //pa_epfile
		switch ($_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");				
			break;
			default:
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名 
				if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format_update.php?th=$th'>");
				}else{
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlIns = sprintf("INSERT INTO paper_format (pa_content, pa_cpfile) VALUES (%s, %s)",
					'"'.$_POST['pa_content'].'"', '"'.$cpfile_name.'"');
					$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
					if($sqlI){
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']==4 && $_FILES['pa_cwfile']['error']==4 && $_FILES['pa_epfile']['error']==4 && $_FILES['pa_cpfile']['error']==4){ //無上傳新檔 
		$sqlIns = sprintf("INSERT INTO paper_format (pa_content) VALUES (%s)",
		'"'.$_POST['pa_content'].'"');
		$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
		if($sqlI){
			echo "<script>alert('已新增!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
		}	
	}
}


if(isset($_POST['update'])){
	// $pa_no=$_POST['pa_no'];	
	if(empty($_POST['pa_content'])){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4 && $_FILES['pa_epfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_ewfile、pa_epfile、pa_cwfile、pa_cpfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cwfile']['error'] || $_FILES['pa_epfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_ewfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_ewfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cwfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cwfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_epfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_epfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cpfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cpfile'];
						unlink($delfile_name);
					}
					$ewfile_name = $th."_format_E".$ewfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$epfile_name = $th."_format_E".$epfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_ewfile = '%s', pa_cwfile = '%s', pa_epfile = '%s', pa_cpfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $ewfile_name, $cwfile_name, $epfile_name, $cpfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4 && $_FILES['pa_epfile']['error']!=4){ //pa_ewfile、pa_cwfile、pa_epfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cwfile']['error'] || $_FILES['pa_epfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_ewfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_ewfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cwfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cwfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_epfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_epfile'];
						unlink($delfile_name);
					}
					$ewfile_name = $th."_format_E".$ewfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$epfile_name = $th."_format_E".$epfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_ewfile = '%s', pa_cwfile = '%s', pa_epfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $ewfile_name, $cwfile_name, $epfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4 && $_FILES['pa_epfile']['error']!=4){ //pa_ewfile、pa_epfile、pa_cpfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cpfile']['error'] || $_FILES['pa_epfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_ewfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_ewfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cpfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cpfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_epfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_epfile'];
						unlink($delfile_name);
					}
					$ewfile_name = $th."_format_E".$ewfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$epfile_name = $th."_format_E".$epfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_ewfile = '%s', pa_cpfile = '%s', pa_epfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $ewfile_name, $cpfile_name, $epfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_ewfile、pa_cwfile、pa_cpfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cwfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_ewfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_ewfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cwfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cwfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cpfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cpfile'];
						unlink($delfile_name);
					}
					$ewfile_name = $th."_format_E".$ewfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_ewfile = '%s', pa_cwfile = '%s', pa_cpfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $ewfile_name, $cwfile_name, $cpfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_epfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_epfile、pa_cwfile、pa_cpfile
		switch ($_FILES['pa_epfile']['error'] || $_FILES['pa_cwfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名 
				if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_epfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_epfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cwfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cwfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cpfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cpfile'];
						unlink($delfile_name);
					}
					$epfile_name = $th."_format_E".$epfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_epfile = '%s', pa_cwfile = '%s', pa_cpfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $epfile_name, $cwfile_name, $cpfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_epfile']['error']!=4){ //pa_ewfile、pa_epfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_epfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_ewfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_ewfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_epfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_epfile'];
						unlink($delfile_name);
					}
					$ewfile_name = $th."_format_E".$ewfile;
					$epfile_name = $th."_format_E".$epfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_ewfile = '%s', pa_epfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $ewfile_name, $epfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4){ //pa_ewfile、pa_cwfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cwfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_ewfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_ewfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cwfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cwfile'];
						unlink($delfile_name);
					}
					$ewfile_name = $th."_format_E".$ewfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_ewfile = '%s', pa_cwfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $ewfile_name, $cwfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_ewfile、pa_cpfile
		switch ($_FILES['pa_ewfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名 
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_ewfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_ewfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cpfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cpfile'];
						unlink($delfile_name);
					}
					$ewfile_name = $th."_format_E".$ewfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_ewfile = '%s', pa_cpfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $ewfile_name, $cpfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_epfile']['error']!=4 && $_FILES['pa_cwfile']['error']!=4){ //pa_epfile、pa_cwfile
		switch ($_FILES['pa_epfile']['error'] || $_FILES['pa_cwfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名
				if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_epfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_epfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cwfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cwfile'];
						unlink($delfile_name);
					}
					$epfile_name = $th."_format_E".$epfile;
					$cwfile_name = $th."_format_C".$cwfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_epfile = '%s', pa_cwfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $epfile_name, $cwfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_epfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_epfile、pa_cpfile
		switch ($_FILES['pa_epfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名
				if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_epfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_epfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cpfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cpfile'];
						unlink($delfile_name);
					}
					$epfile_name = $th."_format_E".$epfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_epfile = '%s', pa_cpfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $epfile_name, $cpfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_cwfile']['error']!=4 && $_FILES['pa_cpfile']['error']!=4){ //pa_cwfile、pa_cpfile
		switch ($_FILES['pa_cwfile']['error'] || $_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名 
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名
				if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_cwfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cwfile'];
						unlink($delfile_name);
					}
					if($row_data['pa_cpfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cpfile'];
						unlink($delfile_name);
					}
					$cwfile_name = $th."_format_C".$cwfile;
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_cwfile = '%s', pa_cpfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $cwfile_name, $cpfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']!=4){ //pa_ewfile
		switch ($_FILES['pa_ewfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$ewfile = strrchr($_FILES['pa_ewfile']['name'], '.'); //取得副檔名  
				if(!in_array($ewfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");	
				}else{
					if($row_data['pa_ewfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_ewfile'];
						unlink($delfile_name);
					}
					$ewfile_name = $th."_format_E".$ewfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_ewfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $ewfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_ewfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $ewfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_cwfile']['error']!=4){ //pa_cwfile
		switch ($_FILES['pa_cwfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$cwfile = strrchr($_FILES['pa_cwfile']['name'], '.'); //取得副檔名 
				if(!in_array($cwfile, $word_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $word_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");	
				}else{
					if($row_data['pa_cwfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cwfile'];
						unlink($delfile_name);
					}
					$cwfile_name = $th."_format_C".$cwfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_cwfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $cwfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_cwfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cwfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_epfile']['error']!=4){ //pa_epfile
		switch ($_FILES['pa_epfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default: 
				$epfile = strrchr($_FILES['pa_epfile']['name'], '.'); //取得副檔名 
				if(!in_array($epfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_epfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_epfile'];
						unlink($delfile_name);
					}
					$epfile_name = $th."_format_E".$epfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_epfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $epfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_epfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $epfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_cpfile']['error']!=4){ //pa_epfile
		switch ($_FILES['pa_cpfile']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";	
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");				
			break;
			default:
				$cpfile = strrchr($_FILES['pa_cpfile']['name'], '.'); //取得副檔名 
				if(!in_array($cpfile, $pdf_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $pdf_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='../paper_format_update.php?th=$th'>");
				}else{
					if($row_data['pa_cpfile'] != null){
						$delfile_name="paper_format_file/" . $row_data['pa_cpfile'];
						unlink($delfile_name);
					}
					$cpfile_name = $th."_format_C".$cpfile;
					$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s', pa_cpfile = '%s' WHERE pa_no = '1'"
					, $_POST['pa_content'], $cpfile_name, '1');
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['pa_cpfile']['tmp_name'],'paper_format_file/'. iconv("utf-8", "big5", $cpfile_name));//複製檔案
						echo "<script>alert('已修改!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
					}
				}
		}
	}else if($_FILES['pa_ewfile']['error']==4 && $_FILES['pa_cwfile']['error']==4 && $_FILES['pa_epfile']['error']==4 && $_FILES['pa_cpfile']['error']==4){ //無上傳新檔 
		$sqlUpd = sprintf("UPDATE paper_format SET pa_content = '%s' WHERE pa_no = '1'"
		, $_POST['pa_content'], '1');
		$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
			echo "<script>alert('已修改!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='paper_format.php?th=$th'>");
		}	
	}
	
}

require_once('../base_footer.php')?>