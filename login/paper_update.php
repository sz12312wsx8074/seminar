<?php require_once('../base_home.php');

$user = $_SESSION['re_mail'];

if($_GET){
	$up_no=$_GET["up_no"];
}

$sql_time = "SELECT * FROM time_job where time_no = 1"; // 期間
$result_time = mysqli_query($link,$sql_time);
$time_data = mysqli_fetch_assoc($result_time);

$current_time = date("Y-m-d");
if ($current_time > $time_data['job_end_date']){
    echo "<script>alert('以超過論文截稿時間!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=login.php?th=$th>");
	return;
}

$sql_user = "SELECT re_wright FROM register where re_mail = '$user'";
$result_user = mysqli_query($link,$sql_user);
$user_data = mysqli_fetch_assoc($result_user);

$sql_p = "SELECT up_user FROM upload WHERE up_no = $up_no ";
$result_p = mysqli_query($link,$sql_p);
$user_p = mysqli_fetch_assoc($result_p);

if($user_p['up_user'] != $user){
	echo "<script>alert('沒有此權限!!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=paper_list.php?th=$th>");
	return;
}

$sql = "SELECT * FROM upload WHERE up_no = $up_no ";
$result = mysqli_query($link,$sql) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($result);

$upsort_num = $row_data['upsort_num'];

if(isset($_POST['update'])){
	if($_FILES['up_word']['error']==4 && $_FILES['up_pdf']['error']==4){ //pdf、word都沒有
      $writer = insert_writer($up_no, $upsort_num, $link, true, $th);
      
	}else if($_FILES['up_word']['error']==4){ //只有pdf，沒有word
      $writer = insert_writer($up_no, $upsort_num, $link, false, $th);
      if ($writer == 0){
        $result = upload_files($_FILES['up_pdf'], array('.pdf'), $up_no, $writer, $link, $th);
        if ($result){
          echo "<script>alert('已修改!!');</script>";
          header("location:update_sendmail.php?th=$th&up_no=$up_no");
        }
      }
	}else if($_FILES['up_pdf']['error']==4){ //只有word，沒有pdf
      $writer = insert_writer($up_no, $upsort_num, $link, false, $th);
      if ($writer == 0){
        $result = upload_files($_FILES['up_word'], array('.doc', '.docx'), $up_no, $writer, $link, $th);
        if ($result){
          echo "<script>alert('已修改!!');</script>";
          header("location:update_sendmail.php?th=$th&up_no=$up_no");
        }
      }
	}else if($_FILES['up_pdf']['error']!=4 && $_FILES['up_word']['error']!=4){ //pdf、word都有
      $file_ext = array('.pdf', '.doc', '.docx'); // pdf and foc
      $writer = insert_writer($up_no, $upsort_num, $link, false, $th);
      if ($writer == 0){
        $result = upload_files($_FILES['up_pdf'], $file_ext, $up_no, $writer, $link, $th);
        $result2 = upload_files($_FILES['up_word'], $file_ext, $up_no, $writer, $link, $th);
        if ($result && $result2){
          echo "<script>alert('已修改!!');</script>";
          header("location:update_sendmail.php?th=$th&up_no=$up_no");
        }
      }
	}
}

