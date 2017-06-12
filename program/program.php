<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM program where prm_no=1";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);
?>


<html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="jquery.twzipcode.min.js"></script>
<link rel="stylesheet" href="zip.css">
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<!--class="form-control"-->

<form method="POST" action="program.php?th=<?php echo $th; ?>" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">
<h1>議程</h1>
<hr>

<?php if($totalRows_data==0){ ?>
	<table>
	<tr>
		<td>目前還沒有資料
		<br><br>
		<?php if($button_on){ ?>
			<input type="button" class="btn btn-primary" name="insert" id="insert" value="新增議程" onClick="self.location='program_insert.php?th=<?php echo $th; ?>'"></td>
		<?php } ?>
	</tr>
	</table>
<?php 
}else{ ?> 
	<table>
		<tr>
			<th style="word-break: keep-all">內文：</th>
			<td style="word-break:break-all; word-wrap:break-all;"><?php echo $row_data['prm_content']; ?></td>
		</tr>
		<tr>
			<th colspan="2"><br>
				<?php if($row_data['prm_pdf']!=null){ ?>
					PDF<br>
				<a Target="_blank" href="../file/<?php echo $th."/".$row_data['prm_pdf']; ?>">檔案預覽</a>
				<a href="../file/downloadfile.php?th=<?php echo $th; ?>&file=<?php echo $row_data['prm_pdf']; ?>">檔案下載</a><br>
				<?php } ?>
				<?php if($row_data['prm_word']!=null){ ?>
					Word<br>
				<button type="button" class="btn btn-link btn-sm" ><a href="../file/downloadfile.php?th=<?php echo $th; ?>&file=<?php echo $row_data['prm_word']; ?>">檔案下載</a></button>
				<?php } ?>
			</th>
		</tr>
	</table>
	<br>
	<?php if($button_on){ ?>
		<input type="button" class="btn btn-primary" name="update" id="update" value="修改" onClick="self.location='program_update.php?th=<?php echo $th; ?>'">
	<?php } ?>
<?php } ?>

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>