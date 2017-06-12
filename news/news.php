<?php require_once('../base_home.php'); 

if(!isset($_SESSION)){  //開啟session功能，要是已經開啟就不用在開
    session_start();
}

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}



if(isset($_POST['dele'])){
	$query_Delete = "SELECT * FROM news";
	$Delete = mysqli_query($link, $query_Delete) or die (mysqli_error());
	$row_Delete = mysqli_fetch_assoc($Delete);
	$totalRows_Delete = mysqli_num_rows($Delete); 
	
	do{
		if(isset($_POST[$row_Delete['news_no']])){
			$file_no = $row_Delete['news_no'];
			$query_file = "SELECT * FROM news_file WHERE news_no ='$file_no'";
			$file = mysqli_query($link,$query_file) or die(mysqli_error());
			$file_data = mysqli_fetch_assoc($file);
			$file_sum = mysqli_num_rows($file);
			if($file_sum!=0){
				do{	
					$file_name = $file_data["file_name"];
					unlink("news_file/" .iconv('UTF-8','BIG5',$file_name));
				} while($file_data = mysqli_fetch_assoc($file));
			}
			$sqlDel = sprintf("DELETE FROM news WHERE news_no = '%s'",$row_Delete['news_no']);
			$sqlD = mysqli_query($link, $sqlDel) or die (mysqli_error());
		}
	}while($row_Delete = mysqli_fetch_assoc($Delete));
	if($sqlD){
		echo "<script>alert('刪除成功');</script>";
	}
}

$news_search=1;
if(!isset($_GET["page"]) or isset($_POST['back'])){
    $page=1;  //設定起始頁//isset 在此是判斷後方參數是否存在
    $_SESSION['search'] = "SELECT * FROM news order by news_date DESC";
}else{
    $page = intval($_GET["page"]);  //確認頁數只能夠是數值資料   
}


