<?php require_once('../seminar_connect.php');

if(isset($_POST['update'])){
	$cc_related=$_POST["cc_related"];
	if(empty($_POST['cl_name'])or empty($_POST['cl_scopes']) or empty($_POST['cl_email'])){
		echo "<script>alert('資料不齊全，請重新輸入。!!');</script>";
	}else{	 
		
		$query_data = "SELECT * FROM committee_list where cl_related!='$cc_related'";
		$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
		$row_data = mysqli_fetch_assoc($data);
		$totalRows_data = mysqli_num_rows($data);

		
		$cl_email2 = array();
		do{
			array_push($cl_email2, $row_data['cl_email']);
		}while($row_data = mysqli_fetch_assoc($data));		
		if(in_array($_POST['cl_email'], $cl_email2)){
			echo "<script>alert('已有此Email，請重新輸入。!!');</script>"; 
		}else{
			$sqlUpd = sprintf("UPDATE committee_list SET cl_name='%s', cl_scopes='%s', cl_email='%s' WHERE cl_related = '%s'"
			,$_POST['cl_name'],$_POST['cl_scopes'],$_POST['cl_email'],$cc_related);		
			$sqlU = mysqli_query($link_generic, $sqlUpd) or die ("MYSQL Error");
			if($sqlU){
				echo "<script>alert('已修改!!');</script>";
				exit("<script>location.href='list_related.php?th=$th';</script>");
			}else{
				echo "Have Error";
			}	
		}
	}
}else{
	$cc_related=$_GET["cl_related"]; 
}


$query_data = "SELECT * FROM committee_list where cl_related ='$cc_related'";
$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>修改審查委員</title>
</head>

<body>
<form method="POST" action="list_related_update.php?th=<?php echo $th; ?>" >
<center><font color="#0000a0"><h2>修改審查委員</h2></font></center>
<hr color="black"><br>
<input type="hidden" name = "cc_related" value = "<?php echo $row_data['cl_related'];?>" />

<center><?php if(empty($_POST['cl_name'])){ ?> <font color="red">*</font> <?php } ?>
審查委員姓名：<input type="text" id="cl_name" name="cl_name" value="<?php echo $row_data['cl_name'] ?>" required/></center><br>

<center><?php if(empty($_POST['cl_scopes'])){ ?> <font color="red">*</font> <?php } ?>
擅長領域：<textarea  class="input"  id="cl_scopes" name="cl_scopes"  required/><?php echo $row_data['cl_scopes'] ?></textarea></center><br><br>

<center><?php if(empty($_POST['cl_email'])){ ?> <font color="red">*</font> <?php } ?>
Email：<input type="email" id="cl_email" name="cl_email" value="<?php echo $row_data['cl_email'] ?>" required/></center><br>
<br><br>
<center><input type="button" name="uppage" id="uppage" value="上一頁" onClick="self.location='list_related.php?th=<?php echo $th; ?>'">
&nbsp&nbsp&nbsp;
<input type="submit" name="update" id="update" value="修改"></center>

</body>
</form>
</html>