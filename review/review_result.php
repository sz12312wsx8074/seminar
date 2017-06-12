<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

date_default_timezone_set('Asia/Taipei');
$todate = date("Y-m-d");



$query_data = "SELECT * FROM upload";   //上傳的表
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);

$query_data2 = "SELECT * FROM time_job where time_no='2'" ;   //查「審查時間」
$data2 = mysqli_query($link, $query_data2) or die (mysqli_error());
$row_data2 = mysqli_fetch_assoc($data2);

if(!isset($_GET["page"])){
	$page=1;  //設定起始頁//isset 在此是判斷後方參數是否存在
	$events_search=1;
	$query_data3 = "SELECT * FROM review_result order by rr_score_avg DESC, rr_num ASC";
}else{
	$page = intval($_GET["page"]);  //確認頁數只能夠是數值資料              
}


?>


<html>

<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<h1>審查結果</h1>
<hr>
<form method="POST" action="review_result.php?th=<?php echo $th; ?>" enctype="multipart/form-data">

<?php 
if( $todate >= $row_data2['job_start_date'] and $row_data2['job_start_date']!=null ){ //審查開始ㄌ
	$query_data5 = "SELECT * FROM review_result";   //查審查資料是否有資料了
	$data5 = mysqli_query($link,$query_data5) or die(mysqli_error());
	$totalRows_data5 = mysqli_num_rows($data5);
	if($totalRows_data5==0){
		do{
			$num = $row_data['upsort_num'];
			$query_data4 = "SELECT * FROM review where rw_num='$num'";
			$data4 = mysqli_query($link,$query_data4) or die(mysqli_error());
			$row_data4 = mysqli_fetch_assoc($data4);
			$totalRows_data4 = mysqli_num_rows($data4);
			if($totalRows_data4==2){
				$score_one = $row_data4['rw_score'];
				$recommend_one = $row_data4['rw_recommend'];
				$row_data4 = mysqli_fetch_assoc($data4);
				$score_two = $row_data4['rw_score'];
				$recommend_two = $row_data4['rw_recommend']; 
				$score_avg = ($score_one + $score_two) / 2;
				$sqlIns = sprintf("INSERT INTO review_result(rr_num, rr_paper, rr_sort, rr_score_one, rr_score_two, rr_score_avg, rr_recommend_one, rr_recommend_two) values (%s, %s, %s, %s, %s, %s, %s, %s)",
				'"'.$num.'"', '"'.$row_data['up_paper'].'"', '"'.$row_data['up_sort'].'"', '"'.$score_one.'"', '"'.$score_two.'"', '"'.$score_avg.'"', '"'.$recommend_one.'"', '"'.$recommend_two.'"');
				$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
			}else{
				$sqlIns = sprintf("INSERT INTO review_result(rr_num, rr_paper, rr_sort, rr_score_one, rr_score_two, rr_score_avg, rr_recommend_one, rr_recommend_two) values (%s, %s, %s, %s, %s, %s, %s, %s)",
				'"'.$num.'"', '"'.$row_data['up_paper'].'"', '"'.$row_data['up_sort'].'"', '"'.$row_data4['rw_score'].'"', 0, '"'.$row_data4['rw_score'].'"', '"'.$row_data4['rw_recommend'].'"', '""');
				$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error5");
			}
		}while($row_data = mysqli_fetch_assoc($data));
	}else{
		if(!isset($_GET["page"])){
			do{
				$num = $row_data['upsort_num'];
				$query_data4 = "SELECT * FROM review where rw_num='$num'";
				$data4 = mysqli_query($link,$query_data4) or die(mysqli_error());
				$row_data4 = mysqli_fetch_assoc($data4);
				$totalRows_data4 = mysqli_num_rows($data4);
				if($totalRows_data4==2){
					$score_one = $row_data4['rw_score'];
					$recommend_one = $row_data4['rw_recommend'];
					$row_data4 = mysqli_fetch_assoc($data4);
					$score_two = $row_data4['rw_score'];
					$recommend_two = $row_data4['rw_recommend']; 
					$score_avg = ($score_one + $score_two) / 2;
					$sqlUpd = sprintf("UPDATE review_result SET rr_score_one='%s', rr_score_two='%s', rr_score_avg='%s', rr_recommend_one='%s', rr_recommend_two='%s' WHERE rr_num='%s'", $score_one, $score_two, $score_avg, $recommend_one, $recommend_two, $num);
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
				}else{
					$sqlUpd = sprintf("UPDATE review_result SET rr_score_one='%s', rr_score_two='%s', rr_score_avg='%s', rr_recommend_one='%s', rr_recommend_two='%s' WHERE rr_num='%s'", $row_data4['rw_score'], 0, $row_data4['rw_score'], $row_data4['rw_recommend'], 0, $num);
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
				}
			}while($row_data = mysqli_fetch_assoc($data));
		}
	}
}
	