if(isset($_POST['search'])){
	$news_search=0;
	if(empty($_POST['star_date']) and empty($_POST['end_date']) and empty($_POST['keyword'])){ //日期+關鍵字都是空值
		$news_search=1;
		$_SESSION['search'] = "SELECT * FROM news order by news_date DESC";
	}
	if(empty($_POST['star_date']) and empty($_POST['end_date']) and !empty($_POST['keyword'])){//關鍵字不是空值
		$key=$_POST['keyword'];
		$_SESSION['search']  = "SELECT * FROM news where news_contant like '%$key%' or news_title like '%$key%' order by news_date DESC";
	}
	if(empty($_POST['star_date']) and !empty($_POST['end_date']) and empty($_POST['keyword'])){//只有輸入結束日期
		$end_date=$_POST['end_date'];
		$_SESSION['search'] = "SELECT * FROM news where news_date<='$end_date' order by news_date DESC";
	}
	if(!empty($_POST['star_date']) and empty($_POST['end_date']) and empty($_POST['keyword'])){//只有輸入開始日期
		$star_date=$_POST['star_date'];
		$_SESSION['search'] = "SELECT * FROM news where news_date>='$star_date' order by news_date DESC";
	}
	if(!empty($_POST['star_date']) and !empty($_POST['end_date']) and empty($_POST['keyword'])){//只有關鍵字是空值的話
		$star_date=$_POST['star_date'];
		$end_date=$_POST['end_date'];
		$_SESSION['search'] = "SELECT * FROM news where news_date>='$star_date' and news_date<='$end_date' order by news_date DESC";
	}
	if(empty($_POST['star_date']) and !empty($_POST['end_date']) and !empty($_POST['keyword'])){//用結束日期+模糊查詢
		$end_date=$_POST['end_date'];
		$key=$_POST['keyword'];
		$_SESSION['search'] = "SELECT * FROM news where news_date<='$end_date' and ( news_contant like '%$key%' or news_title like '%$key%') order by news_date DESC";
	}
	if(!empty($_POST['star_date']) and empty($_POST['end_date']) and !empty($_POST['keyword'])){//用開始日期+模糊查詢
		$star_date=$_POST['star_date'];
		$key=$_POST['keyword'];
		$_SESSION['search'] = "SELECT * FROM news where news_date>='$star_date' and (news_contant like '%$key%'or news_title like '%$key%') order by news_date DESC";
	}
	if(!empty($_POST['star_date']) and !empty($_POST['end_date']) and !empty($_POST['keyword'])){
		$star_date=$_POST['star_date'];
		$end_date=$_POST['end_date'];
		$key=$_POST['keyword'];
		$_SESSION['search'] = "SELECT * FROM news where news_date>='$star_date' and news_date<='$end_date' and (news_contant like '%$key%' or news_title like '%$key%') order by news_date DESC";
	}
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
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>

<form class="navbar-form nav-text" role="search" method="POST" action="news.php?page=1&th=<?php echo $th ?>">

<h1>最新消息</h1>
<hr>
<?php 
if($button_on){ ?>
	<input type="button" class="btn btn-primary" name="insert" id="insert" value="新增" onclick="self.location='news_insert.php?th=<?php echo $th ?>'">
	<input type="button" class="btn btn-primary" name="dele" id="dele" value="刪除選取" onClick="delete_Case(false, '')" />
<?php } ?>
</P>
<br>

<div class="form-group">
	<label for="sel1">日期：</label>
	<input class="form-control"  type="text" name="star_date" id="star_date" size="12" onClick="WdatePicker()"> ~ 
	<input class="form-control" type="text" name="end_date" id="end_date" size="12" onClick="WdatePicker()">
	<label for="sel1">關鍵字：</label>
	<input type="text" class="form-control" id="keyword" name="keyword"  placeholder="請輸入關鍵字">
</div>
	<input type="submit" class="btn btn-default" name="search" value="搜尋">
	<input type="button" class="btn btn-default" id="view" name="view" value="總覽" onClick="self.location='news.php?th=<?php echo $th ?>'">
	


<br><br><br>







<table class="table table-striped  table-hover">
<tr id="title">
	<th>選取</th>
	<th>日期</th>
	<th>標題</th>
	<th>修改</th>
	<th>刪除</th>
</tr>
<?php $sum = 0 ;
do{	
	if($row_sum!=0){?>
		<tr>	
			<?php 
			if($button_on){ ?>
				<td><input class="choose" type="checkbox" name="<?php echo $row_data['news_no']; ?>" /></td>
			<?php }else{ ?>
				<td></td>
			<?php } ?>
			<td><?php echo $row_data['news_date'];?></td> <?php 
			if(mb_strlen($row_data['news_title'],'utf-8')<=50){?>
				<td><a href="news_select.php?news_no=<?php echo $row_data["news_no"]; ?>&th=<?php echo $th ?>" ><?php echo $row_data['news_title']; ?></a></td> <?php 
			}else{ ?>
				<td><a href="news_select.php?news_no=<?php echo $row_data["news_no"]; ?>" ><?php echo substr($row_data['news_title'], 0, 45); ?></a></td> <?php
			}  
			if($button_on){  ?>
				<td><input type="button" class="btn btn-info" name="news_update" id="news_update" value="修改" onclick="self.location='news_update.php?news_no=<?php echo $row_data["news_no"]; ?>&th=<?php echo $th ?>'"></td>
				<td><input type="button" class="btn btn-info" id="delete" name="delete" value="刪除" onclick="delete_Case(true, '<?php echo $row_data['news_no']; ?>&th=<?php echo $th ?>')"></td>
			<?php }else{ ?>
			<td colspan="2"></td>
			<?php } ?>
		</tr>	
		<?php $sum = $sum +1 ;
	} 
}while($row_data = mysqli_fetch_assoc($result)); 
if($news_search==1 and $sum==0){ ?>
	<tr><td colspan="6" class="data">目前還沒有資料</td></tr>
<?php 
}elseif($news_search==0 and $sum==0){ ?>
	<tr><td colspan="6" class="data">查無此資料</td></tr>
<?php	} ?>
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


<!--改版改這裡~這裡以下都要改成這樣   記得!!!如果用複製的下面那個delete_case路徑要改-->
</div>
<script>
function delete_Case(single, no) {
	var dele = confirm("確定要刪除選取項目？");
	if (dele == true){
		if (single){
			location.href='news_delete.php?news_no='+no;	
		}else{
			document.getElementById('dele').type = 'submit';
		}		
	}
}
</script>

</body>
</html>

<?php require_once('../base_footer.php')?>
