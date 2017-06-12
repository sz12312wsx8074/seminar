<?php require_once('../base_home.php'); 

$sql = "SELECT * FROM contribute_fee";
$result = mysqli_query($link,$sql);
$row_data = mysqli_fetch_assoc($result);

$time_sql = "SELECT * FROM time_job where time_no = 5";
$time_result = mysqli_query($link,$time_sql);
$time_data = mysqli_fetch_assoc($time_result);

$year = date("Y-m-d");

if(isset($_POST['submit'])){ 
	$sqlUpd = sprintf("Insert into contribute_fee (seminar_fee, banquet_fee, first_paper, second_paper) values (%s, %s, %s, %s)",
	'"'.$_POST['seminar_fee'].'"', '"'.$_POST['banquet_fee'].'"', '"'.$_POST['first_paper'].'"', '"'.$_POST['second_paper'].'"');
	$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
	if($sqlU){
		echo "<script>alert('已修改!!');</script>";
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=back_fee.php?th=$th>");
	}
}
?>
<style>
  .number{
    text-align:right;
  }
</style>



<div id=content class="col-sm-9">
<h1>編輯費用</h1>
<div class="topnav">
<a class="label label-pill label-success" href="/seminar/login/back_member.php?th=<?php echo $th;?>">會員查詢</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_content.php?th=<?php echo $th;?>">編輯說明文字</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_fee.php?th=<?php echo $th;?>">編輯費用</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_sendmail.php?th=<?php echo $th;?>">編輯信件</a>
</div>
<br>
  
  
 <form class="form-horizontal form-text-size" role="form" method="POST" action="back_fee.php?th=<?php echo $th;?>">
  <div class="form-group">
    <label class="control-label col-sm-2" for="seminar_fee"> <font color="red">*</font>研討會參加費用：</label>
    <div class="col-sm-10">
      <input type="text" class="number" id="seminar_fee" size="10"  maxlength="10" name="seminar_fee" value="<?php echo $row_data['seminar_fee'];?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="banquet_fee"> <font color="red">*</font>宴會參加費用：</label>
    <div class="col-sm-10"> 
      <input type="text" class="number" id="banquet_fee" size="10" maxlength="10" name="banquet_fee" value="<?php echo ($row_data['banquet_fee']);?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="first_paper"> <font color="red">*</font>一篇論文費用：</label>
    <div class="col-sm-10"> 
      <input type="text" class="number" id="first_paper" size="10" maxlength="10" name="first_paper" value="<?php echo $row_data['first_paper'];?>" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="second_paper">二篇以上論文費用(含)：</label>
    <div class="col-sm-10">
      <input type="text" class="number" id="second_paper" size="10" maxlength="10" name="second_paper" value="<?php echo $row_data['second_paper'];?>">
    </div>
  </div>
  <?php if ($year<=$time_data['job_end_date']){?>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
       <input type="submit" class="btn btn-primary" name="submit" value="修改">
    </div>
  </div>
  <?php }?> 
</form>
</div>
</body>
<?php require_once('../base_footer.php')?>