function insert_writer($up_no, $upsort_num, $link, $nothing, $th){
  $run = 0;
  $running = 0;
  // 算有幾個作者 
  for($i=0; $i<count($_POST['lastName']); $i++){
    if($_POST['lastName'][$i] != NULL){
        $running = $running + 1;
    }
  }
   //判斷是否有重複的作者順序或順序錯誤
  for($i=0; $i<$running; $i++){
    $num = $_POST['wr_num'][$i];
    for($j=0; $j<$running; $j++){
      if($j==$i){
        $j = $j + 1;
      }else if($_POST['wr_num'][$j] == $num){
        $run = $run + 1;
      }
	  if($_POST['wr_num'][$j] > $running){
        echo "<script>alert('請輸入正確的作者順序!!');</script>";
        exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=paper_update.php?th=$th&up_no=$up_no'>");
      }
    }
  }
  $sqlDel = sprintf("DELETE FROM writter WHERE wr_uploadsort = '$upsort_num'");
  $sqlD = mysqli_query($link, $sqlDel) or die ("MYSQL Error");

  if($run == 0){
    $sqlUpd = sprintf("UPDATE upload SET up_paper='%s', up_summary='%s', up_keyword='%s' WHERE up_no = $up_no ",
    $_POST['up_paper'], $_POST['up_summary'], $_POST['up_keyword']);
    $sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
    $sqlDel = sprintf("DELETE FROM writter WHERE wr_uploadsort = '$upsort_num'");
    $sqlD = mysqli_query($link, $sqlDel) or die ("MYSQL Error");

    for($i=0; $i<$running; $i++){
      $sqlInsW = sprintf("INSERT INTO writter (wr_lastName, wr_firstName, wr_email, wr_school, wr_uploadsort, wr_num) values (%s, %s, %s, %s, %s, %s)",
      '"'.$_POST['lastName'][$i].'"', '"'.$_POST['firstName'][$i].'"', '"'.$_POST['email'][$i].'"', '"'.$_POST['school'][$i].'"', '"'.$upsort_num.'"', '"'.$_POST['wr_num'][$i].'"');
      $sqlW = mysqli_query($link, $sqlInsW) or die ("MYSQL Error");
    }
    if ($nothing == true){
      if($sqlU && $sqlW){
          echo "<script>alert('已修改!!');</script>";
          header("location:update_sendmail.php?th=$th&up_no=$up_no");
      }
    }
  }else{
    echo "<script>alert('作者順序不可相同!!');</script>";
    exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=paper_update.php?th=$th&up_no=$up_no'>");
  }
  return $run;
}

function upload_files($file, $file_ext, $up_no, $writer, $link, $th){
  $sql = "SELECT * FROM upload WHERE up_no = $up_no ";
  $result = mysqli_query($link,$sql) or die(mysqli_error());
  $row_data = mysqli_fetch_assoc($result);
  switch($file['error']){  
    case 1:  
        echo "<script>alert('檔案大小超出了伺服器上傳限制');</script>";
        //$this ->setError( "The file is too large (server)." );    //檔案大小超出了伺服器上傳限制 UPLOAD_ERR_INI_SIZE
        return false;
    break;  
    case 2:  
        echo "<script>alert('要上傳的檔案大小超出瀏覽器限制');</script>";
        //$this ->setError( "The file is too large (form)." );  // 要上傳的檔案大小超出瀏覽器限制 UPLOAD_ERR_FORM_SIZE
        return false;
    break;  
    case 3:
        echo "<script>alert('檔案僅部分被上傳');</script>";
        //$this ->setError( "The file was only partially uploaded." ); //檔案僅部分被上傳 UPLOAD_ERR_PARTIAL
        return false;
    break;  
    case 4:
        echo "<script>alert('沒有找到要上傳的檔案');</script>";
        //$this ->setError( "No file was uploaded." );  //沒有找到要上傳的檔案 UPLOAD_ERR_NO_FILE
        return false;
    break;  
    case 5:  
        echo "<script>alert('伺服器臨時檔案遺失');</script>";
        //$this ->setError( "The servers temporary folder is missing." );  //伺服器臨時檔案遺失
        return false;
    break;  
    case 6:  
        echo "<script>alert('檔案寫入到站存資料夾錯誤');</script>";
        //$this ->setError( "Failed to write to the temporary folder." );  //檔案寫入到站存資料夾錯誤 UPLOAD_ERR_NO_TMP_DIR 
        return false;
    break;  
    case 7:  
        echo "<script>alert('無法寫入硬碟');</script>";
        //$this ->setError( "Failed to write file to disk." );   //無法寫入硬碟 UPLOAD_ERR_CANT_WRITE  
        return false;
    break;  
    case 8: 
        echo "<script>alert('UPLOAD_ERR_EXTENSION');</script>";			
        //$this ->setError( "File upload stopped by extension." );   //UPLOAD_ERR_EXTENSION
        return false;
    break;
    default:

    $doc = strrchr($file['name'], '.'); //取得副檔名
    $doc_name = $row_data['upsort_num'].$doc;
    if(!in_array($doc, $file_ext)){
        $file_extension='檔案上傳失敗，只允許 '.implode('、', $file_ext).' 副檔名';
        echo "<script>alert('$file_extension');</script>";
        return false;
    }else{
      if ($doc == '.pdf'){
        $up_col = 'up_pdf';
      }else{
        $up_col = 'up_word';
      }
      $sqlUpd = sprintf("UPDATE upload SET up_paper='%s', up_summary='%s', up_keyword='%s', 
      up_status = '已上傳', $up_col='%s', up_upload='1' WHERE up_no = $up_no ",
      $_POST['up_paper'], $_POST['up_summary'], $_POST['up_keyword'], $doc_name);
      $sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
      
      if($sqlU){
		  // echo $AA='upload/'.$th.'/'.iconv("utf-8", "big5", $doc_name);
        move_uploaded_file($file['tmp_name'],'upload/'.$th.'/'.iconv("utf-8", "big5", $doc_name)) ;//複製檔案
        return true;
        
      }
    }
  } 
}//function


