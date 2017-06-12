<?php require_once('../base_home.php');?>

<?php
$query_data = "SELECT * FROM home";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);

$query_time = "SELECT * FROM time_job";
$time = mysqli_query($link,$query_time) or die(mysqli_error());
$row_time = mysqli_fetch_assoc($time);
$time_have = true;
do{
	if($row_time['job_end_date']==null){
		$time_have = false;
	}
}while($row_time = mysqli_fetch_assoc($time));

if($time_have==false){
	exit ("<script>location.href='../insert_time.php?th=$th'</script>");
}


if($row_sum==0){
	$sqlIns = sprintf("INSERT INTO home (home_title,home_contant,
	list_title,list_contant,home_CNfile,home_ENfile) values ('請輸入標題','請輸入敘述','請輸入敘述','請輸入內容','0','0')");
	$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
}


$query_data = "SELECT * FROM home";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);

if($row_data['home_title']=='請輸入標題'&&$row_data['home_contant']=='請輸入敘述'&&$row_data['list_title']=='請輸入敘述'&&$row_data['list_contant']=='請輸入內容'&&$row_data['home_CNfile']=='0'){
	$new = true;
}else{
	$new = false;
}


?>



<html>
<body>
<style type="text/css">p.double {border-style: dashed}</style>
<div id=content class="col-sm-9">
<form class="navbar-form nav-text" method="POST" action="home.php?th=<?php echo $th ?>" enctype="multipart/form-data">

	  <h1>首頁介紹</h1>
	  <hr><?php
	if($button_on){ ?>
		<input type="button" class="btn btn-primary" name="update" id="update" 
		value="<?php if($new){ ?>新增<?php }else{ ?>修改<?php } ?>" onClick="self.location='home_update.php?th=<?php echo $th ?>'">
	<?php } ?>
	<br><br>
	
	
	
	<div class="panel panel-default">
		<div class="panel-heading">
		<div class="container">
			<font size="6"><?php echo $row_data['home_title']; ?></font><br>
		</div>
		</div>
		<div class="panel-body">
		<?php echo $row_data['home_contant']; ?><br><br><br>
		<hr>
		<?php if($row_data['home_CNfile']!='0' and $row_data['home_ENfile']!='0' ){   ?>
			<font size="2"><span style="font-weight:bold;">檔案下載</span></font><br>
			<a href="../file/downloadfile.php?th=<?php echo $th ?>&file=<?php echo $row_data['home_CNfile'];?>">中文檔案</a>
			<a href="../file/downloadfile.php?th=<?php echo $th ?>&file=<?php echo $row_data['home_ENfile'];?>">英文檔案</a>
		<?php } ?>
		</div>
	</div>
	
	<h1>種類</h1>
	<hr>
		
	<div class="panel panel-default">
		<div class="panel-heading">
		  <div class="container">
			<font size="6"><?php echo $row_data['list_title']; ?></font><br>
		  </div>
		</div>
		<div class="panel-body">
			<?php echo $row_data['list_contant']; ?>
		</div>
	</div>

	

	




</div>

</body>
</html>

<?php require_once('../base_footer.php');?>