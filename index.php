<?php require_once('base_home.php');?>

<?php
$query_data = "SELECT * FROM home";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);


if($row_sum==0){
	$sqlIns = sprintf("INSERT INTO home (home_title,home_contant,
	list_title,list_contant,home_CNfile,home_ENfile) values ('請輸入標題','請輸入敘述','請輸入敘述','請輸入內容','0','0')");
	$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
}


$query_data = "SELECT * FROM home";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);




?>



<html>
<body>
<style type="text/css">p.double {border-style: dashed}</style>
<div id=content class="col-sm-9">
<form class="navbar-form nav-text" method="POST" action="index.php?th=<?php echo $th ?>" enctype="multipart/form-data">

	  <h1>介紹</h1>
	  <hr>
		
	<div class="panel panel-default">
		<div class="panel-heading">
		<div class="container">
			<?php if($row_data['home_title']!='請輸入標題'){ ?>
				<font size="6"><?php echo $row_data['home_title']; ?></font><br>
			<?php } ?>
		</div>
		</div>
		<div class="panel-body">
		<?php if($row_data['home_contant']!='請輸入敘述'){ ?>
			<?php echo $row_data['home_contant']; ?><br><br><br>
		<?php }else{ ?> 目前沒有資料 <?php } ?>
		<hr>
		<?php if($row_data['home_CNfile']!='0' and $row_data['home_ENfile']!='0' ){   ?>
			<font size="2"><span style="font-weight:bold;">檔案下載</span></font><br>
			<a href="file/downloadfile.php?file=<?php echo $row_data['home_CNfile'];?>">中文檔案</a>
			<a href="file/downloadfile.php?file=<?php echo $row_data['home_ENfile'];?>">英文檔案</a>
		<?php } ?>
		</div>
	</div>
	
	<h1>種類</h1>
	<hr>
		
	<div class="panel panel-default">
		<div class="panel-heading">
		  <div class="container">
		  <?php if($row_data['list_title']!='請輸入敘述'){ ?>
			<font size="6"><?php echo $row_data['list_title']; ?></font><br>
		  <?php } ?>
		  </div>
		</div>
		<div class="panel-body">
		<?php if($row_data['list_contant']!='請輸入內容'){ ?>
			<?php echo $row_data['list_contant']; ?>
		<?php }else{ ?> 目前沒有資料 <?php } ?>
		</div>
	</div>

	

	




</div>

</body>
</html>

<?php require_once('base_footer.php');?>