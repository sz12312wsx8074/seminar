 <?php require_once('../seminar_connect.php');

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if($_GET){
	$sb_no=$_GET["sb_no"];
}
	
$query = "SELECT * FROM bus_enrolment where sb_no = '$sb_no'";
$data_many = mysqli_query($link,$query) or die(mysqli_error());
$many_data = mysqli_fetch_assoc($data_many);
$many_sum = mysqli_num_rows($data_many);



?>


<link rel=stylesheet href="/seminar/css/base_home.css">
<html>
<h1 style="text-align:center;">接駁車名單</h1>
<title>接駁車名單</title>
<hr>

<table class="table table-striped  table-hover" method="POST" action="bus_enrolment_index.php?th=<?php echo $th ?>">
<thead>
	<tr>
		<th style="text-align:center;">登記人數</th>
		<th style="text-align:center;">姓名</th>
		<th style="text-align:center;">連絡電話</th>
	<tr>
</thead>

<?php 
if($many_sum==0){ ?>
	<tr><td colspan="3" style="text-align:center;">目前還未有人搭車</td></tr>

</tr>

<?php }
else{
	do{ 
		
		$re_mail = $many_data['re_mail'];
		
		$query_data = "SELECT * FROM invite WHERE in_re_mail = '$re_mail' AND in_whatInvite = 'seminar'";
		$data = mysqli_query($link,$query_data) or die(mysqli_error());
		$row_data = mysqli_fetch_assoc($data);
		$phone = $row_data['in_phone'];
		$name = $row_data['in_name'];
		$people = $many_data['number'];
		
	?>
	<tbody>
		<tr>
			<td style="text-align:center;"><?php echo $people ?></td>
			<td style="text-align:center;"><?php echo $name ?></td>
			<td style="text-align:center;"><?php echo $phone ?></td>
		</tr>
	</tbody>
	<?php }while($many_data = mysqli_fetch_assoc($data_many)); 
} ?> 


</table>


</html>
<br><br>
<?php require_once('../base_footer.php');?>