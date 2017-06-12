<?php require_once('seminar_connect.php');


if(isset($_POST['login'])){	
	$id = $_POST['userName'];
	$pwd = $_POST['userPassword'];

	$sql = "SELECT * FROM admin where admin_id = '$id' ";
	$result = mysqli_query($link_generic,$sql);
	$row_data = mysqli_fetch_assoc($result);

	if($row_data['admin_id'] == $id && $row_data['admin_pwd'] == base64_encode($pwd))
	{
			$_SESSION['admin_id'] = $id;
			echo "<script>alert('已登入!!');</script>";
			exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/seminar_th.php>');
	}else{
			echo "<script>alert('帳號或密碼錯誤，請重新輸入!');</script>";
			exit ('<script>location.href="/seminar/login.php"</script>');
	}
}
?>
<head>
<link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css>
<link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css>
</head>
<header>
</header>
<div id=login_wrapper>
  <div class="container">
      <div class="row">
          <div class="col-md-offset-4 col-md-3">
                  <div class="form-login">
                      <form method="POST" action="login.php">
                      <h4>Welcome back to Seminar</h4>
                      <input type="text" name="userName" class="form-control input-sm chat-input" placeholder="帳號" required autofocus/>
                      </br>
                      <input type="password" name="userPassword" class="form-control input-sm chat-input" placeholder="密碼" required/>
                      </br>
                      <input type="submit" class="btn btn-primary btn-md group-btn" name="login" value="登入"/>
                      </form>
                  </div>
          </div>
      </div>
  </div>
</div>



</body>

<?php require_once('base_footer.php')?>

