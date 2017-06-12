<?php require_once('../base_home.php');

$user = $_SESSION['re_mail'];

$sql_time = "SELECT * FROM time_job where time_no = 3"; // 期間
$result_time = mysqli_query($link,$sql_time);
$time_data = mysqli_fetch_assoc($result_time);

$current_time = date("Y-m-d");
if ($current_time > $time_data['job_end_date']){
    echo "<script>alert('以超過論文截稿時間!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=login.php?th=$th>");
	return;
}

if($_GET){
	$ca_upsort=$_GET["ca_upsort"];
}

$sql_user = "SELECT re_mail, re_lastName, re_firstName FROM register WHERE re_mail = '$user'";
$result_user = mysqli_query($link,$sql_user);
$user_data = mysqli_fetch_assoc($result_user);

$sql_p = "SELECT up_user FROM upload WHERE upsort_num = '$ca_upsort' ";
$result_p = mysqli_query($link,$sql_p);
$user_p = mysqli_fetch_assoc($result_p);


if($user_p['up_user'] != $user){
	echo "<script>alert('沒有此權限!!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=camera_ready_list.php?th=$th>");
	return;
}

$query_data = "SELECT * FROM camera_ready WHERE ca_upsort = '$ca_upsort'";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);

$cafile_ext = array('.doc', '.docx');
$arr_ext = array('.doc', '.docx', '.pdf', 'PDF', '.png', '.PNG', '.jpg', 'JPG', '.jpeg', 'JPEG');

$query_authorize = "SELECT * FROM accepted_list";
$authorize = mysqli_query($link,$query_authorize) or die(mysqli_error());
$authorize_data = mysqli_fetch_assoc($authorize);

