<?php require_once('../base_home.php');

if(!isset($_SESSION['re_mail'])){
	echo "<script>alert('請先登入!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=paper.php?th=$th>");
	return;
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
$user = $_SESSION['re_mail'];

$sql_user = "SELECT re_mail, re_lastName, re_firstName, re_wright FROM register where re_mail = '$user'"; // 作者
$result_user = mysqli_query($link,$sql_user);
$user_data = mysqli_fetch_assoc($result_user);

$sql = "SELECT up_no, upsort_num FROM upload ORDER BY up_no DESC"; 
$result = mysqli_query($link,$sql);
$row_data = mysqli_fetch_assoc($result);
$row_sum = mysqli_num_rows($result);

$query_sort = "SELECT * FROM sort"; //分類
$sort_data = mysqli_query($link, $query_sort) or die (mysqli_error());
$sort = mysqli_fetch_assoc($sort_data);

$sql_con = "SELECT page_content FROM page_content where page_name = 'back_paper'"; // 廢話
$result_con = mysqli_query($link,$sql_con);
$row_data_con = mysqli_fetch_assoc($result_con);

$run = 0;
$running = 0;

if(isset($_POST['send']) ){
	if($_POST['up_sort']=="0"){
		echo "<script>alert('請選擇論文分類');</script>";
	}else{
		//算有幾個作者 
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
					exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=paper.php?th=$th>");
				}
			}
		}
		
		//若無錯誤，存入資料庫；若有錯誤，輸出錯誤訊息
		if($run == 0){
			$up_sort='"'.$_POST['up_sort'].'"';
			$query_sort_abb = "SELECT sort_abbreviation FROM sort where sort_name = $up_sort";
			$sort_abb_data = mysqli_query($link, $query_sort_abb) or die (mysqli_error());
			$sort_abb = mysqli_fetch_assoc($sort_abb_data);
			
			$class = $sort_abb['sort_abbreviation'];
			if($row_sum == 0){
				$num = '001';
			}else{
				$value = substr($row_data['upsort_num'], -3) +1;
				$num = str_pad($value,3,'0',STR_PAD_LEFT);
			}
			$sort_num = $class.$num;
		
			$sqlIns = sprintf("INSERT INTO upload (upsort_num, up_paper, up_sort, up_summary, up_keyword, up_user) values (%s, %s, %s, %s, %s, %s)",
			'"'.$sort_num.'"', '"'.$_POST['up_paper'].'"', '"'.$_POST['up_sort'].'"', '"'.$_POST['up_summary'].'"', '"'.$_POST['up_keyword'].'"', '"'.$user.'"');
			$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
			
			$sql_new = "SELECT * FROM upload ORDER BY up_no DESC LIMIT 1";
			$result_new = mysqli_query($link,$sql_new);
			$row_data_new = mysqli_fetch_assoc($result_new);
			
			$up_no = $row_data_new['up_no'];	
			echo $sort_num;
			for($i=0; $i<$running; $i++){
				$sqlInsW = sprintf("INSERT INTO writter (wr_lastName, wr_firstName, wr_email, wr_school, wr_uploadsort, wr_num) values (%s, %s, %s, %s, %s, %s)",
				'"'.$_POST['lastName'][$i].'"', '"'.$_POST['firstName'][$i].'"', '"'.$_POST['email'][$i].'"', '"'.$_POST['school'][$i].'"', '"'.$sort_num.'"', '"'.$_POST['wr_num'][$i].'"');
				$sqlW = mysqli_query($link, $sqlInsW) or die ("MYSQL Error11");
			}
			
			$sqlInsC = sprintf("INSERT INTO camera_ready (ca_upsort, ca_file, ca_auth) values (%s, %s, %s)",'"'.$sort_num.'"', '"'.' '.'"', '"'.' '.'"');
			$sqlC = mysqli_query($link, $sqlInsC) or die ("MYSQL Error");
			
			if($sqlI && $sqlW && $sqlC){
				header("location:sendmail.php?th=$th");
				echo "<script>alert('已新增!');</script>";
			}
		}else{
			echo "<script>alert('作者順序不可相同!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=paper.php?th=$th>");
		}	
	}	
}

