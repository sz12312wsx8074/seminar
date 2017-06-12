<?php require_once('../base_home.php');

$user = $_SESSION['re_mail'];

$sql = "SELECT * FROM register where re_mail = '$user' ";
$result = mysqli_query($link,$sql);
$row_data = mysqli_fetch_assoc($result);

$sql_con = "SELECT page_content FROM page_content where page_name = 'back_user'";
$result_con = mysqli_query($link,$sql_con);
$row_data_con = mysqli_fetch_assoc($result_con);

$query_time = "SELECT job_end_date FROM time_job WHERE time_no = 1 ";
$time = mysqli_query($link,$query_time);
$time_data = mysqli_fetch_assoc($time);

$current_time = date("Y-m-d");

if(isset($_POST['send'])){
	if ($_POST['county'] == "" or $_POST['district'] == ""){
		echo "<script>alert('請選擇地區!');</script>";
	}else{
		$sqlUpd = sprintf("UPDATE register SET re_lastName = '%s', re_firstName= '%s', 
		re_job = '%s', re_phone='%s', re_extension='%s', 
		re_fax='%s', re_school='%s', re_zipcode='%s', re_county='%s', re_district='%s', 
		re_address='%s' WHERE re_mail = '$user'",
		$_POST['lastname'], $_POST['firstname'], $_POST['job'],
		$_POST['phone'], $_POST['extension'], $_POST['fax'], $_POST['school'],
		$_POST['zipcode'], $_POST['county'], $_POST['district'], $_POST['address']);
		$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
			echo "<script>alert('已修改!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=user_update.php?th=$th>");
		}
	}
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="jquery.twzipcode.min.js"></script>
<link rel="stylesheet" href="zip.css">

<div id=content class="col-sm-9">
<h1>帳號管理</h1>
<p><?php echo $row_data_con['page_content'];?></p>
<div class="topnav">
<?php if ($row_data['re_wright'] == 1){
	if ($current_time <= $time_data['job_end_date']){ //在投稿期間 ?>
	<a class="label label-primary" href="/seminar/login/paper.php?th=<?php echo $th; ?>">上傳論文</a>&nbsp;&nbsp;
	<a class="label label-primary" href="/seminar/login/paper_list.php?th=<?php echo $th; ?>">論文列表</a>&nbsp;&nbsp;
 <?php }else{?>
	<a class="label label-primary" href="/seminar/login/camera_ready_list.php?th=<?php echo $th; ?>">論文列表</a>&nbsp;&nbsp;
 <?php } ?>
 <?php }else if($row_data['re_wright'] == 0){ ?>
  <a class="label label-Danger" href="/seminar/login/invite.php?th=<?php echo $th; ?>">報名研討會</a>&nbsp;&nbsp;
 <?php } ?>
<a class="label label-primary" href="/seminar/login/user_update.php?th=<?php echo $th; ?>">帳號管理</a>&nbsp;&nbsp;
<a href="/seminar/login/logout.php?th=<?php echo $th; ?>"><?php echo $user;?>&nbsp;登出</a>
</div>
<br>
  
  
<form class="form-horizontal form-text-size" role="form" method="POST" action="user_update.php?th=<?php echo $th; ?>">
  <div class="form-group">
    <label class="control-label col-sm-2"> <font color="red">*</font>Email：</label>
    <div class="col-sm-10">
      <?php echo $row_data['re_mail']?> &nbsp;
       <button type="button" class="btn btn-success" data-toggle="modal" data-target="#reset_pwd">修改密碼</button>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="lastname"> <font color="red">*</font>姓：</label>
    <div class="col-sm-10"> 
      <input type="text" name="lastname" id="lastname" size="10" value="<?php echo $row_data['re_lastName']?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="firstname"> <font color="red">*</font>名：</label>
    <div class="col-sm-10"> 
      <input type="text" name="firstname" id="firstname" size="10" value="<?php echo $row_data['re_firstName']?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="job">職稱：</label>
    <div class="col-sm-10"> 
      <input type="text" name="job" id="job" size="10" value="<?php echo $row_data['re_job']?>">&nbsp;&nbsp;<small>(教授、學生.....)</small>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="phone"> <font color="red">*</font>電話：</label>
    <div class="col-sm-10"> 
      <input type="text" name="phone" id="phone" size="10" value="<?php echo $row_data['re_phone']?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="extension">分機：</label>
    <div class="col-sm-10"> 
      <input type="text" name="extension" id="extension" size="10" value="<?php echo $row_data['re_extension']?>">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="fax">傳真：</label>
    <div class="col-sm-10"> 
      <input type="text" name="fax" id="fax" size="10" value="<?php echo $row_data['re_fax']?>">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="school">學校/系所：</label>
    <div class="col-sm-10"> 
      <input type="text" name="school" id="school" size="10" value="<?php echo $row_data['re_school']?>">
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd"> <font color="red">*</font>地址：</label>
    <div class="col-sm-10"> 
      <div id="twzipcode"></div><br>
      <input type="text" name="address" id="address" size="50" value="<?php echo $row_data['re_address']?>">
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" class="btn btn-primary" name="send" value="修改">
    </div>
  </div>
</form>
</div>

<!--reset_pwd model-->
<div class="modal fade" id="reset_pwd" tabindex="-1" role="dialog" aria-labelledby="reset_pwd" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">修改密碼</h4>
        </div>
        <div class="modal-body">
          <form class="form-text-size" role="form" method="POST" action="reset_pwd_run.php?th=<?php echo $th; ?>">
            <input type="hidden" name="id" value="<?php echo base64_encode($user); ?>">
            <input type="hidden" name="source" value="user_update">
            <div class="form-group">
              <label class="control-label" for="pwd"><font color="red">*</font>密碼：</label>
              <div>
                <input type="password" id="pwd" name="pwd" required>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label" for="secpwd"><font color="red">*</font>確認密碼：</label>
              <div> 
                <input type="password" id="secpwd" name="secpwd" required>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
          <button type="submit" class="btn btn-primary">修改</button>
        </div>
        </form>
      </div>
    </div>
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
  
$(document).ready(function(){
   var county = "<?php echo $row_data['re_county']; ?>";
   $("select[name='county']").val(county);
  $("select[name='county']").trigger("change");
   var district = "<?php echo $row_data['re_district']; ?>";
   $("select[name='district']").val(district);
  
   var zipcode = "<?php echo $row_data['re_zipcode']; ?>";
   $("input[name='zipcode']").val(zipcode);
  
});
</script>
<?php require_once('../base_footer.php')?>