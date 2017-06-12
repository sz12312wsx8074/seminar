<?php require_once('../base_home.php');

$sql_con = "SELECT page_content FROM page_content where page_name = 'login'";
$result_con = mysqli_query($link,$sql_con);
$row_data_con = mysqli_fetch_assoc($result_con);

$query_time = "SELECT job_end_date FROM time_job WHERE time_no = 1 ";
$time = mysqli_query($link,$query_time);
$time_data = mysqli_fetch_assoc($time);

$current_time = date("Y-m-d");

if(isset($_SESSION['re_mail'])){
  $log = $_SESSION['re_mail'];
  $sql = "SELECT re_wright FROM register where re_mail = '$log' ";
  $result = mysqli_query($link,$sql);
  $row_data = mysqli_fetch_assoc($result);
  
  if ($row_data['re_wright'] == 0){
    exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/login/invite.php?th=$th>");
  }else{
	  if ($current_time <= $time_data['job_end_date']) {
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/login/paper.php?th=$th>");
	  }else{
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/login/camera_ready_list.php?th=$th>");
	  }
  }
}

if(isset($_POST['login'])){
  
  $email = $_POST['email'];
  $pwd = $_POST['pwd'];
  
  $sql = "SELECT re_mail, re_pwd, re_wright FROM register where re_mail = '$email' ";
  $result = mysqli_query($link,$sql);
  $row_data = mysqli_fetch_assoc($result);
  
  if($email != '' && $pwd != '' && $row_data['re_mail'] == $email && $row_data['re_pwd'] == md5($pwd))
  {
      $_SESSION['re_mail'] = $email;
      if($row_data['re_wright'] == 0){
        exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/login/invite.php?th=$th>");
      }else{
		  if ($current_time <= $time_data['job_end_date']) {
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/login/paper.php?th=$th>");
		  }else{
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/login/camera_ready_list.php?th=$th>");
		  }
      }
  }else{
      echo "<script>alert('帳號或密碼錯誤，請重新輸入!');</script>";
      exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/login/login.php?th=$th>");
  }
}

?>

<div id=content class="col-sm-9">
<p><?php echo $row_data_con['page_content']?></p>
<form class="form-text-size" method="POST" action="login.php?th=<?php echo $th ?>">
  <div class="form-group">
    Email：<input type="email" name="email" size="15" required>
  </div>
  <div class="form-group">
    密碼：<input type="password" name="pwd" size="15" required>
  </div>
  <div class="form-group">
    <a href="/seminar/login/forget.php?th=<?php echo $th ?>">忘記密碼?</a>&nbsp;&nbsp;&nbsp;
	<a href="/seminar/login/register.php?th=<?php echo $th ?>">註冊</a>
  </div>
  <input type="submit" class="btn btn-primary" name="login" value="登入" />
</form>
</div>
</body>

<?php require_once('../base_footer.php')?>