if(isset($_POST['update'])){
	if($_FILES['ca_auth']['error']==4){ //只有ca_file
		switch ($_FILES['ca_file']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失    
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";		
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
			break;
			default:			
				$cafile = strrchr($_FILES['ca_file']['name'], '.'); //取得副檔名 
				$cafile_name = $row_data['ca_upsort'].$cafile;
				if(!in_array($cafile, $cafile_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $cafile_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				}else{
					if($row_data['ca_file'] != null){
						$delfile_name="camera_ready/".$th.'/'.$row_data['ca_file'];
						unlink($delfile_name);
					}
					$sqlUpd = sprintf("UPDATE camera_ready SET ca_file = '%s' WHERE ca_upsort = '$ca_upsort'", $cafile_name);
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['ca_file']['tmp_name'], 'camera_ready/'.$th.'/'.iconv("utf-8", "big5", $cafile_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=camera_ready_list.php?th=$th>");
					}
				}
		}
	}else if($_FILES['ca_file']['error']==4){ //只有ca_auth
		switch ($_FILES['ca_auth']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失    
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";		
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				// $this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
			break;
			default:				
				$caauth = strrchr($_FILES['ca_auth']['name'], '.'); //取得副檔名 
				$caauth_name = $row_data['ca_upsort'].$caauth;
				if(!in_array($caauth, $arr_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $arr_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				}else{
					if($row_data['ca_auth'] != null){
						$delfile_name="camera_ready/".$th.'/'.$row_data['ca_auth'];
						unlink($delfile_name);
					}
					$sqlUpd = sprintf("UPDATE camera_ready SET ca_auth = '%s' WHERE ca_upsort = '$ca_upsort'", $caauth_name);
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['ca_auth']['tmp_name'], 'camera_ready/'.$th.'/'. iconv("utf-8", "big5", $caauth_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=camera_ready_list.php?th=$th>");
					}
				}
		}
	}else if($_FILES['ca_file']['error']!=4 && $_FILES['ca_auth']['error']!=4){ //ca_file、ca_auth都有
		switch($_FILES['ca_file']['error'] || $_FILES['ca_auth']['error']){  
			case 1:  
				echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
				// $this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
			break;  
			case 2:  
				echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
				// $this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
			break;  
			case 3:
				echo "<script>alert('檔案僅部分被上傳');</script>";
				// $this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
			break;  
			case 4:
				echo "<script>alert('沒有找到要上傳的檔案');</script>";
				// $this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
			break;  
			case 5:  
				echo "<script>alert('伺服器臨時檔案遺失');</script>";
				// $this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失    
			break;  
			case 6:  
				echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
				// $this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
			break;  
			case 7:  
				echo "<script>alert('無法寫入硬碟');</script>";
				// $this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
			break;  
			case 8: 
				echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";			
				// $this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
			break;
			default:
				$cafile = strrchr($_FILES['ca_file']['name'], '.'); //取得副檔名 
				$cafile_name = $row_data['ca_upsort'].$cafile;
				$caauth = strrchr($_FILES['ca_auth']['name'], '.'); //取得副檔名 
				$caauth_name = $row_data['ca_upsort'].$caauth;
				if(!in_array($cafile, $cafile_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $cafile_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				}else if(!in_array($caauth, $arr_ext)){
					$file_extension='檔案上傳失敗，只允許 '.implode('、', $arr_ext).' 副檔名';
					echo "<script>alert('$file_extension');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='camera_ready_insert.php?th=$th&ca_upsort=$ca_upsort'>");
				}else{
					if($row_data['ca_file'] != null){
						$delfile_name="camera_ready/".$th.'/'.$row_data['ca_file'];
						unlink($delfile_name);
					}
					if($row_data['ca_auth'] != null){
						$delfile_name="camera_ready/".$th.'/'.$row_data['ca_auth'];
						unlink($delfile_name);
					}
					$sqlUpd = sprintf("UPDATE camera_ready SET ca_file = '%s', ca_auth = '%s' WHERE ca_upsort = '$ca_upsort'", $cafile_name, $caauth_name);
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					if($sqlU){
						move_uploaded_file($_FILES['ca_file']['tmp_name'], 'camera_ready/'.$th.'/'.iconv("utf-8", "big5", $cafile_name));//複製檔案
						move_uploaded_file($_FILES['ca_auth']['tmp_name'], 'camera_ready/'.$th.'/'.iconv("utf-8", "big5", $caauth_name));//複製檔案
						echo "<script>alert('已新增!!');</script>";
						exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=camera_ready_list.php?th=$th>");
					}
				}
		}
	}
}

?>

<div id=content class="col-sm-9">

<h1>定稿上傳</h1>
<div class="topnav">
<a class="label label-primary" href="/seminar/login/camera_ready_list.php?th=<?php echo $th; ?>">論文列表</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/user_update.php?th=<?php echo $th; ?>">帳號管理</a>&nbsp;&nbsp;
<!--<a class="label label-primary" href="/seminar/login/reset_pwd.php?th=<?php// echo $th; ?>&id=<?php// echo base64_encode($user); ?>">修改密碼</a>&nbsp;&nbsp;-->
<a href="/seminar/login/logout.php?th=<?php echo $th; ?>"><?php echo $user;?>&nbsp;登出</a>
</div>
<br>

<form class="form-horizontal form-text-size" method="POST" action="camera_ready_insert.php?th=<?php echo $th; ?>&ca_upsort=<?php echo $ca_upsort ?>" enctype="multipart/form-data" >

<input type="hidden" name="ca_upsort" value="<?php echo $ca_upsort ;?>" />

<font color="red">※論文檔案僅能上傳word檔※<br>
  ※授權書可上傳pdf、png、jpeg檔※</font>
<br><br>

授權書下載：<a href="../login/camera_ready/downloadfile.php?ca_authorize=<?php echo $authorize_data['ca_authorize'];?>"><?php echo $authorize_data['ca_authorize'];?></a>
<br><br>  
  
原論文檔案：<a href="../login/camera_ready/<?php echo $th.'/'.$row_data['ca_file'];?>"><?php echo $row_data['ca_file'];?></a>
<br><br>

論文檔案：<input type="file" id="ca_file" name="ca_file" required/>
<br>


原授權書：<a Target="_blank" href="../login/camera_ready/<?php echo $th.'/'.$row_data['ca_auth'];?>"><?php echo $row_data['ca_auth'];?></a>
<br><br>

授權書上傳：<input type="file" id="ca_auth" name="ca_auth" required/>
<br>

<input class="btn btn-primary" type="submit" id="update" name="update" value="確認上傳" />

</form>

</div>

<?php require_once('../base_footer.php')?>