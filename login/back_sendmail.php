<?php require_once('../base_home.php'); 

$sql = "SELECT * FROM mail_content";
$result = mysqli_query($link,$sql);
$row_data = mysqli_fetch_assoc($result);

$time_sql = "SELECT * FROM time_job where time_no = 5";
$time_result = mysqli_query($link,$time_sql);
$time_data = mysqli_fetch_assoc($time_result);

$year = date("Y-m-d");

if(isset($_POST['submit'])){ 
	$sqlUpd = sprintf("UPDATE mail_content SET sender='%s', sender_pwd='%s', subtitle='%s', mail_content='%s'",
	$_POST['sender'], base64_encode($_POST['sender_pwd']), $_POST['subtitle'], $_POST['mail_content']);
	$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
	if($sqlU){
		echo "<script>alert('已修改!!');</script>";
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=back_sendmail.php?th=$th>");
	}
}
?>

<div id=content class="col-sm-9">
<h1>編輯信件</h1>
<div class="topnav">
<a class="label label-pill label-success" href="/seminar/login/back_member.php?th=<?php echo $th;?>">會員查詢</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_content.php?th=<?php echo $th;?>">編輯說明文字</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_fee.php?th=<?php echo $th;?>">編輯費用</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_sendmail.php?th=<?php echo $th;?>">編輯信件</a>
</div>
<br>
  
  
 <form class="form-horizontal form-text-size" role="form" method="POST" action="back_sendmail.php?th=<?php echo $th;?>">
  <div class="form-group">
    <label class="control-label col-sm-2" for="sender"> <font color="red">*</font>寄件者帳號：</label>
    <div class="col-sm-10">
      <input type="email" id="sender" name="sender" size="30" value="<?php echo $row_data['sender'];?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="sender_pwd"> <font color="red">*</font>寄件者密碼：</label>
    <div class="col-sm-10"> 
      <input type="password" id="sender_pwd" name="sender_pwd" value="<?php echo base64_decode($row_data['sender_pwd']);?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="subtitle"> <font color="red">*</font>信件標題：</label>
    <div class="col-sm-10"> 
      <input type="text" id="subtitle" name="subtitle" value="<?php echo $row_data['subtitle'];?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="sender_pwd"> <font color="red">*</font>信件內容：</label>
    <div class="col-sm-10">
      <script>tinymce.init({selector:'textarea',width:575, height:200});</script>
      <textarea id="mail_content" name="mail_content"><?php echo $row_data['mail_content'];?></textarea>
    </div>
  </div>
  <?php if ($year<=$time_data['job_end_date']){?>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" class="btn btn-primary" name="submit" value="修改">
    </div>
  </div>
  <?php }?>
</form>
</div>
</body>
<?php require_once('../base_footer.php')?>