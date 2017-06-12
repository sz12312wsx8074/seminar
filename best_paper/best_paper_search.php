<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~

$query_data = "SELECT * FROM best_paper ";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="jquery.twzipcode.min.js"></script>
<link rel="stylesheet" href="zip.css">
<div id=content class="col-sm-9">
<!--class="form-control"-->
<form class="form-horizontal form-text-size" role="form" method="POST" action="best_paper.php?th=<?php echo $th ?>">

<h1>最佳論文獎</h1>
<hr>
<?php if($totalRows_data==0){ ?>
	<table>
	<tr>
		<td>目前還沒有資料</td>
	</tr>
	</table>
<?php 
}else{ ?> 


	<table>
	<tr>
		<td><?php echo $row_data['bp_content']; ?></td>
	</tr>
	<tr>
		<td colspan="2">
			<?php if($row_data['bp_pdf']!=null){ ?>
				PDF
			
			<a Target="_blank" href="../file/<?php echo $th ?>/<?php echo $row_data['bp_pdf']; ?>">檔案預覽</a>
			<a href="../file/downloadfile.php?th=<?php echo $th ?>&file=<?php echo $row_data['bp_pdf']; ?>">檔案下載</a><br>
			<br>
			<br>
	

    </div>
    </div>
			<?php } ?>			
		</td>
		
	</tr>
	</table>
	<br>


<?php } ?>

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>