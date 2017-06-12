<?php require_once('../seminar_connect.php');

if(isset($_POST['update'])){
	$sort_abbreviation=$_POST["new_sort_abbreviation"];
	
	
	$query_data = "SELECT * FROM all_sort where all_sort_abbreviation!='$sort_abbreviation'";
	$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
	$row_data = mysqli_fetch_assoc($data);
	
	$query_data2 = "SELECT * FROM sort where sort_abbreviation!='$sort_abbreviation'";
	$data2 = mysqli_query($link, $query_data2) or die (mysqli_error());
	$row_data2 = mysqli_fetch_assoc($data2);

	$all_sort_abbreviation2 = array();
	$all_sort_name2 = array();
	$new_sort_abbreviation = array();
	$new_sort_name = array();
	do{
		array_push($all_sort_abbreviation2, $row_data['all_sort_abbreviation']);
		array_push($all_sort_name2, $row_data['all_sort_name']);
	}while($row_data = mysqli_fetch_assoc($data));
	do{
		array_push($new_sort_abbreviation, $row_data2['sort_abbreviation']);
		array_push($new_sort_name, $row_data2['sort_name']);
	}while($row_data2 = mysqli_fetch_assoc($data2));

	if(in_array($_POST['sort_abbreviation'], $new_sort_abbreviation)){
		echo "<script>alert('已有此分類「代碼」。!!');</script>"; 
	}elseif(in_array($_POST['sort_name'], $new_sort_name)){
		echo "<script>alert('已有此分類「名稱」。!!');</script>";
	}else{
		if(in_array($_POST['sort_abbreviation'], $all_sort_abbreviation2)){
			echo "<script>alert('總分類已有此分類「代碼」。!!');</script>"; 
		}elseif(in_array($_POST['sort_name'], $all_sort_name2)){
			echo "<script>alert('總分類已有此分類「名稱」。!!');</script>";
		}else{
			$sqlUpd = sprintf("UPDATE all_sort SET all_sort_abbreviation='%s', all_sort_name='%s' WHERE all_sort_abbreviation = '%s'"
			,$_POST['sort_abbreviation'],$_POST['sort_name'],$_POST['new_sort_abbreviation']);
		
			$sqlU = mysqli_query($link_generic, $sqlUpd) or die ("MYSQL Error");
			if($sqlU){
				echo "<script>alert('已修改!!');</script>";
				exit('<script>window.opener.location.reload(); window.opener=null; window.close();</script>');
			}else{
				echo "Have Error";
			}
		}
	}
}else{
	$sort_abbreviation=$_GET["new_sort_abbreviation"]; 
}

$query_data = "SELECT * FROM sort where sort_abbreviation ='$sort_abbreviation'";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

?>

<html>
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->

<form method="POST" action="sort_update.php?th=<?php echo $th; ?>" >
<title>修改本屆論文分類</title>
<center><font color="#0000a0"><h1>修改本屆論文分類</h1></font></center>
<hr><br>
<input type="hidden" name = "new_sort_abbreviation" value = "<?php echo $row_data['sort_abbreviation'];?>" />

<center>
<?php if(empty($_POST['sort_abbreviation'])){ ?> <font color="red">*</font> <?php } ?>
分類代碼：<input type="text" id="sort_abbreviation" name="sort_abbreviation" value="<?php echo $row_data['sort_abbreviation'] ?>" required />
<br><br>
<?php if(empty($_POST['sort_name'])){ ?> <font color="red">*</font> <?php } ?>
分類名稱：<input type="text" id="sort_name" name="sort_name" value="<?php echo $row_data['sort_name'] ?>" required />
<br><br>
<input type="submit" name="update" id="update" value="修改">
</center>


<br>
</form>
<!--改版改這裡~這裡以下都要改成這樣   記得!!!如果用複製的下面那個delete_case路徑要改-->
</div>

</body>
</html>