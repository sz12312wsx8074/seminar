<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM poster_session where ps_no=1";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data); 

?>


<html>
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<!--class="form-control"-->
<form method="POST" action="poster_session.php?th=<?php echo $th; ?>" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">
<h1>海報議程</h1>
<hr>
<?php if($totalRows_data==0){ ?>
	<?php echo "目前還沒有資料";?>
	<br><br>
	<?php if($button_on){ ?>
		<input type="button" class="btn btn-primary" name="insert" value="新增海報議程" onClick="self.location='poster_session_insert.php?th=<?php echo $th; ?>'">
	<?php } ?>
<?php }else{ ?>
	<?php if($row_data['ps_pdf']!=null){ ?>
		<label class="control-label" >PDF</label><br>
		<a Target="_blank" href="../file/<?php echo $th."/".$row_data['ps_pdf']; ?>">檔案預覽</a>
		<a href="../file/downloadfile.php?th=<?php echo $th; ?>&file=<?php echo $row_data['ps_pdf']; ?>">檔案下載</a>
		<br>
	<?php } ?>
	<?php if($row_data['ps_word']!=null){ ?>
		<label class="control-label" >Word</label><br>
		<a href="../file/downloadfile.php?th=<?php echo $th; ?>&file=<?php echo $row_data['ps_word']; ?>">檔案下載</a>
		<br>
	<?php } ?>
	<br><br>
	<?php if($button_on){ ?>
		<input type="button" class="btn btn-primary" name="update" id="update" value="修改" onClick="self.location='poster_session_update.php?th=<?php echo $th; ?>'"><br>
	<?php } ?>
<?php } ?>

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>