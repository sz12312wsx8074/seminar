<?php require_once('../base_home.php');

$user = $_SESSION['re_mail'];

$sql_time = "SELECT * FROM time_job where time_no = 1"; // 期間
$result_time = mysqli_query($link,$sql_time);
$time_data = mysqli_fetch_assoc($result_time);

$current_time = date("Y-m-d");
if ($current_time > $time_data['job_end_date']){
    echo "<script>alert('以超過論文截稿時間!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=login.php?th=$th>");
	return;
}

$sql_user = "SELECT re_mail, re_lastName, re_firstName FROM register where re_mail = '$user'";
$result_user = mysqli_query($link,$sql_user);
$user_data = mysqli_fetch_assoc($result_user);

if($_GET){
	$up_no=$_GET["up_no"];
}

?>

<div id=content class="col-sm-9">
<h1>上傳論文</h1>
<div class="topnav">
<a class="label label-primary" href="/seminar/login/paper.php?th=<?php echo $th; ?>">上傳論文</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/paper_list.php?th=<?php echo $th; ?>">論文列表</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/user_update.php?th=<?php echo $th; ?>">帳號管理</a>&nbsp;&nbsp;
<a href="/seminar/login/logout.php?th=<?php echo $th; ?>"><?php echo $user;?>&nbsp;登出</a>
</div>

<br>

<form method="POST" action="upload_run.php?th=<?php echo $th; ?>" enctype="multipart/form-data" >

<input type="hidden" name="up_no" value="<?php echo $up_no ;?>">

選擇pdf檔案：<input type="file" id="up_pdf" name="up_pdf" >
<br><br>
選擇word檔案：<input type="file" id="up_word" name="up_word" >
<br><br>
<input type="submit" id="insert" name="insert" value="開始上傳">

</form>

</div>

<?php require_once('../base_footer.php')?>