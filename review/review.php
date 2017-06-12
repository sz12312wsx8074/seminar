<?php require_once('../seminar_connect.php');   //改版改這裡~~~~~~~~
if(!isset($_SESSION['cc_email'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/current_committee/cg_login.php"</script>');
	return;
}

$query_data4 = "SELECT * FROM time_job where time_no = '2'";
$data4 = mysqli_query($link, $query_data4) or die (mysqli_error());
$row_data4 = mysqli_fetch_assoc($data4);
$totalRows_data4 = mysqli_num_rows($data4);

$starttime = $row_data4['job_start_date'];
$endtime = $row_data4['job_end_date'];
$today = date("Y-m-d");

if((strtotime($today) >= strtotime($starttime))AND(strtotime($today) <= strtotime($endtime))){
}elseif((strtotime($today) > strtotime($endtime) and $endtime!=null )){
	echo "<script>alert('審稿時間已經結束!');</script>";
	exit ('<script>location.href="/seminar/current_committee/cg_login.php"</script>');
}else{
	echo "<script>alert('審稿時間尚未開始!');</script>";
	exit ('<script>location.href="/seminar/current_committee/cg_login.php"</script>');
}

$email = $_SESSION['cc_email'];

if(!isset($_GET["page"])){
    $page=1;  //設定起始頁//isset 在此是判斷後方參數是否存在
    $query_data3 = "SELECT * FROM list where cl_email='$email' and (check_list=1 or check_list=5)";
}else{
    $page = intval($_GET["page"]);  //確認頁數只能夠是數值資料   
}

$query_data3 = "SELECT * FROM list where cc_email = '$email'";
$data3 = mysqli_query($link, $query_data3) or die (mysqli_error());
$totalRows_data3 = mysqli_num_rows($data3);

$per = 10; //設定每頁顯示項目數量
$pages = ceil($totalRows_data3/$per); //計算總頁數
$start = ($page-1)*$per; //每頁起始資料序號,以便分次藉由sql語法去取得資料       
//每點任一分頁便執行取得該頁數的資料筆數

$result = mysqli_query($link, "$query_data3 limit $start,$per");
$row_data3 = mysqli_fetch_assoc($result);
$row_sum = mysqli_num_rows($result);


?>


<html>
<div id=content class="col-md-9 col-md-offset-1">    <!--改版改這裡~~~~~~~ -->
<!--class="form-control"-->
<form method="POST" action="review.php" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">
<h1 class="text-center">審稿</h1>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<input type="button" class="col-sm-offset-10 btn btn-primary" name="update" value="登出" onClick="self.location='/seminar/current_committee/cg_logout.php'">
<table class="table table-striped table-hover" >
	<thead>
		<tr id="title">
			<td >論文編號</td>
			<td >論文名稱</td>
			<td >分類</td>
			<td >分數</td>
			<td>審稿</td>
		</tr>
	</thead>
	<tbody>
	<?php 
	if($totalRows_data3==0){ ?>
		<tr><td colspan="5" class="data">目前還沒有資料</td></tr>
<?php }else{
		do{ ?>
			<tr >
				<td><?php echo $row_data3['upsort_num']; ?></td>
				<?php $num = $row_data3['upsort_num'];
				$query_data = "SELECT * FROM upload where upsort_num='$num'";
				$data = mysqli_query($link, $query_data) or die (mysqli_error());
				$row_data = mysqli_fetch_assoc($data); ?>
				<td><?php echo $row_data['up_paper']; ?></td>
				<td><?php echo $row_data['up_sort']; ?></td>
				<?php $query_data2 = "SELECT * FROM review where rw_num='$num' and cc_email='$email'";
				$data2 = mysqli_query($link, $query_data2) or die (mysqli_error());
				$row_data2 = mysqli_fetch_assoc($data2);
				$totalRows_data2 = mysqli_num_rows($data2); ?>
				<td> <?php if($totalRows_data2==0){
					echo "未審";
				}else{
					echo $row_data2['rw_score'];
				} ?> </td>
				<td><input type="button" name="review_insert" id="review_insert" value="審稿" onClick="self.location='review_insert.php?rw_num=<?php echo $row_data3["upsort_num"]; ?>'"></td>
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
			
			<li><a href="?page=1"><span aria-hidden="true">&laquo;</span><span class="sr-only">first</span></a></li>
	
	<?php 	if($page==1){  //上一頁 ?>
				<li><a href="?page=<?php echo $page ?>"><span aria-hidden="true">&lt;</span><span class="sr-only">Previous</span></a></li><?php
			}else{ ?>
				<li><a href="?page=<?php echo $page-1 ?>"><span aria-hidden="true">&lt;</span><span class="sr-only">Previous</span></a></li><?php
			}
			
			for( $i=1 ; $i<=$pages ; $i++ ){  //列出分頁
				if ( $page-5 < $i && $i < $page+5 ){ 
					if($i==$page){ ?>
						<li class="active"><a href="?page=<?php echo $i ?>"><?php echo $i ?></a></li><?php
					}else{ ?>
						<li><a href="?page=<?php echo $i ?>"><?php echo $i ?></a></li><?php
					}
				}
			}			

			if($page==$pages){  //下一頁 ?>
				<li><a href="?page=<?php echo $page ?>"><span aria-hidden="true">&gt;</span><span class="sr-only">Next</span></a></li><?php
			}else{ ?>
				<li><a href="?page=<?php echo $page+1 ?>"><span aria-hidden="true">&gt;</span><span class="sr-only">Next</span></a></li><?php
			}?>
			
			<li><a href="?page=<?php echo $pages ?>"><span aria-hidden="true">&raquo;</span><span class="sr-only">last</span></a></li>
		
		<?php } ?>
	</ul>
</nav>

</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>