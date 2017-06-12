<?php require("/phpMailer/class.phpmailer.php");
require_once('../seminar_connect.php');

$using = $_SESSION['re_mail'];
echo $using;
$sql_new = "SELECT upload.up_no, upload.upsort_num, upload.up_paper, register.re_firstName FROM upload, register where upload.up_user = '$using' and register.re_mail = '$using' ORDER BY up_no DESC Limit 1";
$result_new = mysqli_query($link,$sql_new);
$row_data_new = mysqli_fetch_assoc($result_new);

$sql = "SELECT * FROM mail_content";
$result = mysqli_query($link,$sql);
$row_data = mysqli_fetch_assoc($result);

$num = $row_data_new['upsort_num'];
$title = $row_data_new['up_paper'];
$sendname = $row_data_new['re_firstName'];
$mail_content = $row_data['mail_content'];
$up_no=$row_data_new['up_no'];

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true; // turn on SMTP authentication
//這幾行是必須的

$mail->Username = $row_data['sender'];  //taiwangame2015@gmail.com
$mail->Password = base64_decode($row_data['sender_pwd']);  //taiwangametaiwangame
//這邊是寄件者的gmail帳號和密碼

// echo $row_data['sender'];
// echo base64_decode($row_data['sender_pwd']); 

$mail->FromName = "=?UTF-8?B?".base64_encode($row_data['subtitle'])."?=";
// 寄件者名稱(你自己要顯示的名稱)
//$webmaster_email = "syuanxu3u6@gmail.com"; 
//回覆信件至此信箱


$email= $using;
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

$mail->Subject = "=?UTF-8?B?".base64_encode('上傳論文')."?="; 
// 信件標題
$mail->Body = "親愛的$sendname  您好<br>
<br>
我們很高興地通知您
感謝您上傳了一篇論文!<br>
<br>
<font color=red>論文編號是:$num</font><br>
<font color=red>論文名稱是:$title</font><br>
<br>
$mail_content";
//信件內容(html版，就是可以有html標籤的如粗體、斜體之類)
$mail->AltBody = "信件內容"; 
//信件內容(純文字版)

if(!$mail->Send()){
	echo "寄信發生錯誤：" . $mail->ErrorInfo;
	//如果有錯誤會印出原因
}else{ //echo("寄信成功");
	echo "<script>alert('已發送通知信於你的信箱!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='upload_insert.php?th=$th&up_no=$up_no'>");
} ?>