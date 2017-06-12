<?php require_once('../base_home.php');
mysqli_select_db($link,$database);

if($_GET){
	$cl_related=$_GET["cl_related"];
}

if(isset($_POST['update'])){
	if(empty($_POST['cl_related']) or empty($_POST['cl_name']) or empty($_POST['cl_scopes']) or empty($_POST['cl_email']) ){
		echo "<script>alert('資料不齊全，請重新輸入。');</script>";
	}else{
		$upd = $_POST['upd'];
		$sqlUpd = sprintf("UPDATE committee_list SET cl_related = '%s' , cl_name = '%s',cl_scopes = '%s' ,cl_email = '%s' WHERE cl_email = '$upd'", $_POST['cl_name'], $_POST['cl_scopes'], $_POST['cl_email']);

		$sqlU = mysqli_query($link_generic, $sqlUpd) or die ("MYSQL Error");
		if($sqlU){
			echo "<script>alert('已修改!!');</script>";
			exit ('<meta http-equiv=REFRESH CONTENT=1;url=committee_list.php>');
		}
	}	
}

$query_data = "SELECT * FROM committee_list where cl_related='$cl_related' ";
$data = mysqli_query($link_generic,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
?>



<div id=content>
<h1>修改審查委員</h1><br>
<form id="form" method="POST" action="committee_list_update.php">

<input type="hidden" id="upd" name="upd" value="<?php echo $row_data['cl_related'] ;?>">

<?php if(empty($_POST['cl_related'])){ ?> <font color="red">*</font> <?php } ?>
屆數：<input  class="input" type="text" id="cl_related" name="cl_related" value="<?php echo $row_data['cl_related'] ;?>" required/><br><br>

<?php if(empty($_POST['cl_name'])){ ?> <font color="red">*</font> <?php } ?>
審查委員姓名：<input  class="input" type="text" id="cl_name" name="cl_name" value="<?php echo $row_data['cl_name'] ;?>" required/><br><br>

<?php if(empty($_POST['cl_scopes'])){ ?> <font color="red">*</font> <?php } ?>
擅長領域：<textarea class="input"  id="cl_scopes" name="cl_scopes" required/><?php echo $row_data['cl_scopes'] ;?></textarea><br> <br>

<?php if(empty($_POST['cl_email'])){ ?> <font color="red">*</font> <?php } ?>
Email：<input class="input" type="text" id="cl_email" name="cl_email" value="<?php echo $row_data['cl_email'] ;?>" required/> <br> <br>

<input id="submit" type="submit" name="update" value="修改">
</form>
<br>
</div>
</body>
<?php require_once('../base_footer.php')?>
