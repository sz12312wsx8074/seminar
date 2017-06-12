<?php require_once('../base_home.php');
 
if(!isset($_SESSION)){  //開啟session功能，要是已經開啟就不用在開
    session_start();
	echo "<script>alert('已新增!!');</script>"; 	
}

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}


if(!isset($_GET["page"])){
    $page=1;  //設定起始頁//isset 在此是判斷後方參數是否存在
    $_SESSION['search'] = "SELECT * FROM upload";
	
}else{
    $page = intval($_GET["page"]);  //確認頁數只能夠是數值資料     
	
}
 

$query_data = $_SESSION['search'];
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);

$query_data1 = "SELECT * FROM list";
$data1 = mysqli_query($link,$query_data1) or die(mysqli_error());
$row_data1 = mysqli_fetch_assoc($data1);
$row_sum1 = mysqli_num_rows($data1);

$totalRows_data = mysqli_num_rows($data);

$per = 20; //設定每頁顯示項目數量
$pages = ceil($totalRows_data/$per); //計算總頁數
$start = ($page-1)*$per; //每頁起始資料序號,以便分次藉由sql語法去取得資料       
//每點任一分頁便執行取得該頁數的資料筆數
$result = mysqli_query($link, "$query_data limit $start,$per");
$row_data = mysqli_fetch_assoc($result); 





?>
<html>
<div id=content class="col-sm-9"> 
<form method="POST" action="list.php?page=1&th=<?php echo $th ?>">

<h1>派稿</h1>
<hr>

<input  class="btn btn-default" type="button" name="insert" id="insert" value="發送郵件" onClick="self.location='committee_email.php'">
<hr>


<table class="table table-striped  table-hover">
<tr>
	<th>派稿</th>
	<th>論文編號</th>
	<th>論文名稱</th>
	<th>分類</th>
	<th>派稿狀況</th>
	<th colspan="2" >人員</th>
</tr>
<?php
if($row_sum!=0){
do{ ?>
<tr>
	<?php
	if($button_on){ ?>
		<td><input type="button" class="btn btn-info" name="update" id="update" value="派稿" onClick="window.open('list_insert.php?upsort_num=<?php echo $row_data["upsort_num"]; ?>&th=<?php echo $th; ?> ','派稿',config='height=600,width=700')"></td>
	<?php }else{ ?>
		<td></td>
	<?php } ?>
	<td><?php echo $row_data['upsort_num']; ?></td>
	<td><?php echo $row_data['up_paper']; ?></td>
	<td><?php echo $row_data['up_sort']; ?></td>
	
	<?php  
	$upsort_num = $row_data['upsort_num'];
	$seach = "SELECT * FROM list WHERE upsort_num like '$upsort_num'";
	$seach_data = mysqli_query($link,$seach) or die(mysqli_error());
	$row_datas = mysqli_fetch_assoc($seach_data);
	$row_sums = mysqli_num_rows($seach_data);
	
	
	
	
	$i = 0;
	$cc_one = false;
	$cc_two = false;
	do{
		$cc_email = $row_datas['cc_email'];
		if($i == 0){
			$cc_one = $cc_email;
		}else{
			$cc_two = $cc_email;
		}
		$i = $i+1;
		 
	}while($row_datas = mysqli_fetch_assoc($seach_data));	
	
	
	
	
	if($cc_one){
		$query_one = "SELECT * FROM committee_list where cl_email = '$cc_one' ";
		$one = mysqli_query($link_generic,$query_one) or die(mysqli_error());
		$row_one = mysqli_fetch_assoc($one);
		$ones = $row_one['cl_name'];
	}
	if($cc_two){
		$query_two = "SELECT * FROM committee_list where cl_email = '$cc_two' ";
		$two = mysqli_query($link_generic,$query_two) or die(mysqli_error());
		$row_two = mysqli_fetch_assoc($two);
		$twos = $row_two['cl_name'];
	}
	
	

	if($row_sums==2){?>
		<td> <font color="blue">已派稿</font></td>
	<?php 
	}else{?>
		<td> <font color="red">未派稿</font></td>
	<?php } 
	
	if($cc_one){?> <td> <?php echo $ones ?> </td> <?php } else{?> <td> </td>  <?php }
	if($cc_two){?> <td> <?php echo $twos ?> </td> <?php } else{?> <td> </td>  <?php }?>
	
	<?php
	
		// $email = $row_datas['cc_email'];
		// do{
		
			// $emails = "SELECT * FROM current_committee where cc_email='$emails'";
		
		// }while($row_names = mysqli_fetch_assoc($names_data));
		
		// $names_data = mysqli_query($link,$names) or die(mysqli_error());
		// $row_names = mysqli_fetch_assoc($names_data);
		// do{
			// $q = $row_names['cc_name'];;
		 // echo "<script>alert('$q');</script>";
			// echo $row_names['cc_name'];
		// }while($row_names = mysqli_fetch_assoc($names_data));
	?>
	
</tr>

<?php }while($row_data = mysqli_fetch_assoc($result));
}else{ ?>
	<tr><td colspan="7">目前沒有資料</td></tr><?php
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




</div>
</html>

<?php require_once('../base_footer.php');?>