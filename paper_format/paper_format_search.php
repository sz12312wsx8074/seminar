<?php require_once('../base_home.php');

$query_data = "SELECT * FROM paper_format";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);

?>

<div id=content class="col-sm-9">

<h1>論文格式</h1>

<form class="form-horizontal form-text-size" method="POST" action="paper_format_search.php?th=<?php echo $th; ?>">
<hr>

<?php if($row_sum == 0){?>
	<table>
		目前還沒有資料
	</table>
<?php }else{?>
	<table>

	<tr><?php echo $row_data['pa_content']; ?></tr>

	</table>

	<h2>Paper form</h2>
	<p>
		Word version：
		<?php if($row_data['pa_ewfile'] == NULL){?>
			English、
		<?php }else{?>
			<a href="../paper_format/paper_format_file/downloadfile.php?pa_ewfile=<?php echo $row_data['pa_ewfile'];?>">English</a>、
		<?php }?>

		<?php if($row_data['pa_cwfile'] == NULL){?>
			中文版
		<?php }else{?>
			<a href="../paper_format/paper_format_file/downloadfile.php?pa_cwfile=<?php echo $row_data['pa_cwfile'];?>">中文版</a>
		<?php }?>
	</p>
	<p>
		PDF version：
		<?php if($row_data['pa_epfile'] == NULL){?>
			English、
		<?php }else{?>
		<a Target="_blank" href="../paper_format/paper_format_file/<?php echo $row_data['pa_epfile'];?>">English</a>、
		<?php }?>
		
		<?php if($row_data['pa_cpfile'] == NULL){?>
			中文版
		<?php }else{?>
			<a Target="_blank" href="../paper_format/paper_format_file/<?php echo $row_data['pa_cpfile'];?>">中文版</a>
		<?php }?>
	</p>
<?php }?>

</form>
<br>

</div> 
</div> <!--wrapper-->
</body>

<?php require_once('../base_footer.php')?>