$sql_writter = "SELECT wr_lastName, wr_firstName, wr_email, wr_school, wr_uploadsort, wr_num FROM writter, upload 
WHERE writter.wr_uploadsort = upload.upsort_num and up_no = $up_no ORDER BY wr_num";
$result_writter = mysqli_query($link,$sql_writter);
$writter_data = mysqli_fetch_assoc($result_writter);
$writter_sum = mysqli_num_rows($result_writter);

?>

<div id=content class="col-sm-9">

<h1>修改論文</h1>
<div class="topnav">
<p><?php if ($user_data['re_wright'] == 1){?></p>
<a class="label label-primary" href="/seminar/login/paper.php?th=<?php echo $th; ?>">上傳論文</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/paper_list.php?th=<?php echo $th; ?>">論文列表</a>&nbsp;&nbsp;
<?php }?>
<a class="label label-primary" href="/seminar/login/user_update.php?th=<?php echo $th; ?>">帳號管理</a>&nbsp;&nbsp;
<a href="/seminar/login/logout.php?th=<?php echo $th; ?>"><?php echo $user;?>&nbsp;登出</a>
</div>
<br>

<form class="form-text-size" method="POST" action="paper_update.php?th=<?php echo $th; ?>&up_no=<?php echo $up_no; ?>" enctype="multipart/form-data">
  <input type="hidden" name="up_no" value="<?php echo $up_no ;?>" />
  <div class="form-group">
	<font color="red">*</font> 
    論文名稱：<input type="text" name="up_paper" size="30" value="<?php echo $row_data['up_paper']?>" required>
  </div>
  <div class="form-group">
	<font color="red">*</font> 
    分類：<?php echo $row_data['up_sort']?>
  </div>
  <div class="form-group">
	<font color="red">*</font> 
    摘要：<textarea class="textarea-size" name="up_summary" rows="8" cols="30" required><?php echo $row_data['up_summary']?></textarea>
  </div>
  <div class="form-group">
	<font color="red">*</font> 
    關鍵字：<input type="text" name="up_keyword" size="30" value="<?php echo $row_data['up_keyword']?>" required>
  </div>