?>

<div id=content class="col-sm-9">
<h1>上傳論文</h1>
<p><?php echo $row_data_con['page_content'];?></p>
<div class="topnav">
<?php if ($user_data['re_wright'] == 1){?>
<a class="label label-primary" href="/seminar/login/paper.php?th=<?php echo $th; ?>">上傳論文</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/paper_list.php?th=<?php echo $th; ?>">論文列表</a>&nbsp;&nbsp;
<?php }?>
<a class="label label-primary" href="/seminar/login/user_update.php?th=<?php echo $th; ?>">帳號管理</a>&nbsp;&nbsp;
<a href="/seminar/login/logout.php?th=<?php echo $th; ?>"><?php echo $user;?>&nbsp;登出</a>
</div>
<br>

<form class="form-text-size" method="POST" action="paper.php?th=<?php echo $th; ?>">
  <div class="form-group">
	<font color="red">*</font> 
    論文名稱：<input type="text" name="up_paper" size="30" <?php if(!empty($_POST['up_paper'])){ echo "value='".$_POST['up_paper']."'";}?> required>
  </div>
  <div class="form-group">
	<font color="red">*</font> 
    分類：<select name="up_sort">
    <option selected="selected" value="0">選擇分類</option>
	<?php do{ ?>
		<option value='<?php echo $sort['sort_name']; ?>'><?php echo $sort['sort_name']; ?> </option>
	<?php }while($sort = mysqli_fetch_assoc($sort_data)); ?>
  </select>
  </div>
  <div class="form-group">
  <font color="red">*</font>
  摘要：<textarea class="textarea-size" name="up_summary" id="up_summary" rows="3" cols="30" <?php if(!empty($_POST['up_summary'])){ echo $_POST['up_summary'];}?> required></textarea>
  </div>
  <div class="form-group">
  <font color="red">*</font> 
  關鍵字：<input type="text" name="up_keyword" size="30" <?php if(!empty($_POST['up_keyword'])){ echo "value='".$_POST['up_keyword']."'";}?> required>
  </div>
<h4>作者</h4>
<?php for($j=0;$j<5;$j++){
	if($j==0){?>
		<div class="form-group">
		<font color="red">*</font> 
		順序：<select name="wr_num[]">
		<?php for($k=0; $k<5; $k++){?>
				<option value='<?php echo $k+1; ?>'><?php echo $k+1;?> </option>
		<?php }?>
		</select>
		</div>
		<div class="form-group">
		<font color="red">*</font> 
		姓：<input type="text" name="lastName[]" size="10" value="<?php echo $user_data['re_lastName']?>">
        名：<input type="text" name="firstName[]" size="10" value="<?php echo $user_data['re_firstName']?>">
		</div>
		<div class="form-group">
		<font color="red">*</font> 
		信箱：<input type="text" name="email[]" size="30" value="<?php echo $user_data['re_mail']?>" required>
		</div>
		<div class="form-group">
		<font color="red">*</font> 
		學校/系所：<input type="text" name="school[]" size="15" required>
		</div>
	<?php }else{?>
		<div class="form-group">
		順序：<select name="wr_num[]">
		<?php for($k=0; $k<5; $k++){?>
				<option value='<?php echo $k+1; ?>'><?php echo $k+1;?> </option>
		<?php }?>
		</select>
		</div>
		<div class="form-group">
		姓：<input type="text" name="lastName[]" size="10">
        名：<input type="text" name="firstName[]" size="10">
		</div>
		<div class="form-group"> 
		信箱：<input type="text" name="email[]" size="30">
		</div>
		<div class="form-group">
		學校/系所：<input type="text" name="school[]" size="15">
		</div>
	<?php }
}?>

<input type="submit" class="btn btn-primary" name="send" value="送出" />
</form>


</div>
</body>

<?php require_once('../base_footer.php')?>