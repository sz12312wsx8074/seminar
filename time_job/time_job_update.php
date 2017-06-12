<?php require_once('../seminar_connect.php');

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

date_default_timezone_set('Asia/Taipei');
$todate = date("Y-m-d");
$year_end = $th."-12-31";
$pass = false;

if(isset($_GET["job"])){
   $job = $_GET['job'];
}else{
   $job = $_POST['job'];
}

$query_data = "SELECT * FROM time_job where job_name='$job' ";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);

if(isset($_POST['update']) or isset($_POST['update2'])){
	$job = $_POST['job'];
	if(empty($_POST['job_start_date']) or empty($_POST['job_end_date'])){
		echo "<script>alert('資料不齊全，請重新輸入。');</script>";
	}elseif($_POST['job_start_date'] > $_POST['job_end_date']){
		echo "<script>alert('格式錯誤，開始日期大於截止日期。');</script>";
	}elseif($_POST['job_start_date'] > $year_end){
		echo "<script>alert('開始日期不能超過屆數日期。');</script>";
	}elseif($_POST['job_end_date'] > $year_end){
		echo "<script>alert('截止日期不能超過屆數日期。');</script>";
	}else{
		if(isset($_POST['update'])){ //設定時間
			if($todate > $_POST['job_start_date']){
				echo "<script>alert('開始日期設定需大於今日。');</script>";
			}else{
				$pass = true;
			}
		}else{ //修改時間
			if($todate >= $row_data['job_start_date']){
				if($_POST['job_start_date'] != $row_data['job_start_date']){
					$NO_sart = $row_data['job_name']."時間已經開始，開始時間不能更動。";
					echo "<script>alert('$NO_sart');</script>";
				}else{
					$pass = true;
				}
			}else{
				if($todate > $_POST['job_start_date']){
					echo "<script>alert('開始日期設定需大於今日。');</script>";
				}else{
					$pass = true;
				}
			}
		}
	}
}

if($pass){
	$T_no = $row_data['time_no'];
	if($T_no == 5){
		$query_data2 = "SELECT * FROM time_job where time_no<'$T_no' and (job_start_date is not null) and (job_end_date is not null) ";
		$data2 = mysqli_query($link, $query_data2) or die (mysqli_error());
		$row_data2 = mysqli_fetch_assoc($data2);
		$totalRows_data2 = mysqli_num_rows($data2);
		if($totalRows_data2!=0){
			do{
				if( !($_POST['job_start_date']<=$row_data2['job_start_date'] and $_POST['job_end_date']>=$row_data2['job_end_date']) ){
					$pass = false;
					$ENO = $row_data2['job_name']."超出研討會時間，研討會時間必須涵蓋住其他作業時間。";
					echo "<script>alert('$ENO');</script>";
				}
			}while($row_data2 = mysqli_fetch_assoc($data2) and ($pass == true));
		}
	}else{
		$query_data2 = "SELECT * FROM time_job where time_no!='$T_no' and time_no<4 and (job_start_date is not null) and (job_end_date is not null) order by time_no DESC";
		$data2 = mysqli_query($link, $query_data2) or die (mysqli_error());
		$row_data2 = mysqli_fetch_assoc($data2);
		$totalRows_data2 = mysqli_num_rows($data2);
		
		$query_data3 = "SELECT * FROM time_job where time_no='5' and (job_start_date is not null) and (job_end_date is not null)";
		$data3 = mysqli_query($link, $query_data3) or die (mysqli_error());
		$row_data3 = mysqli_fetch_assoc($data3);
		$totalRows_data3 = mysqli_num_rows($data3);
		
		if($totalRows_data3!=0){
			if( !($_POST['job_start_date']>=$row_data3['job_start_date'] and $_POST['job_end_date']<=$row_data3['job_end_date']) ){
				$pass = false;
				$ENO = $job."時間必須在研討會時間內。";
				echo "<script>alert('$ENO');</script>";
			}
		}
		if($T_no!=4 and $totalRows_data2!=0 and $pass==true){
			do{
				if( $row_data2['time_no']>$T_no ){
					if( $_POST['job_end_date']>=$row_data2['job_start_date'] ){
						$pass = false;
						$ENO = "時間重疊！！\\n".$row_data2['job_name']."開始前，".$job."要先結束。";
						echo "<script>alert('$ENO');</script>";
					}
				}else{
					if( $_POST['job_start_date']<=$row_data2['job_end_date'] ){
						$pass = false;
						$ENO = "時間重疊！！\\n".$row_data2['job_name']."結束後，".$job."才能開始。";
						echo "<script>alert('$ENO');</script>";
					}
				}
			}while($row_data2 = mysqli_fetch_assoc($data2) and ($pass == true));
		}
	}
}

if($pass){
	$sqlUpd = sprintf("UPDATE time_job SET job_start_date='%s', job_end_date='%s' WHERE job_name='%s'",$_POST['job_start_date'], $_POST['job_end_date'], $job );
	$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
	if($sqlU){
		if(isset($_POST['update'])){
			echo "<script>alert('設定完成');</script>";
		}else{
			echo "<script>alert('已修改!!');</script>";
		}
		exit('<script>window.opener.location.reload(); window.opener=null; window.close();</script>');
	}
}

?>


<html>
<title>作業時間</title>
<script language="javascript" type="text/javascript"  src="../My97DatePicker/WdatePicker.js"></script>

<div id=content>    <!--改版改這裡~~~~~~~ -->
<?php if($row_data['job_start_date']==null){
	echo "<h1>設定作業日期</h1>";
}else{
	echo "<h1>修改作業日期</h1>";
} ?>

<hr>

<form method="POST" action="time_job_update.php?th=<?php echo $th; ?>" enctype="multipart/form-data">

<input type="hidden" name = "job" value = "<?php echo $row_data['job_name'];?>" />
<label><font size="4"><?php echo $row_data['job_name'] ?>：</font></label>
<font color="red">*</font>
<input class="Wdate" type="text" name="job_start_date" id="job_start_date" size="12" onClick="WdatePicker()" value="<?php echo $row_data['job_start_date']; ?>" required /> ~ 
<font color="red">*</font>
<input class="Wdate" type="text" name="job_end_date" id="job_end_date" size="12" onClick="WdatePicker()" value="<?php echo $row_data['job_end_date']; ?>" required />
<br><br>

<?php if($row_data['job_start_date']==null){ ?>
	<input type="submit" name="update" id="update" value="送出" />
<?php }else{ ?>
	<input type="submit" name="update2" id="update2" value="修改" />
<?php } ?>


</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>