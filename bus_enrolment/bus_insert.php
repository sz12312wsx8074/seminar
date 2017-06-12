 <?php require_once('../base_home.php');
 
if(!isset($_SESSION['re_mail'])){
	echo "<script>alert('請先登入!');</script>";
	exit ("<script>location.href='/seminar/login/login.php?th=$th'</script>");
	return;
}

$re_mail = $_SESSION['re_mail'];
 
$query_invite = "SELECT * FROM invite where in_re_mail='$re_mail' AND in_whatInvite = 'seminar'";
$invite = mysqli_query($link,$query_invite);
$row_invite = mysqli_fetch_assoc($invite);
$invite_sum = mysqli_num_rows($invite);

$time = "SELECT * FROM important_date where where_from = '3'";
$time_data = mysqli_query($link,$time);
$row_time = mysqli_fetch_assoc($time_data);
$start_date = $row_time['date_date'];


$date=date_create("$start_date");
date_sub($date,date_interval_create_from_date_string("7 days"));
$end_time = date_format($date,"Y-m-d");
$now = date("Y-m-d");

if($end_time<$now){
	echo "<script>alert('已超過報名時間!');</script>";
	exit ("<script>location.href='/seminar/bus_enrolment/bus_enrolment_index.php?th=$th'</script>");
}

if($invite_sum==0){
	echo "<script>alert('請先報名研討會!');</script>";
	exit ("<script>location.href='/seminar/login/invite.php?th=$th'</script>");
}

$phone = $row_invite['in_phone'];
$name = $row_invite['in_name'];

$query_bus = "SELECT * FROM shuttle_bus order by sb_line DESC";
$bus = mysqli_query($link,$query_bus) or die(mysqli_error());
$row_bus = mysqli_fetch_assoc($bus);
$bus_sum = mysqli_num_rows($bus);

if(isset($_POST['submit'])){
	$sum = 0;
	do{
		$sum = $sum+(int)$_POST[$row_bus['sb_no']];
	}while($row_bus = mysqli_fetch_assoc($bus));
	
	if($sum==0){
		echo "<script>alert('請選擇班次');</script>";
		exit("<meta http-equiv=REFRESH CONTENT=0.1;url=bus_insert.php?th=$th>");
	}elseif($sum>$row_invite['in_people']){
		echo "<script>alert('登車總人數不可超過報名人數');</script>";
		exit("<meta http-equiv=REFRESH CONTENT=0.1;url=bus_insert.php?th=$th>");
	}else{
		$bus = mysqli_query($link,$query_bus) or die(mysqli_error());
		$row_bus = mysqli_fetch_assoc($bus);
		do{
			$no = $_POST[$row_bus['sb_no']];
			if($no!='0'){
				$sqlIns = sprintf("INSERT INTO bus_enrolment (sb_no,re_mail,number) values (%s,'$re_mail','$no')"
				,'"'.$row_bus['sb_no'].'"');
				$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
			}
		}while($row_bus = mysqli_fetch_assoc($bus));
		if($sqlI){
			echo "<script>alert('已新增');</script>";
			exit("<meta http-equiv=REFRESH CONTENT=0.1;url=bus_enrolment_index.php?th=$th>");
		}
	}
}

?>


<html>
<div id=content class="col-sm-9"> 
<form method="POST" action="bus_insert.php?th=<?php echo $th ?>">


<h1>登記接駁車</h1>


<hr>

<div class="controls controls-row">
	姓名
	<input type="text" class="span3" name="name" id="name"  value="<?php echo $name ?>" disabled>
	電話
	<input type="text" class="span3" name="phone" id="phone" value="<?php echo $phone ?>" disabled>
</div>



<br>
<h4><b>＊請選擇班次</h4>
<br>
<table class="table table-striped table-condensed table-hover" >
<thead>
	<tr>
		<th>登車人數</th>
		<th>班次</th>
		<th>單程 / 來回</th>
		<th>行程</th>
		<th>回程時間</th>
	</tr>
</thead>
<tbody>
	<?php
	if($bus_sum<1){ ?>
		<tr>
			<td colspan="5" >目前還沒有接駁車</td>
		</tr><?php 
	}else{ 
		do{  ?>
		<tr>
			<td><input size="1" type="text" name="<?php echo $row_bus['sb_no']; ?>" id="<?php echo $row_bus['sb_no']; ?>"  value="0"></td>
			<td><?php echo $row_bus['sb_shift'];?></td>
			<td><?php echo $row_bus['sb_line'];?></td>
			<td><?php echo $row_bus['sb_explanation'];?></td>  
			<td><?php echo $row_bus['sb_time'];?></td>
		</tr>
	<?php }while($row_bus = mysqli_fetch_assoc($bus));
	} ?>
</tbody>

<tr>
	<td><b>總報名人數</td>
	<td><b><?php echo $row_invite['in_people']; ?></td>
</tr>

</table>

<br>


<hr>



<div class="form-actions">
	<input type="submit" class="btn btn-primary" name="submit" value="送出">
	<input type="button" class="btn btn-success" name="cancel" id="cancel" value="取消" onClick="self.location='bus_enrolment_index.php?th=<?php echo $th ?>'">
</div>
</form>



</form>
</div>
</html>

<?php require_once('../base_footer.php');?>