<?php require_once('../base_home.php');

date_default_timezone_set('Asia/Taipei');
$todate = date("Y-m-d");



if(!isset($_GET["page"]) or isset($_POST['back'])){
    $page=1;  //設定起始頁//isset 在此是判斷後方參數是否存在
    $_SESSION['search'] = "SELECT * FROM sort order by sort_abbreviation ASC";
}else{
    $page = intval($_GET["page"]);  //確認頁數只能夠是數值資料   
}

$query_data = "SELECT * FROM sort order by sort_abbreviation ASC";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$totalRows_data = mysqli_num_rows($data);

$per = 10; //設定每頁顯示項目數量
$pages = ceil($totalRows_data/$per); //計算總頁數
$start = ($page-1)*$per; //每頁起始資料序號,以便分次藉由sql語法去取得資料       
//每點任一分頁便執行取得該頁數的資料筆數
$result = mysqli_query($link, "$query_data limit $start,$per");
$row_data = mysqli_fetch_assoc($result);


$query_data2 = "SELECT * FROM time_job where time_no='5'";
$data2 = mysqli_query($link, $query_data2) or die (mysqli_error());
$row_data2 = mysqli_fetch_assoc($data2);
$totalRows_data2 = mysqli_num_rows($data2);

?>


<html>
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->

<form method="POST" action="sort.php?page=1" >
<h1>本屆論文分類</h1>
<hr>

<?php if($todate < $row_data2['job_start_date'] or $row_data2['job_start_date']=="" ){ ?>
	<input type="button" class="btn btn-primary" name="insert" id="insert" value="新增本屆分類" onClick="window.open('sort_insert.php?th=<?php echo $th; ?>', '新增本屆分類', config='height=300,width=500')">
	<input type="button" class="btn btn-primary" name="import" id="import" value="從總分類匯入" onClick="window.open('all_sort.php?th=<?php echo $th; ?>', '從總分類匯入', config='height=600,width=600')">
	<br><br>
<?php } ?>


<table class="table table-striped table-hover" >
	<thead>
		<tr>
			<td></td>
			<th>分類代碼</th>
			<th>分類名稱</th>
			<?php if($todate < $row_data2['job_start_date']){ ?>
				<th>修改</th>
				<th>刪除</th>
			<?php } ?>
		</tr>
	</thead>
	<?php if($totalRows_data==0){ ?>
		<?php if($todate < $row_data2['job_start_date']){ ?>
			<tr><td colspan="5" class="data">目前還沒有資料</td></tr>
		<?php }else{ ?>
			<tr><td colspan="3" class="data">目前還沒有資料</td></tr>
		<?php } ?>
	<?php }else{ ?>
		<?php $i=0;
		do{ $i++?>
		<tr>
			<td><?php echo $i+($page-1)*10; ?></td>
			<td><?php echo $row_data['sort_abbreviation']; ?></td>
			<td><?php echo $row_data['sort_name']; ?></td>
			<?php if($todate < $row_data2['job_start_date'] or $row_data2['job_start_date']=="" ){ ?>
				<td><input type="button" class="btn btn-info" name="update" id="update" value="修改" onClick="window.open('sort_update.php?new_sort_abbreviation=<?php echo $row_data["sort_abbreviation"]."&th=".$th; ?>', '修改', config='height=300,width=500')"></td>
				<td><input type="button" class="btn btn-info" name="delete" id="delete" value="刪除" onclick="delect_Case(true, '<?php echo $row_data['sort_abbreviation']."&th=".$th; ?>')"></td>
			<?php } ?>
		</tr>
		<?php }while($row_data = mysqli_fetch_assoc($result)); 
	} ?>
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
function delect_Case(single, sort_abbreviation) {
	var dele = confirm("確定要刪除此分類？");
	if (dele == true){
		if (single){
			location.href='sort_delete.php?sort_abbreviation='+sort_abbreviation;
		}else{
			document.getElementById('dele').type = 'submit';
		}
	}
}
</script>

</body>
<?php require_once('../base_footer.php')?>