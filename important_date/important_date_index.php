<?php require_once('../base_home.php');

if(!isset($_SESSION)){  //開啟session功能，要是已經開啟就不用在開
    session_start();
}

if(isset($_POST['dele'])){
	$query_Delete = "SELECT * FROM important_date";
	$Delete = mysqli_query($link, $query_Delete) or die (mysqli_error());
	$row_Delete = mysqli_fetch_assoc($Delete);
	$totalRows_Delete = mysqli_num_rows($Delete); 
	
	do{
		if(isset($_POST[$row_Delete['date_no']])){
			$sqlDel = sprintf("DELETE FROM important_date WHERE date_no = '%s'",$row_Delete['date_no']);
			$sqlD = mysqli_query($link, $sqlDel) or die (mysqli_error());
		}
	}while($row_Delete = mysqli_fetch_assoc($Delete));
}

$query_data1 = "SELECT * FROM important_date";
$data1 = mysqli_query($link,$query_data1) or die(mysqli_error());
$row_data1 = mysqli_fetch_assoc($data1);
$row_sum1 = mysqli_num_rows($data1);
$no = $row_data1['date_no'];


if($row_sum1 ==0){
	$sqlIns = sprintf("INSERT INTO important_date (contant) values ('請輸入敘述') ");
	$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
	if($sqlI){
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=important_date_index.php?th=$th>");
	}
}

$query_where = "SELECT * FROM important_date Where where_from=1";
$where = mysqli_query($link,$query_where) or die(mysqli_error());
$where_sum = mysqli_num_rows($where);

$query_paper = "SELECT * FROM time_job";
$data_paper = mysqli_query($link,$query_paper) or die(mysqli_error());
$row_paper = mysqli_fetch_assoc($data_paper);

if($where_sum==0){
	do{
		$time_no = $row_paper['time_no'];
		$start = $row_paper['job_start_date'];
		$end = $row_paper['job_end_date'];
		$name = $row_paper['job_name'];
		if($time_no==5){
			$name = "研討會";
		}
		if($time_no==1 or $time_no==3 or $time_no==5){
			$start_name = $name."開始";
			$end_name = $name."結束";
			$sqlIns_start = sprintf("INSERT INTO important_date (date_content,date_date,where_from) values ('$start_name','$start',1) ");
			$sqlIns_end = sprintf("INSERT INTO important_date (date_content,date_date,where_from) values ('$end_name','$end',1) ");
			$sqlI_start = mysqli_query($link, $sqlIns_start) or die ("MYSQL Error");
			$sqlI_end = mysqli_query($link, $sqlIns_end) or die ("MYSQL Error");
		}
	}while($row_paper = mysqli_fetch_assoc($data_paper));
}

$query_meeting = "SELECT * FROM important_date Where where_from=3";//研討會會議日期
$meeting = mysqli_query($link,$query_meeting) or die(mysqli_error());
$row_meeting = mysqli_fetch_assoc($meeting);
$meeting_sum = mysqli_num_rows($meeting);
if($meeting_sum==0){
	$sqlIns_meeting = sprintf("INSERT INTO important_date (date_content,where_from) values ('研討會會議日期',3) ");
	$sqlI_meeting = mysqli_query($link, $sqlIns_meeting) or die ("MYSQL Error");
}

$date_search = 1;
if(!isset($_GET["page"]) or isset($_POST['back'])){
    $page = 1;  //設定起始頁//isset 在此是判斷後方參數是否存在
    $_SESSION['search'] = "SELECT * FROM important_date order by date_date DESC";
}else{
    $page = intval($_GET["page"]);  //確認頁數只能夠是數值資料   
}


if(isset($_POST['search'])){
	$date_search=0;
	if(empty($_POST['star_date']) and empty($_POST['end_date']) and empty($_POST['keyword'])){ //日期+關鍵字都是空值
		$_SESSION['search'] = "SELECT * FROM important_date order by date_date DESC";
		$date_search=1;
	}
	if(empty($_POST['star_date']) and empty($_POST['end_date']) and !empty($_POST['keyword'])){//關鍵字不是空值
		$key=$_POST['keyword'];
		$_SESSION['search']  = "SELECT * FROM important_date where date_content like '%$key%' order by date_date DESC";
	}
	if(empty($_POST['star_date']) and !empty($_POST['end_date']) and empty($_POST['keyword'])){//只有輸入結束日期
		$end_date=$_POST['end_date'];
		$_SESSION['search'] = "SELECT * FROM important_date where date_date<='$end_date' order by date_date DESC";
	}
	if(!empty($_POST['star_date']) and empty($_POST['end_date']) and empty($_POST['keyword'])){//只有輸入開始日期
		$star_date=$_POST['star_date'];
		$_SESSION['search'] = "SELECT * FROM important_date where date_date>='$star_date' order by date_date DESC";
	}
	if(!empty($_POST['star_date']) and !empty($_POST['end_date']) and empty($_POST['keyword'])){//只有關鍵字是空值的話
		$star_date=$_POST['star_date'];
		$end_date=$_POST['end_date'];
		$_SESSION['search'] = "SELECT * FROM important_date where date_date>='$star_date' and date_date<='$end_date' order by date_date DESC";
	}
	if(empty($_POST['star_date']) and !empty($_POST['end_date']) and !empty($_POST['keyword'])){//用結束日期+模糊查詢
		$end_date=$_POST['end_date'];
		$key=$_POST['keyword'];
		$_SESSION['search'] = "SELECT * FROM important_date where date_date<='$end_date' and ( date_contant like '%$key%') order by date_date DESC";
	}
	if(!empty($_POST['star_date']) and empty($_POST['end_date']) and !empty($_POST['keyword'])){//用開始日期+模糊查詢
		$star_date=$_POST['star_date'];
		$key=$_POST['keyword'];
		$_SESSION['search'] = "SELECT * FROM important_date where date_date>='$star_date' and (date_contant like '%$key%') order by date_date DESC";
	}
	if(!empty($_POST['star_date']) and !empty($_POST['end_date']) and !empty($_POST['keyword'])){
		$star_date=$_POST['star_date'];
		$end_date=$_POST['end_date'];
		$key=$_POST['keyword'];
		$_SESSION['search'] = "SELECT * FROM important_date where date_date>='$star_date' and date_date<='$end_date' and (date_contant like '%$key%') order by date_date DESC";
	}
}


