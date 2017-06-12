<?php require_once('../base_home.php');



$query_mail = "SELECT re_mail FROM register";
$search_mail = mysqli_query($link,$query_mail);
$mail_data = mysqli_fetch_assoc($search_mail);
$mail_row = mysqli_fetch_row($search_mail);

$query_time = "SELECT job_end_date FROM time_job WHERE time_no = 2 ";
$time = mysqli_query($link,$query_time);
$time_data = mysqli_fetch_assoc($time);

$current_time = date("Y-m-d");//要打這個日期才可以正確抓出來
$same = false;

if(isset($_POST['email']) and isset($_POST['send'])){
  do{
    if ($_POST['email'] == $mail_data['re_mail']) {
      $same = true;
      echo "<script>alert('已有相同信箱!');</script>";
    }
  }while($mail_data = mysqli_fetch_assoc($search_mail));
  
  if($_POST['pwd'] != $_POST['secpwd']){
		echo "<script>alert('密碼與確認密碼不相符!');</script>";
  }else if ($_POST['county'] == "" or $_POST['district'] == "") {	
		echo "<script>alert('請選擇地區!');</script>";
  }else if ($same != true){
    if($current_time <= $time_data['job_end_date']){
        $wright = 1;
        $sqlIns = sprintf("INSERT INTO register (re_mail, re_lastName, re_firstName,
        re_job, re_pwd, re_phone, re_extension, re_fax, re_school, re_zipcode, re_county, 
        re_district, re_address, re_wright)
        values (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        '"'.$_POST['email'].'"', '"'.$_POST['lastname'].'"', '"'.$_POST['firstname'].'"', 
        '"'.$_POST['job'].'"', '"'.md5($_POST['pwd']).'"', '"'.$_POST['phone'].'"',
        '"'.$_POST['extension'].'"', '"'.$_POST['fax'].'"', '"'.$_POST['school'].'"',
        '"'.$_POST['zipcode'].'"', '"'.$_POST['county'].'"', '"'.$_POST['district'].'"', 
        '"'.$_POST['address'].'"', '"'.$wright.'"');

        $sqlI = mysqli_query($link, $sqlIns) or die('error MYSQL');
        if($sqlI){
            echo "<script>alert('註冊成功!');</script>";
            exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='login.php?th=$th>'>");
        }	
    }else{
        $wright = 0;
        $sqlIns = sprintf("INSERT INTO register (re_mail, re_lastName, re_firstName,
        re_job, re_pwd, re_phone, re_extension, re_fax, re_school, re_zipcode, re_county, 
        re_district, re_address, re_wright)
        values (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        '"'.$_POST['email'].'"', '"'.$_POST['lastname'].'"', '"'.$_POST['firstname'].'"', 
        '"'.$_POST['job'].'"', '"'.md5($_POST['pwd']).'"', '"'.$_POST['phone'].'"',
        '"'.$_POST['extension'].'"', '"'.$_POST['fax'].'"', '"'.$_POST['school'].'"',
        '"'.$_POST['zipcode'].'"', '"'.$_POST['county'].'"', '"'.$_POST['district'].'"', 
        '"'.$_POST['address'].'"', '"'.$wright.'"');

        $sqlI = mysqli_query($link, $sqlIns) or die('error MYSQL');
        if($sqlI){
            echo "<script>alert('註冊成功!');</script>";
            exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='login.php?th=$th'>");
        }
    }
  }
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="jquery.twzipcode.min.js"></script>
<link rel="stylesheet" href="zip.css">
<div id=content class="col-sm-9">
<!--class="form-control"-->
  <form class="form-horizontal form-text-size" role="form" method="POST" action="register.php?th=<?php echo $th?>">
  <h1>註冊會員</h1>
  <div class="form-group">
    <label class="control-label col-sm-2" for="email"><font color="red">*</font>信箱：</label>
    <div class="col-sm-10">
      <input type="email" id="email" name="email" size="30" <?php if(!empty($_POST['email'])){ echo "value='".$_POST['email']."'";}?> required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="lastname"><font color="red">*</font>姓：</label>
    <div class="col-sm-10"> 
      <input type="text" id="lastname" name="lastname" size="10" <?php if(!empty($_POST['lastname'])){ echo "value='".$_POST['lastname']."'";}?> required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="firstname"><font color="red">*</font>名：</label>
    <div class="col-sm-10">
      <input type="text" id="firstname" name="firstname" size="10" <?php if(!empty($_POST['firstname'])){ echo "value='".$_POST['firstname']."'";}?> required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="job">職稱：</label>
    <div class="col-sm-10">
      <input type="text" id="job" name="job" size="10" <?php if(!empty($_POST['job'])){ echo "value='".$_POST['job']."'";}?>>&nbsp;&nbsp;<small>(教授、學生.....)</small>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd"><font color="red">*</font>密碼：</label>
    <div class="col-sm-10">
      <input type="password" id="pwd" name="pwd" size="10" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="secpwd"><font color="red">*</font>確認密碼：</label>
    <div class="col-sm-10">
      <input type="password" id="secpwd" name="secpwd" size="10" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="phone"><font color="red">*</font>電話：</label>
    <div class="col-sm-10">
      <input type="text" id="phone" name="phone" size="10" maxlength="10" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" <?php if(!empty($_POST['phone'])){ echo "value='".$_POST['phone']."'";}?> required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="extension">分機：</label>
    <div class="col-sm-10">
      <input type="text" id="extension" name="extension" size="10" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" <?php if(!empty($_POST['extension'])){ echo "value='".$_POST['extension']."'";}?>>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="fax">傳真：</label>
    <div class="col-sm-10">
      <input type="text" id="fax" name="fax" size="10" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" <?php if(!empty($_POST['fax'])){ echo "value='".$_POST['fax']."'";}?>>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="school">學校/系所：</label>
    <div class="col-sm-10">
       <input type="text" id="school" name="school" size="15" <?php if(!empty($_POST['school'])){ echo "value='".$_POST['school']."'";}?>>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="address"><font color="red">*</font>地址：</label>
    <div class="col-sm-10">
      <div id="twzipcode"></div><br>
		<input type="text" id="address" name="address" size="50" <?php if(!empty($_POST['address'])){ echo "value='".$_POST['address']."'";}?> required>
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" class="btn btn-primary" name="send" value="送出">
    </div>
  </div>
</form>
</div>


</body>
<script>
$('#twzipcode').twzipcode({
	'readonly':true, 
	'css': ['county', 'district', 'zipcode'],
    'countyName'   : 'county',   // 預設值為 county
    'districtName' : 'district', // 預設值為 district
    'zipcodeName'  : 'zipcode',  // 預設值為 zipcode
});
</script>
<?php require_once('../base_footer.php')?>