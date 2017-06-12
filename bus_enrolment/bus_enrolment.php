 <?php require_once('../base_home.php');

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM shuttle_bus";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);
	



?>




<html>
<div id=content class="col-sm-9"> 
<form method="POST" action="bus_enrolment.php?th=<?php echo $th ?>">
<h1>登記接駁車</h1>
<hr>
<div class="topnav">
	<?php if($button_on){ ?>
		<a class="label label-pill label-success" href="/seminar/shuttle_bus/shuttle_bus.php?th=<?php echo $th; ?>">接駁車</a>
	<?php } ?>
</div>
<br>

<table class="table table-striped  table-hover">
<thead>
	<tr>
		<th  style="text-align:center;">人數</th>
		<th  style="text-align:center;">班次</th>
		<th style="text-align:center;">單程 / 來回</th>
		<th  style="text-align:center;">行程</th>
		
		<th  style="text-align:center;">名單</th>
	<tr>
</thead>

<?php 
do{ 
	$sb_no = $row_data['sb_no'];
	$query = "SELECT * FROM bus_enrolment where sb_no = '$sb_no'";
	$data_many = mysqli_query($link,$query) or die(mysqli_error());
	$many_data = mysqli_fetch_assoc($data_many);
	$sum = 0;
	do{
		$sum = $sum+(int)$many_data['number'];
	}while($many_data = mysqli_fetch_assoc($data_many));
?>

<tbody>
	<tr>
		<td  style="text-align:center;"><?php echo $sum ?></td>
		<td  style="text-align:center;"><?php echo $row_data['sb_shift']; ?></td>
		<td  style="text-align:center;"><?php echo $row_data['sb_line']; ?></td>
		<td  style="text-align:center;"><?php echo $row_data['sb_explanation']; ?></td>
		<td  style="text-align:center;"><input type="button" name="update" id="update" value="查看名單" class="btn btn-primary" onClick="window.open('bus_list.php?sb_no=<?php echo $sb_no;?>&th=<?php echo $th ?>','查看名單',config='height=600,width=500')"></td>
	</tr>
</tbody>
<?php }while($row_data = mysqli_fetch_assoc($data)); ?> 


</table>



</form>
</div>
</html>


<?php require_once('../base_footer.php');?>