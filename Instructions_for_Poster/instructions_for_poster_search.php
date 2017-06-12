<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~

$query_data = "SELECT * FROM instructions_for_poster where ifp_no=1";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data); 

?>


<html>
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<!--class="form-control"-->
<form method="POST" action="instructions_for_poster.php" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">
<h1>論文海報演示說明</h1>
<hr>
<?php if($totalRows_data==0){ ?>
	<?php echo "目前還沒有資料";
}else{ ?> 
	<table>
	<tr>
		<td style="word-break: keep-all">說明：</td>
		<td style="word-break:break-all; word-wrap:break-all;"><?php echo $row_data['ifp_content']; ?></td>
	</tr>
	</table>
	<br>
	<img src="../file/<?php echo $th."/".$row_data['ifp_img']; ?>" width="50%" height="50%" /></text><br>
	<br>
<?php } ?>

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>