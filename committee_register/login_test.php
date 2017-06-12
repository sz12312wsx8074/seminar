<?php session_start(); ?>
<!--上方語法為啟用session，此語法要放在網頁最前方-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
//連接資料庫
//只要此頁面上有用到連接MySQL就要include它
include("../seminar_connect.php");
$mail = $_POST['mail'];
$pwd = $_POST['pwd'];

//搜尋資料庫資料
$sql = "SELECT * FROM committee_register where cg_mail = '$mail' ";
$result = mysql_query($link,$sql);
$row_data = mysql_fetch_assoc($result);

//判斷帳號與密碼是否為空白
//以及MySQL資料庫裡是否有這個會員
if($mail != null && $pwd != null && $row_data['cg_mail'] == $mail && $row_data['cg_pwd'] == $pwd)
{
        //將帳號寫入session，方便驗證使用者身份
        $_SESSION['username'] = $mail;
        echo '登入成功!';
        echo '<meta http-equiv=REFRESH CONTENT=1;url=/seminar/committee_register/test.php>';
}
else
{
        echo '登入失敗!';
        echo '<meta http-equiv=REFRESH CONTENT=1;url=/seminar/committee_register/cg_login.php>';
}
?>
