<?php require_once('../base_home.php');
	
if(isset($_POST['mail'])){	
	$mail = $_POST['mail'];
	$pwd = $_POST['pwd'];

	$sql = "SELECT * FROM committee_register where cg_mail = '$mail' ";
	$result = mysqli_query($link,$sql);
	$row_data = mysqli_fetch_assoc($result);

	if($mail != null && $pwd != null && $row_data['cg_mail'] == $mail && $row_data['cg_pwd'] == $pwd)
	{
			$_SESSION['cg_mail'] = $mail;
			echo "<script>alert('已登入!!');</script>";
			exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/review.php>');
	}else{
			echo "<script>alert('帳號或密碼錯誤，請重新輸入!');</script>";
			exit ('<script>location.href="/seminar/committee_register/cg_login.php"</script>');
	}
}
?>

<div id=content>
<form method="POST" action="cg_login.php">
<p>帳號:<input type="text" id="mail" name="mail"></p>
<p>密碼:<input type="password" id="pwd" name="pwd"></p>
<input type="submit" name="login" value="登入"/>

</form>
</div>
</body>

<?php require_once('../base_footer.php')?>

