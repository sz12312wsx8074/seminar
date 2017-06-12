<?php require_once('../base_home.php');

$id = $_GET["id"];
?>


<div id=content class="col-sm-9">
<h1>忘記密碼?</h1>  
<form class="form-horizontal form-text-size" role="form" method="POST" action="reset_pwd_run.php?th=<?php echo $th; ?>">
  <input type="hidden" name="id" value="<?php echo $id ?>">
  <input type="hidden" name="source" value="reset_pwd">
  <div class="form-group">
    <label class="control-label col-sm-2" for="pwd"><font color="red">*</font>密碼：</label>
    <div class="col-sm-10">
      <input type="password" id="pwd" name="pwd" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-2" for="secpwd"><font color="red">*</font>確認密碼：</label>
    <div class="col-sm-10">
      <input type="password" id="secpwd" name="secpwd" required>
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" class="btn btn-primary" name="send" value="送出" />
    </div>
  </div>
</form>
</div>

<?php require_once('../base_footer.php')?>