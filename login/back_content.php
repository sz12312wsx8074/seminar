<?php require_once('../base_home.php');

if (isset($_POST['send'])){
  $class = $_POST['class'];
  $sqlUpd = sprintf("UPDATE page_content SET page_content='%s' WHERE page_name = '$class' ", $_POST['page_content']);
  $sqlU = mysqli_query($link, $sqlUpd) or die('error MYSQL');
  if($sqlU){
      echo "<script>alert('送出成功!');</script>";
      exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=back_content.php?th=$th>");
  }
}

$time_sql = "SELECT * FROM time_job where time_no = 5";
$time_result = mysqli_query($link,$time_sql);
$time_data = mysqli_fetch_assoc($time_result);

$year = date("Y-m-d");

$sql_register = "SELECT * FROM page_content where page_name = 'register'";
$result_register = mysqli_query($link,$sql_register);
$data_register = mysqli_fetch_assoc($result_register);

$sql_login = "SELECT * FROM page_content where page_name = 'login'";
$result_login = mysqli_query($link,$sql_login);
$data_login = mysqli_fetch_assoc($result_login);

$sql_paper = "SELECT * FROM page_content where page_name = 'paper'";
$result_paper = mysqli_query($link,$sql_paper);
$data_paper = mysqli_fetch_assoc($result_paper);

$sql_paper_list = "SELECT * FROM page_content where page_name = 'paper_list'";
$result_paper_list = mysqli_query($link,$sql_paper_list);
$data_paper_list = mysqli_fetch_assoc($result_paper_list);

$sql_paper_update = "SELECT * FROM page_content where page_name = 'paper_update'";
$result_paper_update = mysqli_query($link,$sql_paper_update);
$data_paper_update = mysqli_fetch_assoc($result_paper_update);

$sql_user_update = "SELECT * FROM page_content where page_name = 'user_update'";
$result_user_update = mysqli_query($link,$sql_user_update);
$data_user_update = mysqli_fetch_assoc($result_user_update);

$sql_upload = "SELECT * FROM page_content where page_name = 'upload_insert'";
$result_upload = mysqli_query($link,$sql_upload);
$data_upload = mysqli_fetch_assoc($result_upload);
?>


<div id=content class="col-sm-9">
<h1>編輯說明文字</h1>
<div class="topnav">
<a class="label label-pill label-success" href="/seminar/login/back_member.php?th=<?php echo $th;?>">會員查詢</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_content.php?th=<?php echo $th;?>">編輯說明文字</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_fee.php?th=<?php echo $th;?>">編輯費用</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_sendmail.php?th=<?php echo $th;?>">編輯信件</a>
</div>
<br>

<form class="form-horizontal form-text-size" role="form" method="POST" action="back_content.php?th=<?php echo $th;?>">
  <feildset class="form-group">
    <legend class="back-legend">會員註冊頁面說明文字:</legend> <!--register-->
    <div class="col-sm-10">
      <textarea class="form-control textarea-size" name="page_content" placeholder="編輯說明文字"><?php echo $data_register['page_content'];?></textarea>
    </div>
    <input type="hidden" name="class" value="register">
    <?php if ($year<=$time_data['job_end_date']){?>
    <input type="submit" class="btn btn-primary back-btn" name="send" value="送出">
    <?php }?>
  </feildset>
  <br><br>
</form> 
<form class="form-horizontal form-text-size" role="form" method="POST" action="back_content.php?th=<?php echo $th;?>">
  <feildset class="form-group">
    <legend class="back-legend">會員登入頁面說明文字:</legend>  <!--login-->
    <div class="col-sm-10">
      <textarea class="form-control textarea-size" name="page_content" placeholder="編輯說明文字"><?php echo $data_login['page_content']?></textarea>
    </div>
    <input type="hidden" name="class" value="login">
    <?php if ($year<=$time_data['job_end_date']){?>
    <input type="submit" class="btn btn-primary back-btn" name="send" value="送出">
    <?php }?>
  </feildset>
  <br><br>
</form> 
<form class="form-horizontal form-text-size" role="form" method="POST" action="back_content.php?th=<?php echo $th;?>">
  <feildset class="form-group">
    <legend class="back-legend">上傳論文頁面說明文字:</legend>  <!--paper-->
    <div class="col-sm-10">
      <textarea class="form-control textarea-size" name="page_content" placeholder="編輯說明文字"><?php echo $data_paper['page_content'];?></textarea>
    </div>
    <input type="hidden" name="class" value="paper">
    <?php if ($year<=$time_data['job_end_date']){?>
    <input type="submit" class="btn btn-primary back-btn" name="send" value="送出">
    <?php }?>
  </feildset>
  <br><br>
</form> 
<form class="form-horizontal form-text-size" role="form" method="POST" action="back_content.php?th=<?php echo $th;?>">
  <feildset class="form-group">
    <legend class="back-legend">檔案上傳頁面說明文字:</legend>  <!--upload_insert-->
    <div class="col-sm-10">
      <textarea class="form-control textarea-size" name="page_content" placeholder="編輯說明文字"><?php echo $data_upload['page_content'];?></textarea>
    </div>
    <input type="hidden" name="class" value="user_update">
    <?php if ($year<=$time_data['job_end_date']){?>
    <input type="submit" class="btn btn-primary back-btn" name="send" value="送出">
    <?php }?>
  </feildset>
  <br><br>
</form>
<form class="form-horizontal form-text-size" role="form" method="POST" action="back_content.php?th=<?php echo $th;?>">
  <feildset class="form-group">  
    <legend class="back-legend">論文列表頁面說明文字:</legend> <!--paper_list-->
    <div class="col-sm-10">
      <textarea class="form-control textarea-size" name="page_content" placeholder="編輯說明文字"><?php echo $data_paper_list['page_content'];?></textarea>
    </div>
    <input type="hidden" name="class" value="paper_list">
    <?php if ($year<=$time_data['job_end_date']){?>
    <input type="submit" class="btn btn-primary back-btn" name="send" value="送出">
    <?php }?>
  </feildset>
  <br><br>
</form> 
<form class="form-horizontal form-text-size" role="form" method="POST" action="back_content.php?th=<?php echo $th;?>">
  <feildset class="form-group">  
    <legend class="back-legend">論文修改頁面說明文字:</legend> <!--paper_update-->
    <div class="col-sm-10">
      <textarea class="form-control textarea-size" name="page_content" placeholder="編輯說明文字"><?php echo $data_paper_update['page_content'];?></textarea>
    </div>
    <input type="hidden" name="class" value="paper_update">
    <?php if ($year<=$time_data['job_end_date']){?>
    <input type="submit" class="btn btn-primary back-btn" name="send" value="送出">
    <?php }?>
  </feildset>
  <br><br>
</form> 
<form class="form-horizontal form-text-size" role="form" method="POST" action="back_content.php?th=<?php echo $th;?>">
  <feildset class="form-group">
    <legend class="back-legend">帳號管理頁面說明文字:</legend>  <!--user_update-->
    <div class="col-sm-10">
      <textarea class="form-control textarea-size" name="page_content" placeholder="編輯說明文字"><?php echo $data_user_update['page_content'];?></textarea>
    </div>
    <input type="hidden" name="class" value="user_update">
    <?php if ($year<=$time_data['job_end_date']){?>
    <input type="submit" class="btn btn-primary back-btn" name="send" value="送出">
    <?php }?>
  </feildset>
  <br><br>
</form> 
  
</div>
</body>
<?php require_once('../base_footer.php')?>