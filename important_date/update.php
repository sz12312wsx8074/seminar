<?php require_once('../base_home.php');

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if($button_on==false){
	echo "<script>alert('研討會時間已過!');</script>";
	exit ("<script>location.href='/seminar/important_date/important_date.php?th=$th'</script>");
} 

if($_GET){
	$date_no=$_GET["date_no"];
}

$query_data = "SELECT * FROM important_date where date_no='$date_no'";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);

$query_time = "SELECT * FROM time_job where time_no=5";
$time = mysqli_query($link,$query_time) or die(mysqli_error());
$row_time = mysqli_fetch_assoc($time);
$start = $row_time['job_start_date'];
$end = $row_time['job_end_date'];

if(isset($_POST['submit'])){
	if(empty($_POST['date_content']) and $row_data['where_from']!=3){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
	}elseif($_POST['date_date']<$start or $_POST['date_date']>$end){
		echo "<script>alert('不在研討會時間範圍內');</script>";
	}
	else{
		$insert = false;
		if($row_data['where_from']!=3){
			$sqlUpd = sprintf("UPDATE important_date set date_content='%s',date_date='%s' where date_no='$date_no' "
			,$_POST['date_content'],$_POST['date_date']);
		}else{
			if($row_data['date_date']==null){
				$insert = true;
			}
			$sqlUpd = sprintf("UPDATE important_date set date_date='%s' where date_no='$date_no' ",$_POST['date_date']);
		}
		$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
				if($insert){
					echo "<script>alert('已設定!!');</script>";  //改版改這裡~~~~~~~~"已修改"改成彈跳視窗
				}else{
					echo "<script>alert('已修改!!');</script>";
				}
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=important_date.php?th=$th>");  //改版改這裡~~~~~~~~ 過0.1秒後回原本頁面 那個content=0.1就等於秒數
			}
	}
}


?>


<html>
<div id=content class="col-sm-9">
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>  <!--文字編輯器套件-->
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->

<form class="form-horizontal form-text-size"  method="POST" action="update.php?th=<?php echo $th ?>&date_no=<?php echo $date_no ?>">

<body>

<?php if($row_data['where_from']!=3){ ?>
	<h1>修改重要日期</h1>
<?php }elseif($row_data['date_date']==null){ ?>
	<h1>設定研討會會議日期</h1>
<?php }else{ ?>
	<h1>修改研討會會議日期</h1>
<?php } ?>
<hr>

<div class="form-group">
<?php
	if($row_data['where_from']!=3){ ?>
		<label class="control-label col-sm-2"><font color="red">*</font>日期：</label><?php
	}else{ ?>
		<label class="control-label col-sm-2"><font color="red">*</font>研討會會議日期：</label><?php
	} ?>
	<div class="col-sm-10">
		<input type="text" name="date_date" id="date_date" onClick="WdatePicker()"
		value="<?php echo $row_data['date_date']; ?>" required>
	</div>
</div>

<?php
if($row_data['where_from']!=3){ ?>
<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>項目：</label>
	<div class="col-sm-10">
		<textarea id="date_content" name="date_content" ><?php echo $row_data['date_content']; ?></textarea>
	</div>
</div><?php
} ?>
<div class="form-group">
	<div class="col-sm-10 col-sm-offset-2">
		<input type="submit" class="btn btn-primary" name="submit" value="送出"/>
	</div>
</div>

</body>
</form>
</div>
</html>










<?php require_once('../base_footer.php')?>