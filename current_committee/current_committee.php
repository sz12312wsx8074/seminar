<?php require_once('../base_home.php'); 

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

mysqli_select_db($link,$database);

if(!isset($_SESSION)){  //開啟session功能，要是已經開啟就不用在開
	session_start();
}
$search=0;
$sn = 'seminar_'.$th;
if(!isset($_GET["page"])){
	$page=1;  //設定起始頁//isset 在此是判斷後方參數是否存在
	$_SESSION['search'] = "SELECT * FROM ".$sn.".current_committee as A, seminar_generic_data.committee_list as B WHERE A.cc_email = B.cl_email order by cl_related DESC";
}else{
	$page = intval($_GET["page"]);  //確認頁數只能夠是數值資料                 
}



if(isset($_POST['search'])){
	if(empty($_POST['keyword'])){ //關鍵字是空值
		if($_POST['class']=="all"){
			$_SESSION['search'] = "SELECT * FROM ".$sn.".current_committee as A, seminar_generic_data.committee_list as B WHERE A.cc_email = B.cl_email order by cl_related DESC";
		}
	}
	if(!empty($_POST['keyword'])){//關鍵字不是空值
		$key=$_POST['keyword'];
		if($_POST['class']=="all"){
			$_SESSION['search'] = "SELECT * FROM ".$sn.".current_committee as A, seminar_generic_data.committee_list as B WHERE A.cc_email = B.cl_email and
			(cl_name like '%$key%' or cl_scopes like '%$key%' or cc_email like '%$key%') and cl_history=0 order by cl_related DESC";
		}else if($_POST['class']=="name"){
			$_SESSION['search'] = "SELECT * FROM ".$sn.".current_committee as A, seminar_generic_data.committee_list as B WHERE A.cc_email = B.cl_email and cl_name like '%$key%' and cl_history=0 order by cl_related DESC";
		}else if($_POST['class']=="scopes"){
			$_SESSION['search'] = "SELECT * FROM ".$sn.".current_committee as A, seminar_generic_data.committee_list as B WHERE A.cc_email = B.cl_email and cl_scopes like '%$key%' and cl_history=0 order by cl_related DESC";
		}else if($_POST['class']=="email"){
			$_SESSION['search'] = "SELECT * FROM ".$sn.".current_committee as A, seminar_generic_data.committee_list as B WHERE A.cc_email = B.cl_email and cc_email like '%$key%' and cl_history=0 order by cl_related DESC";
		}
	}
	$contact_search=0;
}else{
	$query_data = "SELECT * FROM ".$sn.".current_committee as A, seminar_generic_data.committee_list as B WHERE A.cc_email = B.cl_email order by cl_related DESC";
	$contact_search=1;
}


if(isset($_POST['dele'])){
	$query_Delete = "SELECT * FROM current_committee";
	$Delete = mysqli_query($link, $query_Delete) or die (mysqli_error());
	$row_Delete = mysqli_fetch_assoc($Delete);
	do{
		if(isset($_POST[$row_Delete['cc_related']])){
			$sqlDel = sprintf("Delete FROM current_committee  WHERE cc_related='%s'", $row_Delete['cc_related']);
			$sqlD = mysqli_query($link, $sqlDel) or die (mysqli_error());
		}
	}while($row_Delete = mysqli_fetch_assoc($Delete));
}

$query_data=$_SESSION['search'];
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$totalRows_data = mysqli_num_rows($data);

$per = 10; //設定每頁顯示項目數量
$pages = ceil($totalRows_data/$per); //計算總頁數
$start = ($page-1)*$per; //每頁起始資料序號,以便分次藉由sql語法去取得資料       
//每點任一分頁便執行取得該頁數的資料筆數
$result = mysqli_query($link, "$query_data limit $start,$per");
$row_data = mysqli_fetch_assoc($result);
$row_sum = mysqli_num_rows($result);

?>


<html>
<body>