$query_data=$_SESSION['search'];
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$totalRows_data = mysqli_num_rows($data)-1;

$per = 10; //設定每頁顯示項目數量
$pages = ceil($totalRows_data/$per); //計算總頁數
$start = ($page-1)*$per; //每頁起始資料序號,以便分次藉由sql語法去取得資料       
//每點任一分頁便執行取得該頁數的資料筆數
$result = mysqli_query($link, "$query_data limit $start,$per");
$row_data = mysqli_fetch_assoc($result);
$row_sum = mysqli_num_rows($result);




?>




<html>
<div id=content class="col-sm-9">
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>

<form class="navbar-form nav-text" role="search" method="POST" action="important_date_index.php?th=<?php echo $th ?>">
<body>

<div class="page-header">
	<h1>重要日期</h1>
</div>



<?php 
if($row_data1['contant']!='請輸入敘述'){
	echo $row_data1['contant'];
}else{ ?>目前還沒有資料<?php }?><br>
<hr>

<div class="form-group">
	<label for="sel1">日期：</label>
	<input class="form-control"  type="text" name="star_date" id="star_date" size="12" onClick="WdatePicker()"> ~ 
	<input class="form-control" type="text" name="end_date" id="end_date" size="12" onClick="WdatePicker()">
	<label for="sel1">關鍵字：</label>
	<input type="text" class="form-control" id="keyword" name="keyword"  placeholder="請輸入關鍵字">
</div>
	<input type="submit" class="btn btn-default" name="search" value="搜尋">
	<input type="button" class="btn btn-default" id="view" name="view" value="總覽" onClick="self.location='important_date_index.php?th=<?php echo $th ?>'">


<br><br><br>

<table  class="table table-striped  table-hover">
<tr id="title"> 
	<th>項目</th>
	<th>日期</th>
</tr>
<?php

$sum = 0 ;
do { 
	if($row_data['date_no'] != $no and $row_sum !=0 and $row_data['date_date']!=null){ ?>
	<tr>
		<td><?php echo $row_data['date_content'];?></td>
		<td><?php echo $row_data['date_date'];?></td>
	</tr> <?php	
	$sum = $sum+1;
	}
}while($row_data = mysqli_fetch_assoc($result)); 

if($date_search==1 and $sum==0){ ?>
	<tr><td colspan="6" class="data">目前還沒有資料</td></tr> <?php 
}elseif($date_search==0 and $sum==0){ ?>
	<tr><td colspan="6" class="data">查無此資料</td></tr>
<?php	} ?>

</table>

<nav style="text-align:center">
	<ul class="pagination">
	<?php 
		if($totalRows_data>0){
			echo '共 ' . $totalRows_data . ' 筆' . ' - 共 ' . $pages . ' 頁' . "<br>"; ?>
			
			<li><a href="?page=1&th=<?php echo $th ?>"><span aria-hidden="true">&laquo;</span><span class="sr-only">first</span></a></li>
	<?php 
			if($page==1){  //上一頁 ?>
				<li><a href="?page=<?php echo $page ?>&th=<?php echo $th ?>"><span aria-hidden="true">&lt;</span><span class="sr-only">Previous</span></a></li><?php
			}else{ ?>
				<li><a href="?page=<?php echo $page-1 ?>&th=<?php echo $th ?>"><span aria-hidden="true">&lt;</span><span class="sr-only">Previous</span></a></li><?php
			}
			for( $i=1 ; $i<=$pages ; $i++ ) {  //列出分頁
				if ( $page-5 < $i && $i < $page+5 ){ 
					if($i==$page){ ?>
						<li class="active"><a href="?page=<?php echo $i ?>&th=<?php echo $th ?>"><?php echo $i ?></a></li> <?php
					}else{ ?>
						<li><a href="?page=<?php echo $i ?>&th=<?php echo $th ?>"><?php echo $i ?></a></li><?php
					}
				}
			}
			
			if($page==$pages){  //下一頁 ?>
				<li><a href="?page=<?php echo $page ?>&th=<?php echo $th ?>"><span aria-hidden="true">&gt;</span><span class="sr-only">Next</span></a></li><?php
			}else{ ?>
				<li><a href="?page=<?php echo $page+1 ?>&th=<?php echo $th ?>"><span aria-hidden="true">&gt;</span><span class="sr-only">Next</span></a></li><?php
			}?>
			<li><a href="?page=<?php echo $pages ?>&th=<?php echo $th ?>"><span aria-hidden="true">&raquo;</span><span class="sr-only">last</span></a></li>
<?php	} ?>
	</ul>
</nav>



</body>
</form>
</div>
</html>

<?php require_once('../base_footer.php')?>