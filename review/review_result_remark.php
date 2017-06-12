<?php require_once('../seminar_connect.php');

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if(isset($_POST['update']) or isset($_POST['update2'])){
	$rr_num = $_POST['rr_num'];
	if(mb_strlen($_POST['rr_remark'],'utf-8')>20){
		echo "<script>alert('字數不可以超過20字');</script>";
	}else{
		$sqlUpd = sprintf("UPDATE review_result SET rr_remark='%s' WHERE rr_num='%s'", $_POST['rr_remark'], $_POST['rr_num']);
		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		if($sqlU){
			if(isset($_POST['update'])){
				echo "<script>alert('已儲存!!');</script>";
			}else{
				echo "<script>alert('已修改!!');</script>";
			}
			exit('<script>window.opener.location.reload(); window.opener=null; window.close();</script>');
		}
	}
}else{
	$rr_num = $_GET["rr_num"];
}


$query_data = "SELECT * FROM review_result where rr_num='$rr_num'";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

?>

<html>
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->

<?php if($row_data['rr_remark']==null){ ?>
	<h1>新增備註</h1>
	<title>新增備註</title>
<?php }else{ ?>
	<h1>修改備註</h1>
	<title>修改備註</title>
<?php } ?>

<hr>
<form method="POST" action="review_result_remark.php?th=<?php echo $th; ?>">

<input type="hidden" name="rr_num" id="rr_num" value="<?php echo $row_data['rr_num'];?>" />

論文編號：<?php echo $row_data['rr_num'];?>
<br><br>
備註：<input type="text" id="rr_remark" name="rr_remark" size="50" maxlength="20" value="<?php echo $row_data['rr_remark'] ?>" />
<br><font color="red">不可超過20字</font>
<br><br><br>
<?php if($row_data['rr_remark']==null){ ?>
	<input type="submit" name="update" id="update" value="儲存" />
<?php }else{ ?>
	<input type="submit" name="update2" id="update2" value="修改" />
<?php } ?>

</form>
<!--改版改這裡~這裡以下都要改成這樣   記得!!!如果用複製的下面那個delete_case路徑要改-->
</div>

</body>
