<?php require_once('base_home.php');

$query_data = 'SELECT * FROM admin';
$data = mysqli_query($link_generic,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);


if (isset($_POST['send'])) {
	if ($_POST['id'] == $row_data['admin_id'] or $_POST['pwd'] == $row_data['admin_pwd']){
		echo "<script>alert('帳號密碼不可重複');</script>";
	}else if ($_POST['pwd'] != $_POST['second_pwd']){
		echo "<script>alert('兩組密碼不相同');</script>";
	}else{
		$sqlUpd = sprintf("UPDATE admin SET admin_id = '%s', admin_pwd = '%s'"
		, $_POST['id'], base64_encode($_POST['pwd']));
		$sqlU = mysqli_query($link_generic, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
				echo "<script>alert('已修改帳號密碼!!');</script>";
				exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=home/home.php>');
        }
	}

}


?>
<div id=content class="col-sm-9">
<form class="form-horizontal form-text-size" role="form" method="POST" action="admin_update.php">
  <div class="form-group">
    <label class="control-label col-sm-2"> <font color="red">*</font>帳號：</label>
    <div class="col-sm-10">
      <?php echo $row_data['admin_id']?> 
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="id"> <font color="red">*</font>設定新帳號：</label>
    <div class="col-sm-10"> 
      <input type="text" name="id" id="id" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="id"> <font color="red">*</font>設定新密碼：</label>
    <div class="col-sm-10"> 
      <input type="password" name="pwd" id="pwd" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="id"> <font color="red">*</font>確認密碼：</label>
    <div class="col-sm-10"> 
      <input type="password" name="second_pwd" id="second_pwd" required>
    </div>
  </div>
  
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" class="btn btn-primary" name="send" value="送出">
    </div>
  </div>
</form>
</div>
</div> <!--wrapper-->
</body>

<?php require_once('base_footer.php')?>