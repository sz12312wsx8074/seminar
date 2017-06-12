<?php require_once('../base_home.php');

$user = $_SESSION['re_mail'];

$query = "SELECT * FROM invite where in_re_mail = '$user'";
$data = mysqli_query($link,$query);
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);

if ($row_data['in_isInvite'] == 0){
  echo "<script>alert('請先報名研討會!');</script>";
  exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='invite.php?th=$th'>");
}


$time = "SELECT * FROM important_date where where_from = '3'";
$time_data = mysqli_query($link,$time);
$row_time = mysqli_fetch_assoc($time_data);
$start_date = $row_time['date_date'];

$date=date_create("$start_date");
date_sub($date,date_interval_create_from_date_string("7 days"));
$end_time = date_format($date,"Y-m-d");


if (isset($_POST['send'])){
  if ($_POST['meat'] + $_POST['vagetarian'] != $_POST['people']){
    echo "<script>alert('人數有誤!');</script>";
  }else{
    $sqlIns = sprintf("INSERT INTO invite (in_re_mail, in_name, in_people, in_meatDish,
    in_vegetarian, in_school, in_email, in_phone, in_whatInvite, in_isParty) 
    values (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
  '"'.$user.'"', '"'.$_POST['name'].'"', '"'.$_POST['people'].'"', '"'.$_POST['meat'].'"', 
  '"'.$_POST['vagetarian'].'"', '"'.$_POST['school'].'"',
  '"'.$_POST['email'].'"', '"'.$_POST['phone'].'"', '"'.'party'.'"', '"'.'1'.'"');
    
    $sqlI = mysqli_query($link, $sqlIns) or die('error MYSQL123');
    if($sqlI){
      echo "<script>alert('報名成功!');</script>";
      exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='party.php?th=$th'>");
    }
  }
}

?>
<div id=content class="col-sm-9">
<h1>在此報名宴會</h1>
<div class="topnav">
<a class="label label-Danger" href="/seminar/login/invite.php?th=<?php echo $th;?>">報名研討會</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/user_update.php?th=<?php echo $th;?>">帳號管理</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/fee.php?th=<?php echo $th;?>">繳費資訊</a>&nbsp;&nbsp;
<a href="/seminar/login/logout.php?th=<?php echo $th;?>"><?php echo $user;?>&nbsp;登出</a>
</div>
<br>
<?php if ($row_sum != 2){ ?>
<form class="form-horizontal form-text-size" role="form" method="POST" action="party.php?th=<?php echo $th;?>"> <!--enctype="multipart/form-data"-->
  <div class="form-group">
    <label class="control-label col-sm-2" for="name"> <font color="red">*</font>姓名：</label>
    <div class="col-sm-10">
      <input type="text" id="name" name="name" <?php if(!empty($_POST['name'])){ echo "value='".$_POST['name']."'";}?> required>
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
    <label class="control-label col-sm-2" for="pwd"> <font color="red">*</font>葷食：</label>
    <div class="col-sm-10"> 
    <select name="meat">
     <?php for( $i=0;$i<11;$i++){
        echo "<option value=".$i.">".$i."</option>";
       }?>
    </select>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd"> <font color="red">*</font>素食：</label>
    <div class="col-sm-10"> 
    <select name="vagetarian">
      <?php for( $i=0;$i<11;$i++){
        echo "<option value=".$i.">".$i."</option>";
       } ?>
    </select>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="school"> <font color="red">*</font>學校/系所：</label>
    <div class="col-sm-10"> 
      <input type="text" id="school" name="school" <?php if(!empty($_POST['school'])){ echo "value='".$_POST['school']."'";}?> required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="phone"> <font color="red">*</font>手機：</label>
    <div class="col-sm-10"> 
      <input type="text" id="phone" name="phone" maxlength="10" <?php if(!empty($_POST['phone'])){ echo "value='".$_POST['phone']."'";}?> required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="email"> <font color="red">*</font>Email：</label>
    <div class="col-sm-10"> 
      <input type="email" id="email" name="email" <?php if(!empty($_POST['email'])){ echo "value='".$_POST['email']."'";}?> required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2"></label>
    <div class="col-sm-10">
      如欲搭乘接駁車請於<?php echo $end_time ?>前登記，前往＞＞<a href="/seminar/bus_enrolment/bus_enrolment_index.php?th=<?php echo $th ?>">接駁車登記</a>
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" class="btn btn-primary" name="send" value="報名確認">
    </div>
  </div>
</form>
<?php }else{
  $query_party = "SELECT * FROM invite where in_re_mail = '$user' and in_whatInvite = 'party'";
  $data_party = mysqli_query($link,$query_party);
  $sql_data = mysqli_fetch_assoc($data_party); ?>

<form class="form-horizontal form-text-size" role="form" method="POST" action="party.php?th=<?php echo $th;?>"> <!--enctype="multipart/form-data"-->
  <div class="form-group">
    <label class="control-label col-sm-2" for="name"> <font color="red">*</font>姓名：</label>
    <div class="col-sm-10">
      <?php echo $sql_data['in_name']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="people"> <font color="red">*</font>參加人數：</label>
    <div class="col-sm-10"> 
      <?php echo $sql_data['in_people']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd"> <font color="red">*</font>葷食：</label>
    <div class="col-sm-10"> 
      <?php echo $sql_data['in_meatDish']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd"> <font color="red">*</font>素食：</label>
    <div class="col-sm-10"> 
      <?php echo $sql_data['in_vegetarian']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="school"> <font color="red">*</font>學校/系所：</label>
    <div class="col-sm-10"> 
      <?php echo $sql_data['in_school']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="phone"> <font color="red">*</font>手機：</label>
    <div class="col-sm-10"> 
      <?php echo $sql_data['in_phone']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="email"> <font color="red">*</font>Email：</label>
    <div class="col-sm-10"> 
     <?php echo $sql_data['in_email']?>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2"></label>
    <div class="col-sm-10">
      如欲搭乘接駁車請於<?php echo $end_time ?>前登記，前往＞＞<a href="/seminar/bus_enrolment/bus_enrolment_index.php?th=<?php echo $th ?>">接駁車登記</a>
    </div>
  </div>
</form>
<?php }?>
<!--上傳收據<input type="file" name="receipt" id="receipt">-->
</div>
</body>


<?php require_once('../base_footer.php')?>