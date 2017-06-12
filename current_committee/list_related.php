<?php require_once('../seminar_connect.php');

if(!isset($_SESSION)){  //開啟session功能，要是已經開啟就不用在開
	session_start();
}
$search=0;

if(!isset($_GET["page"])){
	$page=1;  //設定起始頁//isset 在此是判斷後方參數是否存在
	$_SESSION['search'] = "SELECT * FROM committee_list order by cl_related ASC";
}else{
	$page = intval($_GET["page"]);  //確認頁數只能夠是數值資料                 
}


$query_data = "SELECT * FROM committee_list";
$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);


$query_data2 = "SELECT * FROM current_committee order by cc_email ASC";
$data2 = mysqli_query($link, $query_data2) or die (mysqli_error());
$row_data2 = mysqli_fetch_assoc($data2);

$new_cc_related = array();
do{
	array_push($new_cc_related, $row_data2['cc_related']);
}while($row_data2 = mysqli_fetch_assoc($data2));


if(isset($_POST['send'])){
	$query_Send = "SELECT * FROM committee_list";
	$Send = mysqli_query($link_generic, $query_Send) or die (mysqli_error());
	$row_Send = mysqli_fetch_assoc($Send);
	
	do{
		if(isset($_POST[$row_Send['cl_related']])){
			if(in_array($row_Send['cl_related'], $new_cc_related)){
				
			}else{
				$sqlIns = sprintf("INSERT INTO current_committee ( cc_email, cc_related ) values (%s, %s)",'"'.$row_Send['cl_email'].'"','"'.$row_Send['cl_related'].'"');
				$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
			}
		}
	}while($row_Send = mysqli_fetch_assoc($Send));
	exit('<script>window.opener.location.reload(); window.opener=null; window.close();</script>');
}

if(isset($_POST['search'])){
	$key=$_POST['keyword'];
	$_SESSION['search'] = "SELECT * FROM committee_list where cl_name like '%$key%' or cl_scopes like '%$key%' or cl_email like '%$key%'";
	$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
	$row_data = mysqli_fetch_assoc($data);
	$totalRows_data = mysqli_num_rows($data);
	if($totalRows_data==0){
		$search=1;
	}
}

$query_data = $_SESSION['search'];
$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

$per = 10; //設定每頁顯示項目數量
$pages = ceil($totalRows_data/$per); //計算總頁數
$start = ($page-1)*$per; //每頁起始資料序號,以便分次藉由sql語法去取得資料       
//每點任一分頁便執行取得該頁數的資料筆數
$result = mysqli_query($link_generic, "$query_data limit $start,$per");
$row_data = mysqli_fetch_assoc($result); 

?>


<html>
<div id=content class="col-sm-9">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>匯入委員名單</title>
</head>

<body>
<form method="POST" action="list_related.php?th=<?php echo $th; ?>" >
<center><font color="#0000a0"><h2>歷屆審查委員名單</h2></font></center>
<hr>
<div class="form-group">
		<label for="sel1">關鍵字：</label>
		<input type="text" id="keyword" name="keyword"  placeholder="請輸入關鍵字">
		<input type="submit" class="btn btn-default" name="search" id="search" value="搜尋">
	</div>

<?php if($totalRows_data==0){ 
	if($search==1){
		echo "<center>查無此資料</center>";
	}else{
		echo "<center>目前還沒有資料</center>";
	}
}else{ ?>

<table  class="table table-striped table-hover" >
		<thead>
			<tr>
				<th></th>
				<th></th>
				<th>審查委員姓名</th>
				<th>擅長領域</th>
				<th>Email</th>
				<th>修改</th>
			</tr>
		</thead>
		
<tbody>		
<?php $i=0;
do{ $i++?>
<tr>
	<td><input type="checkbox" name="<?php echo $row_data['cl_related']; ?>" id="sen" value="1" /></td>
	<td><?php echo $i;?></td>
	<td><?php echo $row_data['cl_name']; ?></td>
	<td><?php echo $row_data['cl_scopes']; ?></td>
	<td><?php echo $row_data['cl_email']; ?></td>
	<td><input type="button" class="btn btn-info" name="update" id="update" value="修改" onClick="self.location='list_related_update.php?cl_related=<?php echo $row_data["cl_related"]."&th=".$th; ?>'"></td>

</tr>
<?php }while($row_data = mysqli_fetch_assoc($result)); 
} ?>
</tbody>
</table>

<nav style="text-align:center">
	<ul class="pagination">
	<?php 
		if($totalRows_data!=0){
			echo '共 ' . $totalRows_data . ' 筆' . ' - 共 ' . $pages . ' 頁' . "<br>"; ?>
			
			<li><a href="?page=1&th=<?php echo $th; ?>"><span aria-hidden="true">&laquo;</span><span class="sr-only">first</span></a></li>
	<?php 
			if($page==1){  //上一頁 ?>
<li><a href="?page=<?php echo $page."&th=".$th; ?>"><span aria-hidden="true">&lt;</span><span class="sr-only">Previous</span></a></li><?php
			}else{ ?>
<li><a href="?page=<?php echo ($page-1)."&th=".$th; ?>"><span aria-hidden="true">&lt;</span><span class="sr-only">Previous</span></a></li><?php
			}
			for( $i=1 ; $i<=$pages ; $i++ ) {  //列出分頁
				if ( $page-5 < $i && $i < $page+5 ){ 
					if($i==$page){ ?>
						<li class="active"><a href="?page=<?php echo $i."&th=".$th; ?>"><?php echo $i ?></a></li><?php
					}else{ ?>
						<li><a href="?page=<?php echo $i."&th=".$th; ?>"><?php echo $i ?></a></li><?php
					}
				}
			}			


			if($page==$pages){  //下一頁 ?>
<li><a href="?page=<?php echo $page."&th=".$th; ?>"><span aria-hidden="true">&gt;</span><span class="sr-only">Next</span></a></li><?php
			}else{ ?>
<li><a href="?page=<?php echo ($page+1)."&th=".$th; ?>"><span aria-hidden="true">&gt;</span><span class="sr-only">Next</span></a></li><?php
			}?>
<li><a href="?page=<?php echo $pages."&th=".$th; ?>"><span aria-hidden="true">&raquo;</span><span class="sr-only">last</span></a></li>
<?php	} ?>
	</ul>
</nav>



<br>
<center><input type="button" class="btn btn-primary" name="insert" id="insert" value="新增審查委員" onClick="self.location='list_related_insert.php?th=<?php echo $th; ?>'">
<input type="submit" class="btn btn-primary" name="send" id="send" value="匯入至本屆審查委員" /></center>
<br>
</div>

</body>
</form>
</html>