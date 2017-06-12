<?php require_once('../seminar_connect.php');
 
 if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if($button_on==false){
	echo "<script>alert('研討會時間已過!');</script>";
	exit ("<script>location.href='/seminar/list/list.php?th=$th'</script>");
} 

if($_GET){
	$upsort_num=$_GET["upsort_num"];
}

 

$query_data = "SELECT * FROM upload WHERE upsort_num = '$upsort_num'";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);


 
$query_data1 = "SELECT * FROM current_committee";
$data1 = mysqli_query($link,$query_data1) or die(mysqli_error());
$row_data1 = mysqli_fetch_assoc($data1);
$row_sum1 = mysqli_num_rows($data1);


$seach = "SELECT * FROM list WHERE upsort_num like '$upsort_num'";
$seach_data = mysqli_query($link,$seach) or die(mysqli_error());
$row_datas = mysqli_fetch_assoc($seach_data);

$row_sums = mysqli_num_rows($seach_data);



if(isset($_POST['submit'])){
	
	$email = $_POST['cc_email'];
	$num = count($email);
	if($num>2){
		echo "<script>alert('所選人員超過2個');</script>";
	}
	else{
		
		if($row_sums==2){
			if($num<2){
				echo "<script>alert('所選人員少於2個');</script>";
				$sqlU = false;
			}else{
				$i = 0;
				do{
					$id = $email[$i];
					$list_no = $row_datas['list_no'];
					$sqlUpd = sprintf("UPDATE  list SET cc_email='$id',upsort_num='$upsort_num' where list_no='$list_no'");
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
					$i=$i+1;
				}while($row_datas = mysqli_fetch_assoc($seach_data));
			}
		}
		
		
		for($i=0;$i<=$num-1;$i++){
			$id = $email[$i];
			if($row_sums==0){
				$sqlUpd = sprintf("INSERT INTO list (cc_email,upsort_num) value('$id','$upsort_num')");
				$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
			}
			
			if($row_sums==1){
				if($i==0){
					$list_no = $row_datas['list_no'];
					$sqlUpd = sprintf("UPDATE  list SET cc_email='$id',upsort_num='$upsort_num' where list_no='$list_no'");
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
				}
				else{
					$sqlUpd = sprintf("INSERT INTO list (cc_email,upsort_num) value('$id','$upsort_num')");
					$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
				}
				
			}	
			
		}
		if($sqlU){
			echo "<script>alert('已新增!!');</script>"; 
			exit('<script>window.opener.location.reload(); window.opener=null; window.close();</script>');
		}
		
		
	}
		
		
}

 
 
 
 ?>

 
 
 
 
 
 
 <html>
 <head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>派稿</title>
</head>
<form  method="POST" action="list_insert.php?upsort_num=<?php echo $upsort_num ?>&th=<?php echo $th ?>">
<div class="page-header">
	<h1 align="center">派稿</h1>
</div>





<input type="hidden" name="upsort_num" value="<?php echo $upsort_num ;?>">
<div class="form-group">
	<label class="control-label col-sm-2" for="up_paper">論文名稱</label>
	<div class="col-sm-10">
		<input name="up_paper" id="up_paper" disabled="disabled" value="<?php echo $row_data['up_paper']?>"><br>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-2" for="up_sort">類別</label>
	<div class="col-sm-10">
		<input name="up_sort" id="up_sort" disabled="disabled" value="<?php echo $row_data['up_sort']?>"><br>
	</div>
</div>	

<div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>選擇人員(最多2個):</label>
	</div>
</div>



<table class="table table-striped  table-hover">
 <tr>
	<th>選擇</th>
	<th>擅長領域</th>
	<th>姓名</th>
</tr>
 <?php 
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
	
	
	 
	
	
	$query_two = "SELECT * FROM committee_list where cl_email = '$cc_two'";
	$data_two = mysqli_query($link_generic,$query_two) or die(mysqli_error());
	$row_two = mysqli_fetch_assoc($data_two);
	
 
 
 do{?>
<tr>
	<?php
	$who = $row_data1['cc_email'];
	
	$query_who = "SELECT * FROM committee_list where cl_email = '$who'";
	$data_who = mysqli_query($link_generic,$query_who) or die(mysqli_error());
	$row_who = mysqli_fetch_assoc($data_who);
	$num_who = mysqli_num_rows($data_who);
	
	
	if($num_who!=0){
	
	?>


	<td><input type="checkbox" name="cc_email[]" id="cc_email[]" value="<?php echo $row_data1['cc_email']; ?>" 
	<?php 

	if($row_data1['cc_email'] == $cc_one){?> checked <?php } 
	if($row_data1['cc_email'] == $cc_two){?> checked <?php }
	
	
	
	?> ></td>	<?php } ?>
	
	
	<?php if($num_who!=0){ ?>
	<td><?php echo $row_who['cl_scopes']; ?></td>
	<td><?php echo $row_who['cl_name']; ?></td>
	<?php } ?>
 <?php }while($row_data1 = mysqli_fetch_assoc($data1)); ?>
 </tr>
 <table>
 <br><input class="btn btn-primary" id="submit" name="submit" type="submit" value="儲存">
 	
	
	

 
 
 
 
 
 
 
 
 

 </html>