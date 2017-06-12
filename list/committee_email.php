<?php require("/phpMailer/class.phpmailer.php");
require_once('../seminar_connect.php');
if(!isset($_SESSION)){  //開啟session功能，要是已經開啟就不用在開
	session_start();
}

$using = "SELECT * FROM seminar_".$th.".current_committee as A, seminar_generic_data.committee_list as B WHERE A.cc_email = B.cl_email";
$result_new1 = mysqli_query($link,$using);
$row_data_new1 = mysqli_fetch_assoc($result_new1);

$sql = "SELECT * FROM mail_content";
$result = mysqli_query($link,$sql);
$row_data = mysqli_fetch_assoc($result);

do{
$cg_login = "http://163.17.9.238:8080/seminar/current_committee/cg_login.php";
$accout = $row_data_new1['cc_email'];
$sendname = $row_data_new1['cl_name'];
$mail_content = $row_data['mail_content'];

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

$email = $accout;
// 收件者信箱 
$name="安安";
// 收件者的名稱or暱稱
//$mail->From = $webmaster_email;


 
    ##### 隨機密碼可能包含的字符
    $str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr(str_shuffle($str), 0, 10); 	
    ##### 產生 10 位的密碼
	
	$sqlUpd = sprintf("UPDATE current_committee SET cc_pwd='%s' WHERE cc_email = '%s'"
			,$password,$accout);	
	$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");			



$mail->AddAddress($email,$name);
//$mail->AddReplyTo($webmaster_email,"taiwan_gmae");
//這不用改

$mail->WordWrap = 50;
//每50行斷一次行  

//$mail->AddAttachment("/XXX.rar");
// 附加檔案可以用這種語法(記得把上一行的//去掉)



$mail->IsHTML(true); // send as HTML

$mail->Subject = "=?UTF-8?B?".base64_encode('審稿邀請')."?="; 
// 信件標題
$mail->Body = "親愛的$sendname  您好<br>
<br>
我們誠摯地邀請您參與本屆的審查委員<br>
委員登入網址：$cg_login<br>
帳號為您的信箱：$accout<br>
密碼：$password<br>
<br>

<br>
$mail_content";
//信件內容(html版，就是可以有html標籤的如粗體、斜體之類)
$mail->AltBody = "信件內容"; 
//信件內容(純文字版)

	if(!$mail->Send()){
		echo "寄信發生錯誤：" . $mail->ErrorInfo;
		//如果有錯誤會印出原因
	}
	
}while ($row_data_new1 = mysqli_fetch_assoc($result_new1));
 

echo "<script>alert('已發送邀請信於你的信箱!');</script>";
exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=list.php>');
	
 ?>