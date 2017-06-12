<?php require_once('../base_home.php');
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

mysqli_select_db($link,$database);
$query_data = "SELECT * FROM committee_list order by cl_related DESC";
$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);


if(isset($_POST['submit'])){
	if ($_POST['cl_name'] == "" or $_POST['cl_scopes'] == "" or $_POST['cc_email'] == ""){
			echo "<script>alert('資料不齊全，請重新輸入。');</script>";
	}else{	
		
		$sqlIns = sprintf('INSERT INTO committee_list(cl_name, cl_scopes,cl_email) values (%s, %s,%s)',
		'"'.$_POST['cl_name'].'"', '"'.$_POST['cl_scopes'].'"','"'.$_POST['cc_email'].'"');
		$sqlI = mysqli_query($link_generic, $sqlIns) or die('error MYSQL');
		if($sqlI){
			$sqlIns2 = sprintf("INSERT INTO current_committee (cc_email,cc_related) values (%s,%s)",'"'.$_POST['cc_email'].'"','"'.$_POST['re'].'"');
			$sqlI2 = mysqli_query($link, $sqlIns2) or die ("MYSQL Error");
			if($sqlI2){
			echo "<script>alert('已新增!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=current_committee.php?th=$th>");
			}			
		}
	}		
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="jquery.twzipcode.min.js"></script>
<link rel="stylesheet" href="zip.css">
<div id=content class="col-sm-9">
<form class="form-horizontal form-text-size" role="form" method="POST" action="current_committee_insert.php?th=<?php echo $th; ?>">

<h1>新增本屆審查委員</h1>
<hr>
<input type="hidden" id="re" name="re" value="<?php echo $row_data['cl_related']+1 ;?>">

 <div class="form-group">
    <label class="control-label col-sm-2" for="cl_name"><font color="red">*</font>審查委員姓名：</label>
    <div class="col-sm-10">
      <input type="cl_name" id="cl_name" name="cl_name" size="30" <?php if(!empty($_POST['cl_name'])){ echo "value='".$_POST['cl_name']."'";}?> required>
    </div>
  </div>

 <div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>擅長領域：</label>
	<div class="col-sm-10">
		<textarea size="30"  id="cl_scopes" name="cl_scopes"><?php if(!empty($_POST['cl_scopes'])){ echo $_POST['cl_scopes']; } ?></textarea>
	</div>
</div>
  
<div class="form-group">
    <label class="control-label col-sm-2" for="cc_email"><font color="red">*</font>Email：</label>
    <div class="col-sm-10">
      <input type="email" id="cc_email" name="cc_email" size="30" <?php if(!empty($_POST['cc_email'])){ echo "value='".$_POST['cc_email']."'";}?> required>
    </div>
  </div>


<div class="form-group">
	<div class="col-sm-10 col-sm-offset-2">
		<input id="insert"  class="btn btn-primary" type="submit" name="submit" value="送出"/>
	</div>
</div>
  
</form>
<br>
</div>
</body>

<?php require_once('../base_footer.php')?>