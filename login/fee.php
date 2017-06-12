<?php require_once('../base_home.php');

$user = $_SESSION['re_mail'];

$sql = "SELECT * FROM contribute_fee";
$result = mysqli_query($link,$sql);
$row_data = mysqli_fetch_assoc($result);

$query_receipt = "SELECT * FROM register WHERE re_mail = '$user'";
$receipt = mysqli_query($link,$query_receipt);
$receipt_data = mysqli_fetch_assoc($receipt);

$arr_ext = array('.png', '.PNG', '.jpg', 'JPG', '.jpeg', 'JPEG');

if(isset($_POST['confirm'])){ 
	switch ($_FILES['fee']['error']){  
		case 1:  
			echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='fee.php?th=$th'>");
			// $this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE  
		break;  
		case 2:  
			echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='fee.php?th=$th'>");
			// $this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE  
		break;  
		case 3:
			echo "<script>alert('檔案僅部分被上傳');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='fee.php?th=$th'>");
			// $this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL   
		break;  
		case 4:
			echo "<script>alert('沒有找到要上傳的檔案');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='fee.php?th=$th'>");
			// $this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE  
		break;  
		case 5:  
			echo "<script>alert('伺服器臨時檔案遺失');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='fee.php?th=$th'>");
			// $this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失    
		break;  
		case 6:  
			echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='fee.php?th=$th'>");
			// $this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR  
		break;  
		case 7:  
			echo "<script>alert('無法寫入硬碟');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='fee.php?th=$th'>");
			// $this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
		break;  
		case 8: 
			echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";		
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='fee.php?th=$th'>");
			// $this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION  
		break;
		default:
			if($receipt_data['receipt'] != null){
					$delfile_name="receipt/" . $receipt_data['receipt'];
					unlink($delfile_name);
			}
			
			$fee = strrchr($_FILES['fee']['name'], '.'); //取得副檔名 
			$fee_name = $receipt_data['re_phone'].$fee;
			if(!in_array($fee, $arr_ext)){
				$file_extension='檔案上傳失敗，只允許 '.implode('、', $arr_ext).' 副檔名';
				echo "<script>alert('$file_extension');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='fee.php?th=$th'>");
			}else{
				$sqlUpd = sprintf("UPDATE register SET receipt = '%s' WHERE re_mail = '$user'", $fee_name);
				$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
				if($sqlU){
					move_uploaded_file($_FILES['fee']['tmp_name'], 'receipt/'. iconv("utf-8", "big5", $fee_name));//複製檔案
					echo "<script>alert('已新增!!');</script>";
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='fee.php?th=$th'>");
				}
			}
	}
}

?>


<div id=content class="col-sm-9">
<h1>費用資訊</h1>
<div class="topnav">
<a class="label label-Danger" href="/seminar/login/invite.php?th=<?php echo $th?>">報名研討會</a>&nbsp;&nbsp;
<a class="label label-Danger" href="/seminar/login/party.php?th=<?php echo $th?>">報名宴會</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/user_update.php?th=<?php echo $th?>">帳號管理</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/fee.php?th=<?php echo $th?>">繳費資訊</a>&nbsp;&nbsp;  
<a href="/seminar/login/logout.php?th=<?php echo $th?>"><?php echo $user;?>&nbsp;登出</a>
</div>
<br>
  
<table class="table table-striped">
  <thead>
    <tr>
      <th>項目</th>
      <th>費用</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">論文費用</th>
      <td><?php echo 'NT$'.$row_data['first_paper']?>&nbsp;/篇</td>
    </tr>
    <?php if ($row_data['second_paper'] != "0"){?>
    <tr>
      <th scope="row">第二篇以上論文費用</th>
      <td><?php echo 'NT$'.$row_data['second_paper']?>&nbsp;/篇</td>
    </tr>
    <?php }?>
    <tr>
      <th scope="row">研討會參加費用</th>
      <td><?php echo 'NT$'.$row_data['seminar_fee']?>&nbsp;/人</td>
    </tr>
    <tr>
      <th scope="row">晚宴參加費用</th>
      <td><?php echo 'NT$'.$row_data['banquet_fee']?>&nbsp;/人</td>
    </tr>
  </tbody>
</table>

<form class="form-text-size" method="POST" action="fee.php?th=<?php echo $th?>" enctype="multipart/form-data">
  <fieldset class="form-group">
    <font color="red">※上傳收據僅支援PNG、JPG、JPEG格式※</font><br>
	<?php if($receipt_data['receipt'] != NULL){?>
		已上傳的收據：<br>
		<img src="../login/receipt/<?php echo $receipt_data['receipt']; ?>" width="30%" height="30%" /></text>
	<?php }?>
	<br>
    上傳收據：<input type="file" name="fee" />
  </fieldset>
  <input type="submit" class="btn btn-primary" name="confirm" value="送出" />
</form>
</div>
</body>


<?php require_once('../base_footer.php')?>