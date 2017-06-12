<?php require_once('../base_home.php');

if(!isset($_SESSION['re_mail'])){
	echo "<script>alert('請先登入!');</script>";
	exit ("<script>location.href='/seminar/login/login.php?th=$th'</script>");
	return;
}

$re_mail = $_SESSION['re_mail'];

$query_data = "SELECT * FROM bus_enrolment where re_mail = '$re_mail' ";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);

$query_invite = "SELECT * FROM invite where in_re_mail='$re_mail' AND in_whatInvite = 'seminar'";
$invite = mysqli_query($link,$query_invite);
$row_invite = mysqli_fetch_assoc($invite);
$in_people = $row_invite['in_people'];

$sum = 0;
do{
	$sum = $sum+(int)$row_data['number'];
}while($row_data = mysqli_fetch_assoc($data));

$lost = (int)$in_people - $sum;

?>




<html>
<div id=content class="col-sm-9"> 
<form class="navbar-form nav-text" method="POST" action="bus_enrolment_index.php?th=<?php echo $th ?>">
<h1>登記接駁車</h1>
<br>
<hr>

<?php if($row_sum!=0){?>
	
	<h4>你目前選擇的行程是:</h4><font color="red">
	<?php 
	if($lost!=0){ ?>
		＊小提醒：有 <?php echo $lost  ?> 人未登記接駁車</font><br><br><?php
	} ?>
	<table class="table table-striped  table-hover" method="POST" action="bus_enrolment_index.php?th=<?php echo $th ?>">
		<thead>
			<tr>
				<th>登車人數</th>
				<th>班次</th>
				<th>單程 / 來回</th>
				<th>行程</th>
				<th>回程時間</th>
			</tr>
		</thead>
		<tbody><?php
		$data = mysqli_query($link,$query_data) or die(mysqli_error());
		$row_data = mysqli_fetch_assoc($data);
		do{ 
			$sb_no = $row_data['sb_no'];
			$query_bus = "SELECT * FROM shuttle_bus where sb_no = '$sb_no' ";
			$bus = mysqli_query($link,$query_bus) or die(mysqli_error());
			$row_bus = mysqli_fetch_assoc($bus);
		?>
			<tr>
				<td><?php echo $row_data['number']; ?></td>
				<td><?php echo $row_bus['sb_shift']; ?></td>
				<td><?php echo $row_bus['sb_line']; ?></td>
				<td><?php echo $row_bus['sb_explanation'];?></td>  
				<td><?php echo $row_bus['sb_time']; ?></td>
			</tr><?php
		}while($row_data = mysqli_fetch_assoc($data));	?>
		
		</tbody>
	</table>
	<div class="topnav">
	<br>
	<?php 
	if($button_on){ ?>
		<a class="label label-pill label-success" href="/seminar/bus_enrolment/bus_update.php?th=<?php echo $th ?>">修改</a>
		<a class="label label-pill label-warning" href="#" onclick="delete_Case('<?php echo $re_mail; ?>&th=<?php echo $th ?>')" >取消登記</a>
	<?php } ?>
		</div>
<?php }elseif($row_sum==0){ ?>
	你目前還未登記接駁<br>
	<div class="topnav">
	<br>
	<?php
	if($button_on){ ?>
		<a class="label label-pill label-success" href="/seminar/bus_enrolment/bus_insert.php?th=<?php echo $th ?>">登記</a>
	<?php } ?>
</div>
	
<?php } ?>

<script>
function delete_Case(mail) {
	var dele = confirm("確定要取消登記？");
	if (dele == true){
		location.href='bus_delete.php?re_mail='+mail;		
	}
}
</script>






</form>
</div>
</html>

<?php require_once('../base_footer.php');?>