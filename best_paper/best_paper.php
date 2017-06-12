<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM best_paper ";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);
$bp_no = $row_data['bp_no'];

?>


<html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="jquery.twzipcode.min.js"></script>
<link rel="stylesheet" href="zip.css">
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<!--class="form-control"-->
<form method="POST" action="best_paper.php?th=<?php echo $th; ?>" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">

<h1>最佳論文獎</h1>
<hr>
<?php if($totalRows_data==0){ ?>
	<table>
	<tr>
		<td>目前還沒有資料
		<br><br>
<?php if($button_on){ ?>
		<input type="button" class="btn btn-primary" name="insert" id="insert" value="新增最佳論文獎" onClick="self.location='best_paper_insert.php?th=<?php echo $th; ?>'"></td>
<?php } ?>
	</tr>
	</table>
<?php 
}else{ ?> 
	<table>
		<tr>
			<td style="word-break:break-all; word-wrap:break-all;"><?php echo $row_data['bp_content']; ?></td>
		</tr>
		<tr>
			<th colspan="2"><br>
				<?php if($row_data['bp_pdf']!=null){ ?>
					PDF<br>
				<a Target="_blank" href="../file/<?php echo $th ?>/<?php echo $row_data['bp_pdf']; ?>">檔案預覽</a>
				<a href="../file/downloadfile.php?th=<?php echo $th ?>&file=<?php echo $row_data['bp_pdf']; ?>">檔案下載</a><br>
				<?php } ?>
			</th>
		</tr>
	</table>
	<br>
<?php if($button_on){ ?>
	<input type="button" class="btn btn-primary" name="update" id="update" value="修改" onClick="self.location='best_paper_update.php?bp_no=<?php echo $bp_no; ?>&th=<?php echo $th; ?>'">
	<br>
<?php } ?>
<?php } ?>

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>