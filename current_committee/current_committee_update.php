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


if($_GET){
	$cc_email= $_GET['cc_email'];
}

if(isset($_POST['update'])){
	if(empty($_POST['cl_name']) or empty($_POST['cl_scopes'])){
		echo "<script>alert('資料不齊全，請重新輸入。');</script>";
	}else{
		$upd = $_POST['upd'];
		$sqlUpd = sprintf("UPDATE committee_list SET cl_name='%s', cl_scopes='%s' WHERE cl_email = '%s'"
			,$_POST['cl_name'],$_POST['cl_scopes'],$cc_email);		

		$sqlU = mysqli_query($link_generic, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
			echo "<script>alert('已修改!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=1;url=current_committee.php?th=$th>");
		}
	}	
}

$query_data = "SELECT * FROM committee_list where cl_email='$cc_email' ";
$data = mysqli_query($link_generic,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
?>


<html>
<div id=content class="col-sm-9">
<form class="form-horizontal form-text-size"  method="POST" action="current_committee_update.php?cc_email=<?php echo $cc_email; ?>&th=<?php echo $th; ?>">

<h1>修改審查委員</h1>
<hr>
<input type="hidden" id="upd" name="upd" value="<?php echo $row_data['cl_email'] ;?>">

<div class="form-group">
<?php if(empty($_POST['cl_name'])){ ?> 
<label class="control-label col-sm-2"><font color="red">*</font> <?php } ?>
審查委員姓名：</label>
<div class="col-sm-10"><input class="input" type="text" id="cl_name" name="cl_name" value="<?php echo $row_data['cl_name'] ;?>" required/><br><br>
</div>
</div>

<div class="form-group">
<?php if(empty($_POST['cl_scopes'])){ ?> 
<label class="control-label col-sm-2"><font color="red">*</font> <?php } ?>
擅長領域：</label>
<div class="col-sm-10"><textarea class="input"  id="cl_scopes" name="cl_scopes" required/><?php echo $row_data['cl_scopes'] ;?></textarea><br> <br>
</div>
</div>
<div class="col-sm-10 col-sm-offset-2">
<input type="submit" class="btn btn-primary" name="update" id="update" value="修改">
</div>
</form>
</div>
</body>
<?php require_once('../base_footer.php')?>
