<?php require_once('../seminar_connect.php');

if(isset($_POST['update'])){
	$sort_abbreviation=$_POST["sort_abbreviation"];
	if(empty($_POST['all_sort_abbreviation'])or empty($_POST['all_sort_name'])){
		echo "<script>alert('資料不齊全，請重新輸入。!!');</script>";
	}else{ 

		$query_data = "SELECT * FROM all_sort where all_sort_abbreviation!='$sort_abbreviation'";
		$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
		$row_data = mysqli_fetch_assoc($data);
		$totalRows_data = mysqli_num_rows($data);

		$all_sort_abbreviation2 = array();
		$all_sort_name2 = array();
		do{
			array_push($all_sort_abbreviation2, $row_data['all_sort_abbreviation']);
			array_push($all_sort_name2, $row_data['all_sort_name']);
		}while($row_data = mysqli_fetch_assoc($data));

		if(in_array($_POST['all_sort_abbreviation'], $all_sort_abbreviation2)){
			echo "<script>alert('已有此分類「代碼」，請重新輸入。!!');</script>"; 
		}elseif(in_array($_POST['all_sort_name'], $all_sort_name2)){
			echo "<script>alert('已有此分類「名稱」，請重新輸入。!!');</script>";
		}else{
			$sqlUpd = sprintf("UPDATE all_sort SET all_sort_abbreviation='%s', all_sort_name='%s' WHERE all_sort_abbreviation = '%s'"
			,$_POST['all_sort_abbreviation'],$_POST['all_sort_name'],$_POST['sort_abbreviation']);
		
			$sqlU = mysqli_query($link_generic, $sqlUpd) or die ("MYSQL Error");
			if($sqlU){
				echo "<script>alert('已修改!!');</script>";
				exit("<script>window.opener.location.reload(); location.href='all_sort.php?th=$th';</script>");
			}else{
				echo "Have Error";
			}
		}
	}
}else{
	$sort_abbreviation=$_GET["all_sort_abbreviation"]; 
}
$query_data = "SELECT * FROM all_sort where all_sort_abbreviation ='$sort_abbreviation'";
$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>修改總論文分類</title>
</head>

<body>
<form method="POST" action="all_sort_update.php?th=<?php echo $th; ?>" >
<center><font color="#0000a0"><h2>修改總論文分類</h2></font></center>
<hr color="black"><br>
<input type="hidden" name = "sort_abbreviation" value = "<?php echo $row_data['all_sort_abbreviation'];?>" />
<center>
<?php if(empty($_POST['all_sort_abbreviation'])){ ?> <font color="red">*</font> <?php } ?>
分類代碼：<input type="text" id="all_sort_abbreviation" name="all_sort_abbreviation" value="<?php echo $row_data['all_sort_abbreviation'] ?>" required />
<br><br>
<?php if(empty($_POST['all_sort_name'])){ ?> <font color="red">*</font> <?php } ?>
分類名稱：<input type="text" id="all_sort_name" name="all_sort_name" value="<?php echo $row_data['all_sort_name'] ?>" required/>
<br><br>
<input type="button" name="uppage" id="uppage" value="上一頁" onClick="self.location='all_sort.php?th=<?php echo $th; ?>'">
&nbsp&nbsp&nbsp;
<input type="submit" name="update" id="update" value="修改">
</center>

</body>
</form>
</html>