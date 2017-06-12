<?php require_once('../seminar_connect.php');


$query_data = "SELECT * FROM all_sort";
$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);

$query_data2 = "SELECT * FROM sort order by sort_no ASC";
$data2 = mysqli_query($link, $query_data2) or die (mysqli_error());
$row_data2 = mysqli_fetch_assoc($data2);

$all_sort_abbreviation = array();
$all_sort_name = array();
$new_sort_abbreviation = array();
$new_sort_name = array();

do{
	array_push($all_sort_abbreviation, $row_data['all_sort_abbreviation']);
	array_push($all_sort_name, $row_data['all_sort_name']);
}while($row_data = mysqli_fetch_assoc($data));
do{
	array_push($new_sort_abbreviation, $row_data2['sort_abbreviation']);
	array_push($new_sort_name, $row_data2['sort_name']);
}while($row_data2 = mysqli_fetch_assoc($data2));

if(isset($_POST['submit'])){
	if(in_array($_POST['sort_abbreviation'], $all_sort_abbreviation)){
		echo "<script>alert('此分類「代碼」總分類已有，請從總分類匯入。!!');</script>";
	}elseif(in_array($_POST['sort_name'], $all_sort_name)){
		echo "<script>alert('此分類「名稱」總分類已有，請從總分類匯入。!!');</script>";
	}else{
		if(in_array($_POST['sort_abbreviation'], $new_sort_abbreviation)){
			echo "<script>alert('此分類「代碼」已有，請重新輸入。!!');</script>";
		}elseif(in_array($_POST['sort_name'], $new_sort_name)){
			echo "<script>alert('此分類「名稱」已有，請重新輸入。!!');</script>";
		}else{
			$sqlIns = sprintf("INSERT INTO all_sort (all_sort_abbreviation, all_sort_name) values (%s,%s)",'"'.$_POST['sort_abbreviation'].'"','"'.$_POST['sort_name'].'"');
			$sqlI = mysqli_query($link_generic, $sqlIns) or die ("MYSQL Error");
			if($sqlI){
				$sqlIns2 = sprintf("INSERT INTO sort (sort_abbreviation, sort_name) values (%s,%s)",'"'.$_POST['sort_abbreviation'].'"','"'.$_POST['sort_name'].'"');
				$sqlI2 = mysqli_query($link, $sqlIns2) or die ("MYSQL Error");
				if($sqlI2){
					exit('<script>window.opener.location.reload(); window.opener=null; window.close();</script>');
				}else{
					echo "Have Error";
				}
			}else{
				echo "Have Error";
			}
		}
	}
}


?>

<html>
<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->

<form method="POST" action="sort_insert.php?th=<?php echo $th; ?>" >
<title>新增本屆論文分類</title>
<center><font color="#0000a0"><h1>新增本屆論文分類</h1></font></center>
<hr color="black"><br>

<center>
<?php if(empty($_POST['sort_abbreviation'])){ ?> <font color="red">*</font> <?php } ?>
分類代碼：<input type="text" id="sort_abbreviation" name="sort_abbreviation" 
<?php if(!empty($_POST['sort_abbreviation'])){ ?> value="<?php echo $_POST['sort_abbreviation'] ?>" <?php } ?> required />
<br><br>
<?php if(empty($_POST['sort_name'])){ ?> <font color="red">*</font> <?php } ?>
分類名稱：<input type="text" id="sort_name" name="sort_name"
<?php if(!empty($_POST['sort_name'])){ ?> value="<?php echo $_POST['sort_name'] ?>" <?php } ?> required />
<br><br>
<input type="submit" name="submit" id="submit" value="新增">
</center>

<br>
</form>
<!--改版改這裡~這裡以下都要改成這樣   記得!!!如果用複製的下面那個delete_case路徑要改-->
</div>

</body>
</html>