$query_data3 = "SELECT * FROM review_result order by rr_score_avg DESC, rr_num ASC";   //審查結果總表
$data3 = mysqli_query($link,$query_data3) or die(mysqli_error());
$totalRows_data3 = mysqli_num_rows($data3);
$per = 10; //設定每頁顯示項目數量
$pages = ceil($totalRows_data3/$per); //計算總頁數
$start = ($page-1)*$per; //每頁起始資料序號,以便分次藉由sql語法去取得資料       
//每點任一分頁便執行取得該頁數的資料筆數
$result = mysqli_query($link, "$query_data3 limit $start, $per");
$row_data3 = mysqli_fetch_assoc($result);

?>

<?php if( $todate >= $row_data2['job_start_date'] and $row_data2['job_start_date']!=null ){ 
	if($row_data3['rr_num']!=null and $totalRows_data3>=1){ ?>
		<div class="topnav">
			<a class="label label-pill label-success" href="/seminar/review/review_result_excel.php?th=<?php echo $th; ?>">匯出成excel</a><br>
		</div>
<?php }
 } ?>

<table class="table table-striped table-hover" >
	<thead>
		<tr>
			<th>編號</th>
			<th>論文編號</th>
			<th>論文名稱</th>
			<th>分類</th>
			<th>分數一</th>
			<th>分數二</th>
			<th>平均分數</th>
			<th>委員一<br>是否推薦</th>
			<th>委員二<br>是否推薦</th>
			<th>備註</th>
		</tr>
	<thead>
	<tbody>
		<?php 
		if( $todate < $row_data2['job_start_date'] or $row_data2['job_start_date']==null){ ?>
			<tr><td colspan="10" >審查尚未開始，請耐心等候。</td></tr>
		<?php }elseif($row_data3['rr_num']==null and $totalRows_data3==1){ ?>
			<tr><td colspan="10" >目前還沒有資料</td></tr>
		<?php }else{
			$i=0;
			do{ $i++ ?>
			<tr>
				<td><?php echo $i+($page-1)*10; ?></td>
				<td><?php echo $row_data3['rr_num']; ?></td>
				<td><?php echo $row_data3['rr_paper']; ?></td>
				<td><?php echo $row_data3['rr_sort']; ?></td>
				<td><?php echo $row_data3['rr_score_one']; ?></td>
				<td><?php echo $row_data3['rr_score_two']; ?></td>
				<td><?php echo $row_data3['rr_score_avg']; ?></td>
				<td><?php if($row_data3['rr_recommend_one']==1){ echo "是"; }else{ echo "否"; } ?></td>
				<td><?php if($row_data3['rr_recommend_two']==1){ echo "是"; }else{ echo "否"; } ?></td>
				<td><?php echo $row_data3['rr_remark']; 
				if($button_on){
					if($row_data3['rr_remark']==null){ ?>
						<input type="button" class="btn btn-primary" name="rr_remark" id="rr_remark" value="備註" onClick="window.open('review_result_remark.php?th=<?php echo $th; ?>&rr_num=<?php echo $row_data3["rr_num"]; ?>' , '備註', config='height=300,width=500')">
					<?php }else{ ?>
						<input type="button" class="btn btn-primary" name="rr_remark" id="rr_remark" value="修改" onClick="window.open('review_result_remark.php?th=<?php echo $th; ?>&rr_num=<?php echo $row_data3["rr_num"]; ?>' , '修改', config='height=300,width=500')">
					<?php } 
				} ?>
				</td>
			</tr>
		<?php }while($row_data3 = mysqli_fetch_assoc($result)); 
		} ?>
	</tbody>
</table>

<nav style="text-align:center">
	<ul class="pagination">
	<?php 
		if($totalRows_data3!=0){
			echo '共 ' . $totalRows_data3 . ' 筆' . ' - 共 ' . $pages . ' 頁' . "<br>"; ?>
			
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

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>