<?php require_once('../base_home.php');

$user = $_SESSION['re_mail'];

$query = "SELECT * FROM invite where in_re_mail = '$user' and in_whatInvite = 'seminar'";
$data = mysqli_query($link,$query);
$row_data = mysqli_fetch_assoc($data);

$sql = "SELECT * FROM register where re_mail = '$user'";
$acc_data = mysqli_query($link,$sql);
$acc_row_data = mysqli_fetch_assoc($acc_data);


$time = "SELECT * FROM important_date where where_from = '3'";
$time_data = mysqli_query($link,$time);
$row_time = mysqli_fetch_assoc($time_data);
$start_date = $row_time['date_date'];

$date=date_create("$start_date");
date_sub($date,date_interval_create_from_date_string("7 days"));
$end_time = date_format($date,"Y-m-d");


if (isset($_POST['send'])){  //
  $sqlIns = sprintf("INSERT INTO invite (in_re_mail, in_name, in_people, in_school,
  in_email, in_phone, in_whatInvite, in_isInvite) values (%s, %s, %s, %s, %s, %s, %s, %s)",
  '"'.$user.'"', '"'.$_POST['name'].'"', '"'.$_POST['people'].'"', '"'.$_POST['school'].'"', 
  '"'.$_POST['email'].'"', '"'.$_POST['phone'].'"', '"'.'seminar'.'"', '"'.'1'.'"');

  $sqlI = mysqli_query($link, $sqlIns) or die('error MYSQL');
  if($sqlI){
      echo "<script>alert('報名成功!');</script>";
      exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='invite.php?th=$th'>");
  }
}

?>
<div id=content class="col-sm-9">
<h1>在此報名研討會</h1>
<div class="topnav">
<?php if ($acc_row_data['re_wright'] == 1){?>
<a class="label label-primary" href="/seminar/login/camera_ready_list.php?th=<?php echo $th; ?>">論文列表</a>&nbsp;&nbsp;
<?php }?>  
<a class="label label-Danger" href="/seminar/login/party.php?th=<?php echo $th;?>">報名宴會</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/user_update.php?th=<?php echo $th;?>">帳號管理</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/fee.php?th=<?php echo $th;?>">繳費資訊</a>&nbsp;&nbsp;  
<a href="/seminar/login/logout.php?th=<?php echo $th;?>"><?php echo $user;?>&nbsp;登出</a>
</div>
<br>
<?php if ($row_data['in_isInvite'] == 1){?>
  
  <form class="form-horizontal form-text-size" role="form" method="POST" action="invite.php?th=<?php echo $th;?>">
  <div class="form-group">
    <label class="control-label col-sm-2" for="email"> <font color="red">*</font>姓名：</label>
    <div class="col-sm-10">
      <?php echo $row_data['in_name']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd"> <font color="red">*</font>參加人數：</label>
    <div class="col-sm-10"> 
      <?php echo $row_data['in_people']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd"> <font color="red">*</font>學校/系所：</label>
    <div class="col-sm-10"> 
      <?php echo $row_data['in_school']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd"> <font color="red">*</font>手機：</label>
    <div class="col-sm-10"> 
      <?php echo $row_data['in_phone']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd"> <font color="red">*</font>Email：</label>
    <div class="col-sm-10"> 
      <?php echo $row_data['in_email']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2"></label>
    <div class="col-sm-10">
      如欲搭乘接駁車請於<?php echo $end_time ?>前登記，前往＞＞<a href="/seminar/bus_enrolment/bus_enrolment_index.php?th=<?php echo $th ?>">接駁車登記</a>
    </div>
  </div>
</form>
<?php }else {?>
<form class="form-horizontal form-text-size" role="form" method="post" action="invite.php?th=<?php echo $th;?>"> <!--enctype="multipart/form-data"-->
  <div class="form-group">
    <label class="control-label col-sm-2" for="name"> <font color="red">*</font>姓名：</label>
    <div class="col-sm-10">
      <input type="text" id="name" name="name"  size="10" <?php if(!empty($_POST['name'])){ echo "value='".$_POST['name']."'";}?> required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="people"> <font color="red">*</font>參加人數：</label>
    <div class="col-sm-10"> 
      <select name="people" id="people"> 
      <?php for( $i=1;$i<11;$i++){
        echo "<option value=".$i.">".$i."</option>";
       } ?>
    </select>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="school"> <font color="red">*</font>學校/系所：</label>
    <div class="col-sm-10"> 
      <input type="text" id="school" name="school" size="10" <?php if(!empty($_POST['school'])){ echo "value='".$_POST['school']."'";}?> required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="phone"> <font color="red">*</font>手機：</label>
    <div class="col-sm-10"> 
      <input type="text" id="phone" name="phone" maxlength="10"  size="10" <?php if(!empty($_POST['phone'])){ echo "value='".$_POST['phone']."'";}?> required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="email"> <font color="red">*</font>Email：</label>
    <div class="col-sm-10"> 
      <input type="email" id="email" name="email"  size="30" value="<?php echo $user?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2"></label>
    <div class="col-sm-10">
      如欲搭乘接駁車請於<?php echo $end_time ?>前登記，前往＞＞<a href="/seminar/bus_enrolment/bus_enrolment_index.php?th=<?php echo $th;?>">接駁車登記</a>
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" class="btn btn-primary" name="send" value="報名確認">
    </div>
  </div>
</form>
<?php }?>
 <!--上傳收據<input type="file" name="receipt" id="receipt">-->
</div>
</body>


<?php require_once('../base_footer.php')?>