<?php require_once('../seminar_connect.php');


$query_data = "SELECT * FROM committee_list";
$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

$cl_name = array();
$cl_scopes = array();
$cl_email = array();
do{
	array_push($cl_name, $row_data['cl_name']);
	array_push($cl_scopes, $row_data['cl_scopes']);
	array_push($cl_email, $row_data['cl_email']);
}while($row_data = mysqli_fetch_assoc($data));

if(isset($_POST['submit'])){
	if(empty($_POST['cl_name'])or empty($_POST['cl_scopes']) or empty($_POST['cl_email'])){
		echo "<script>alert('資料不齊全，請重新輸入。!!');</script>";
	
	}else{
			$sqlIns = sprintf("INSERT INTO committee_list (cl_name, cl_scopes, cl_email) values (%s,%s,%s)",'"'.$_POST['cl_name'].'"','"'.$_POST['cl_scopes'].'"','"'.$_POST['cl_email'].'"');
			$sqlI = mysqli_query($link_generic, $sqlIns) or die ("MYSQL Error");
		
			if($sqlI){
				exit("<script>location.href='list_related.php?th=$th';</script>");
			}else{
				echo "Have Error";
			}
		}
	
}


?>

<html>
<div id=content class="col-sm-9">      <!--改版改這裡~~~~~~~ -->

<form method="POST" action="list_related_insert.php?th=<?php echo $th; ?>" >
<center><font color="#0000a0"><h1>新增審查委員</h1></font></center>
<hr color="black"><br>

<center><?php if(empty($_POST['cl_name'])){ ?> <font color="red">*</font> <?php } ?>
審查委員姓名：<input type="text" id="cl_name" name="cl_name" 
<?php if(!empty($_POST['cl_name'])){ ?> value="<?php echo $_POST['cl_name'] ?>"  <?php } ?> required/></center><br>

<center><?php if(empty($_POST['cl_scopes'])){ ?> <font color="red">*</font> <?php } ?>
擅長領域：<textarea  class="input"  id="cl_scopes" name="cl_scopes"
<?php if(!empty($_POST['cl_scopes'])){ ?> value="<?php echo $_POST['cl_scopes'] ?>"  <?php } ?> required/></textarea></center><br><br>

<center><?php if(empty($_POST['cl_email'])){ ?> <font color="red">*</font> <?php } ?>
Email：<input type="email" id="cl_email" name="cl_email" 
<?php if(!empty($_POST['cl_email'])){ ?> value="<?php echo $_POST['cl_email'] ?>"  <?php } ?> required/></center><br>

<br><br>
<center><input type="button" name="uppage" id="uppage" value="上一頁" onClick="self.location='list_related.php?th=<?php echo $th; ?>'">
&nbsp&nbsp&nbsp;
<input type="submit" name="submit" id="submit" value="新增"></center>

</body>
</form>
</html>