<?php require_once('../seminar_connect.php');

if(isset($_POST['email'])){	

	$email = $_POST['email'];
	$pwd = $_POST['pwd'];

	$sql = "SELECT * FROM current_committee where cc_email = '$email' ";
	$result = mysqli_query($link,$sql);
	$row_data = mysqli_fetch_assoc($result);

	if($email != null && $pwd != null && $row_data['cc_email'] == $email && $row_data['cc_pwd'] == $pwd)
	{
			$_SESSION['cc_email'] = $email;
			
			echo "<script>alert('已登入!!');</script>";
			exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/review/review.php>');
	}else{
			echo "<script>alert('帳號或密碼錯誤，請重新輸入!');</script>";
			exit ('<script>location.href="/seminar/current_committee/cg_login.php"</script>');
	}
}
?>

<head>
<link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css>
<link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css>
<link rel=stylesheet href="/journal/css/base_home.css">
<link rel=stylesheet href="/journal/css/content.css">
</head>
<header>
<div>
	<img src="/seminar/header_img/header_img.jpg" width="100%" height="100%"/>
</div>
</header>

<div class="container">
    <div class="row">
        <div class="col-md-offset-4 col-md-3">
				<div class="form-login">
					<form method="POST" action="cg_login.php">
					<h4>本屆審查委員登入</h4>
					<input type="text" name="email" class="form-control input-sm chat-input" placeholder="帳號" required autofocus/>
					</br>
					<input type="password" name="pwd" class="form-control input-sm chat-input" placeholder="密碼" required/>
					</br>
					<input type="submit" class="btn btn-primary btn-md group-btn" name="login" value="登入"/>
					</form>
				</div>
        </div>
    </div>
</div>



</body>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php require_once('../base_footer.php')?>

