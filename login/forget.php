<?php require("/phpMailer/class.phpmailer.php");
require_once('../base_home.php');


if(isset($_POST['send'])and isset($_POST['email'])){
	
	//$newpwd=substr(md5(rand()),0,10);
	$url = "http://163.17.9.238:8080/seminar/login/reset_pwd.php?th=$th &id=".base64_encode($_POST['email']);
	
	$sql = "SELECT * FROM mail_content";
	$result = mysqli_query($link,$sql);
	$row_data = mysqli_fetch_assoc($result);

	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true; // turn on SMTP authentication
	//這幾行是必須的

	$mail->Username = $row_data['sender'];  //taiwangame2015@gmail.com
	$mail->Password = base64_decode($row_data['sender_pwd']);  //taiwangametaiwangame
	//這邊是寄件者的gmail帳號和密碼

	$mail->FromName = "=?UTF-8?B?".base64_encode($row_data['subtitle'])."?=";
	// 寄件者名稱(你自己要顯示的名稱)
	//$webmaster_email = "syuanxu3u6@gmail.com"; 
	//回覆信件至此信箱


	$email= $_POST['email'];
	// 收件者信箱
	$name="安安";
	// 收件者的名稱or暱稱
	//$mail->From = $webmaster_email;

	$mail->AddAddress($email,$name);
	//$mail->AddReplyTo($webmaster_email,"taiwan_gmae");
	//這不用改

	$mail->WordWrap = 50;
	//每50行斷一次行

	//$mail->AddAttachment("/XXX.rar");
	// 附加檔案可以用這種語法(記得把上一行的//去掉)

	$mail->IsHTML(true); // send as HTML

	$mail->Subject = "=?UTF-8?B?".base64_encode('忘記密碼了嗎?')."?="; 
	// 信件標題
	$mail->Body = "<p>＊ 此信件為系統發出信件，請勿直接回覆，感謝您的配合。謝謝！＊</p>

	<p>親愛的會員 您好：<br>
	<p></p>
	這封認證信是由台灣觀光博彩研究發展學會發出，用以處理您忘記密碼，
	當您收到本「認證信函」後，請直接點選下方連結重新設置您的密碼，無需回信。</p>
	<p></p>
	$url
	<p></p>
	<p align='right'>台灣觀光博彩研究發展學會&nbsp;&nbsp;敬</p>
	<p></p>
	<p></p>
	<p>
	======================================<br>
	台灣觀光博彩研究發展學會<br>
	TEL:0988888888<br>
	======================================<br>
	</p>";
	//信件內容(html版，就是可以有html標籤的如粗體、斜體之類)
	$mail->AltBody = "信件內容"; 
	//信件內容(純文字版)

	if(!$mail->Send()){
		echo "寄信發生錯誤：" . $mail->ErrorInfo;
	}else{
		echo "<script>alert('已發送通知信於你的信箱!');</script>";
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='login.php?th=$th'>");
	} 
}

?>

<div id=content class="col-sm-9">
請輸入Email，我們將會把密碼寄到您的信箱。
<form class="form-horizontal form-text-size" role="form" method="POST" action="forget.php?th=<?php echo $th ?>">
  <h1>忘記密碼?</h1>
  <div class="form-group">
    <label class="control-label col-sm-2" for="email"><font color="red">*</font>Email：</label>
    <div class="col-sm-10">
      <input type="email" id="email" name="email" size="30" required>
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" class="btn btn-primary" name="send" value="送出" />
    </div>
  </div>
</form>
</div>
</div> <!--wrapper-->
</body>

<?php require_once('../base_footer.php')?>