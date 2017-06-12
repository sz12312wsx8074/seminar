<?php require_once('../base_home.php');

if(isset($_POST['search'])){
	if(empty($_POST['keyword'])){ //關鍵字是空值
		if($_POST['class']=="all"){
			$query_data = "SELECT * FROM shuttle_bus order by sb_no ASC";
		}
	}
	if(!empty($_POST['keyword'])){//關鍵字不是空值
		$key=$_POST['keyword'];
		if($_POST['class']=="all"){
			$query_data = "SELECT * FROM shuttle_bus where (sb_shift like '%$key%' 
			             or sb_explanation like '%$key%' or sb_line like '%$key%' or sb_time like '%$key%' order by sb_no DESC";
		}else if($_POST['class']=="shift"){
			$query_data = "SELECT * FROM shuttle_bus where sb_shift like '%$key%' order by sb_no DESC";
		}else if($_POST['class']=="explanation"){
			$query_data = "SELECT * FROM shuttle_bus where sb_explanation like '%$key%' order by sb_no DESC";
		}else if($_POST['class']=="line"){
			$query_data = "SELECT * FROM shuttle_bus where sb_line like '%$key%' order by sb_no DESC";
		}
		}else if($_POST['class']=="time"){
			$query_data = "SELECT * FROM shuttle_bus where sb_time like '%$key%' order by sb_no DESC";
		}
	
	$contact_search=0;
}else{
	$query_data = "SELECT * FROM shuttle_bus order by sb_no ASC";
	$contact_search=1;
}


$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);
?>


<div id=content class="col-sm-9">
<form method="POST" action="shuttle_bus.php">
<h1>接駁車</h1>
<hr>


<table class="table table-striped table-hover">
  <thead>
<tr>
	<th>班次</th>
	<th>說明</th>
	<th>單程 / 來回</th>
	<th>發車時間</th>

</tr>
</thead>
<tbody>
<?php
if($row_sum>0){
	do { ?>
	<tr>
		<td><?php echo $row_data['sb_shift']."　";?></td>
		<td><?php echo $row_data['sb_explanation']."　";?></td>
		<td><?php echo $row_data['sb_line']."　";?></td>
		<td><?php echo $row_data['sb_time']."　";?></td>
		
		
	</tr>	
	<?php } while($row_data = mysqli_fetch_assoc($data)); 
}else{ ?>
	<tr><td colspan="6">目前還沒有資料</td></tr><?php
} ?>
</tbody>
</table>
<br>
</form>
</div>
<script>

</script>
</body>
<?php require_once('../base_footer.php')?>