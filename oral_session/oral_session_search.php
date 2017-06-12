<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~

$query_data = "SELECT * FROM oral_session where os_no=1";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

?>


<html>
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<!--class="form-control"-->
<form method="POST" action="oral_session.php?th=<?php echo $th; ?>" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">
<h1>發表議程</h1>
<hr>
<?php if($totalRows_data==0){ 
	 echo "目前還沒有資料";
}else{ ?>
	
	<?php if($row_data['os_pdf']!=null){ ?>
		<label class="control-label" >PDF</label><br>
		<button type="button" class="btn btn-link btn-sm" ><a Target="_blank" href="../file/<?php echo $th."/".$row_data['os_pdf']; ?>">檔案預覽</a></button>　
		<button type="button" class="btn btn-link btn-sm" ><a href="../file/downloadfile.php?th=<?php echo $th; ?>&file=<?php echo $row_data['os_pdf']; ?>">檔案下載</a></button>
		<br>
	<?php }
	if($row_data['os_word']!=null){ ?>
		<label class="control-label" >Word</label><br>
		<button type="button" class="btn btn-link btn-sm" ><a href="../file/downloadfile.php?th=<?php echo $th; ?>&file=<?php echo $row_data['os_word']; ?>">檔案下載</a></button>
	<?php } ?>
<?php } ?>

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php') ?>