<?php if($writter_sum == 5){
	$i = 0;
	do{
		if($i == 0){?>
		  <h4>作者</h4>
		  <div class="form-group">
		  <font color="red">*</font> 
		  順序：<select name="wr_num[]" >
			<?php for($k=0; $k<5; $k++){?>
				<option value='<?php echo $k+1; ?>' <?php if($writter_data['wr_num']==$k+1){?> selected="selected"<?php } ?> >
				<?php echo $k+1;?> </option>
			<?php }?>			
			</select>
		  </div>
		  <div class="form-group">
		  <font color="red">*</font> 
		   姓：<input type="text" name="lastName[]" size="10" value="<?php echo $writter_data['wr_lastName']?>">
		   名：<input type="text" name="firstName[]" size="10" value="<?php echo $writter_data['wr_firstName']?>">
		  </div>
		  <div class="form-group">
		  <font color="red">*</font> 
			信箱：<input type="email" name="email[]" size="30" value="<?php echo $writter_data['wr_email']?>">
		  </div>
		  <div class="form-group">
		  <font color="red">*</font> 
		   學校/系所：<input type="text" name="school[]" size="15" value="<?php echo $writter_data['wr_school']?>">
		  </div>
		<?php $i =$i + 1;
		}else{?>
		  <h4>作者</h4>
		  <div class="form-group">
		  <!--<font color="red">*</font> -->
		  順序：<select name="wr_num[]" >
			<?php for($k=0; $k<5; $k++){?>
				<option value='<?php echo $k+1; ?>' <?php if($writter_data['wr_num']==$k+1){?> selected="selected"<?php } ?> >
				<?php echo $k+1;?> </option>
			<?php }?>			
			</select>
		  </div>
		  <div class="form-group">
		  <!--<font color="red">*</font> -->
		   姓：<input type="text" name="lastName[]" size="10" value="<?php echo $writter_data['wr_lastName']?>">
		   名：<input type="text" name="firstName[]" size="10" value="<?php echo $writter_data['wr_firstName']?>">
		  </div>
		  <div class="form-group">
		  <!--<font color="red">*</font> --> 
			信箱：<input type="email" name="email[]" size="30" value="<?php echo $writter_data['wr_email']?>">
		  </div>
		  <div class="form-group">
		  <!--<font color="red">*</font> -->
		   學校/系所：<input type="text" name="school[]" size="15" value="<?php echo $writter_data['wr_school']?>">
		  </div>
		<?php $i =$i + 1;
		}
	}while($writter_data= mysqli_fetch_assoc($result_writter));
}else{
	do{?>
      <h4>作者</h4>
      <div class="form-group">
      <font color="red">*</font> 
      順序：<select name="wr_num[]" >		
        <?php for($k=0; $k<5; $k++){?>
            <option value='<?php echo $k+1; ?>' <?php if($writter_data['wr_num']==$k+1){?> selected="selected"<?php } ?> >
            <?php echo $k+1;?> </option>
        <?php }?>			
        </select>
      </div>
      <div class="form-group">
      <font color="red">*</font> 
       姓：<input type="text" name="lastName[]" size="10" value="<?php echo $writter_data['wr_lastName']?>">
       名：<input type="text" name="firstName[]" size="10" value="<?php echo $writter_data['wr_firstName']?>">
      </div>
      <div class="form-group">
      <font color="red">*</font> 
        信箱：<input type="email" name="email[]" size="30" value="<?php echo $writter_data['wr_email']?>">
      </div>
      <div class="form-group">
      <font color="red">*</font> 
       學校/系所：<input type="text" name="school[]" size="15" value="<?php echo $writter_data['wr_school']?>">
      </div>
<?php }while($writter_data= mysqli_fetch_assoc($result_writter));
  for($j=0;$j<(5-$writter_sum);$j++){?>
      <h4>作者</h4>
      <div class="form-group">
      <!--<font color="red">*</font> -->
      順序：<select name="wr_num[]" >
			<?php for($k=0; $k<5; $k++){?>
				<option value='<?php echo $k+1; ?>'><?php echo $k+1;?> </option>
			<?php }?>			
		</select>
      </div>
      <div class="form-group">
      <!--<font color="red">*</font> --> 
        姓：<input type="text" id="lastName" name="lastName[]" size="10">
        名：<input type="text" id="firstName" name="firstName[]" size="10">
      </div>
      <div class="form-group">
      <!--<font color="red">*</font> -->
        信箱：<input type="email" id="email" name="email[]" size="30">
      </div>
      <div class="form-group">
      <!--<font color="red">*</font> -->
       學校/系所：<input type="text" id="school" name="school[]" size="15">
      </div>
  <?php }?>
<?php } 
  
if($row_data['up_pdf'] != NULL){ ?>
	PDF檔案：<a Target="_blank" href="../login/upload/<?php echo $th.'/'.$row_data['up_pdf'];?>"><?php echo $row_data['up_pdf'];?></a>
	<input type="button" id="delete" name="delete" value="刪除" onclick="delete_Case(true, '<?php echo $row_data['up_pdf']; ?>')"/>
<?php }else{ ?>
    PDF檔案：
<?php } ?>
<br><br>

<?php if($row_data['up_word'] != NULL){?>
	Word檔案：<a href="../login/upload/<?php echo $th.'/'.$row_data['up_word'];?>"><?php echo $row_data['up_word'];?></a>
	<input type="button" id="delete" name="delete" value="刪除" onclick="delete_Case(true, '<?php echo $row_data['up_word']; ?>')" />
<?php }else{ ?>
	Word檔案：
<?php } ?>
<br><br>
上傳pdf檔案：<input type="file" id="up_pdf" name="up_pdf"/>
<br><br>
上傳word檔案：<input type="file" id="up_word" name="up_word"/>
<br><br>
<input type="submit" class="btn btn-primary" id="update" name="update" value="修改"/>
</form> 
<br>

</div> 
<script>
function delete_Case(single, file) {
	var dele = confirm("確定要刪除這個檔案嗎？");
	if (dele == true){
		if (single){
			location.href='../login/upload/deletefile.php?th=<?php echo $th;?>&file='+file;
		}else{
			document.getElementById('dele').type = 'submit';
		}
	}
}
</script>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>