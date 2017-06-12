<?php require_once('../base_home.php');
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

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
			             or sb_explanation like '%$key%' or sb_line like '%$key%' or sb_time like '%$key%' order by sb_no ASC";
		}else if($_POST['class']=="shift"){
			$query_data = "SELECT * FROM shuttle_bus where sb_shift like '%$key%' order by sb_no ASC";
		}else if($_POST['class']=="explanation"){
			$query_data = "SELECT * FROM shuttle_bus where sb_explanation like '%$key%' order by sb_no ASC";
		}else if($_POST['class']=="line"){
			$query_data = "SELECT * FROM shuttle_bus where sb_line like '%$key%' order by sb_no ASC";
		}else if($_POST['class']=="time"){
			$query_data = "SELECT * FROM shuttle_bus where sb_time like '%$key%' order by sb_no ASC";
		}
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
<form class="form-horizontal form-text-size" role="form" method="POST" action="shuttle_bus.php?th=<?php echo $th; ?>">
<h1>接駁車</h1>
<hr>
<div class="topnav">
<?php if($button_on){ ?>
	<input type="button" class="btn btn-primary" name="insert" id="insert" value="新增" onclick="self.location='shuttle_bus_insert.php?th=<?php echo $th; ?>'">
	<a class="label label-pill label-success" href="/seminar/bus_enrolment/bus_enrolment.php?th=<?php echo $th; ?>">接駁車現況</a>
<?php } ?>
	</div>
<br>


<table class="table table-striped table-hover">
  <thead>
<tr>
	<th>班次</th>
	<th>說明</th>
	<th>單程 / 來回</th>
	<th>發車時間</th>
	<th>修改</th>
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
	
		<td class="down-button">
	<?php if($button_on){ ?>
		<input type="button" class="btn btn-info" name="update" id="update" value="修改" onClick="self.location='shuttle_bus_update.php?sb_no=<?php echo $row_data["sb_no"]; ?>&th=<?php echo $th; ?>'"></td>
	<?php } ?>
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