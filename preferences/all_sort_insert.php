<?php require_once('../seminar_connect.php');

$query_data = "SELECT * FROM all_sort";
$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

$all_sort_abbreviation = array();
$all_sort_name = array();
do{
	array_push($all_sort_abbreviation, $row_data['all_sort_abbreviation']);
	array_push($all_sort_name, $row_data['all_sort_name']);
}while($row_data = mysqli_fetch_assoc($data));

if(isset($_POST['submit'])){
	if(empty($_POST['all_sort_abbreviation'])or empty($_POST['all_sort_name'])){
		echo "<script>alert('資料不齊全，請重新輸入。!!');</script>";
	}else{
		if(in_array($_POST['all_sort_abbreviation'], $all_sort_abbreviation)){
			echo "<script>alert('已有此分類「代碼」，請重新輸入。!!');</script>";
		}elseif(in_array($_POST['all_sort_name'], $all_sort_name)){
			echo "<script>alert('已有此分類「名稱」，請重新輸入。!!');</script>";
		}else{
			$sqlIns = sprintf("INSERT INTO all_sort (all_sort_abbreviation,all_sort_name) values (%s,%s)",'"'.$_POST['all_sort_abbreviation'].'"','"'.$_POST['all_sort_name'].'"');
			$sqlI = mysqli_query($link_generic, $sqlIns) or die ("MYSQL Error");
		
			if($sqlI){
				exit("<script>location.href='all_sort.php?th=$th';</script>");
			}else{
				echo "Have Error";
			}
		}
	}
}


?>

<html>
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->

<form method="POST" action="all_sort_insert.php?th=<?php echo $th; ?>" >
<title>新增總論文分類</title>
<center><font color="#0000a0"><h1>新增總論文分類</h1></font></center>
<hr color="black"><br>

<center><?php if(empty($_POST['all_sort_abbreviation'])){ ?> <font color="red">*</font> <?php } ?>
分類代碼：<input type="text" id="all_sort_abbreviation" name="all_sort_abbreviation" 
<?php if(!empty($_POST['all_sort_abbreviation'])){ ?> value="<?php echo $_POST['all_sort_abbreviation'] ?>"  <?php } ?> required />
<br><br>
<?php if(empty($_POST['all_sort_name'])){ ?> <font color="red">*</font> <?php } ?>
分類名稱：<input type="text" id="all_sort_name" name="all_sort_name" 
<?php if(!empty($_POST['all_sort_name'])){ ?> value="<?php echo $_POST['all_sort_name'] ?>"  <?php } ?> required />
<br><br>
<input type="button" name="uppage" id="uppage" value="上一頁" onClick="self.location='all_sort.php?th=$th'">
&nbsp&nbsp&nbsp;
<input type="submit" name="submit" id="submit" value="新增">
</center>

</body>
</form>
</html>