<div id=content class="col-sm-9">
<form class="navbar-form nav-text" role="search" method="POST" action="current_committee.php?page=1&th=<?php echo $th; ?>">

<h1>本屆審查委員名單</h1>
<hr>
<div class="topnav">
<?php if($button_on){ ?>
	<input type="button" class="btn btn-primary" name="insert" id="insert" value="新增" onclick="self.location='current_committee_insert.php?th=<?php echo $th; ?>'">	
	<input type="button" class="btn btn-primary" name="dele" id="dele" value="刪除選取" onClick="delete_Case(false, '')" />
	<a class="label label-pill label-success" onClick="window.open('list_related.php?th=<?php echo $th; ?>', '匯入委員名單', config='height=700,width=700')">匯入委員名單</a>&nbsp;&nbsp;
<?php } ?>
	</div>
<br>


  <label for="sel1">搜尋分類：</label>
  <select class="form-control" id="sel1" name="class">
  <option value='all' selected="selected">全部</option>
		<option value='name'>審查委員姓名</option>
		<option value='scopes'>擅長領域</option>
		<option value='email'>Email</option>
  </select>
  <div class="form-group">
    <input type="text" class="form-control" name="keyword" placeholder="Search">
  </div>
  <input type="submit" class="btn btn-default" name="search" value="搜尋">
  <input type="button" class="btn btn-default" id="view" name="view" value="總覽" onClick="self.location='current_committee.php?th=<?php echo $th; ?>'">



<table class="table table-striped table-hover">
<thead>
<tr>
	<th>選取</th>
	<th>審查委員姓名</th>
	<th>擅長領域</th>
	<th>Email</th>
	
	<th class="down-button">修改</th>
	<th class="down-button">刪除</th>
</tr>
</thead>
<tbody>
<?php if ($contact_search == 1 and $row_sum == 0){ ?>
	<tr><td class="data" colspan="8">目前還沒有資料</td></tr>
<?php }else if($contact_search == 0 and $row_sum == 0){ ?>
	<tr><td class="data" colspan="8">查無此資料</td></tr>
<?php } else{	
do{ ?>
<tr>
	<?php 
	// $a = $row_data['cc_related']; 
	// $row_data1 = "SELECT * FROM committee_list WHERE cl_related = '$a' ";
	// $row_data1_data = mysqli_query($link_generic,$row_data1) or die(mysqli_error());
	// $row_datas = mysqli_fetch_assoc($row_data1_data);
	// $row_sums = mysqli_num_rows($row_data1_data);	
	?>
	<td class="choose"><input type="checkbox" name="<?php echo $row_data['cc_related']; ?>"/></td>
	<td><?php echo $row_data['cl_name']."　"; ?></td>
	<td><?php echo $row_data['cl_scopes']."　";?></td>
	<td><?php echo $row_data['cc_email']."　";?></td>
	
	<td>
<?php if($button_on){ ?>
	<input type="button" class="btn btn-info" name="update" id="update" value="修改" onClick="self.location='current_committee_update.php?th=<?php echo $th; ?>&cc_email=<?php echo $row_data["cc_email"]; ?>'"></td>
<?php } ?>		
	<td>
<?php if($button_on){ ?>
	<input type="button" class="btn btn-info" id="delete" name="delete" value="刪除" onclick="delete_Case(true, '<?php echo $row_data['cc_email']; ?>','<?php echo $th ?>')"></td>
<?php } ?>	
</tr>	
<?php } while($row_data = mysqli_fetch_assoc($result)); }?>

</tbody>
</table>

<nav style="text-align:center">
	<ul class="pagination">
	<?php 
		if($totalRows_data!=0){
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
</form>
</div>

<script>
function delete_Case(single, email, th) {
	var dele = confirm("確定要刪除選取項目？");
	if (dele == true){
		if (single){
			location.href='current_committee_delete.php?cc_email='+email+'&th='+th;	
		}else{
			document.getElementById('dele').type = 'submit';
		}
		
	}
}
</script>
</body>
<?php require_once('../base_footer.php')?>