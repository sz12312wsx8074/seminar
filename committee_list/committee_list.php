<?php require_once('../base_home.php');
mysqli_select_db($link,$database);


if(isset($_POST['dele'])){
	mysqli_select_db($link, $database);
	$query_Delete = "SELECT * FROM committee_list";
	$Delete = mysqli_query($link_generic, $query_Delete) or die (mysqli_error());
	$row_Delete = mysqli_fetch_assoc($Delete);
	do{
		if(isset($_POST[$row_Delete['cl_related']])){
			$sqlDel = sprintf("UPDATE committee_list SET cl_history='%s' WHERE cl_related='%s'", 1, $row_Delete['cl_related']);
			$sqlD = mysqli_query($link_generic, $sqlDel) or die (mysqli_error());
		}
	}while($row_Delete = mysqli_fetch_assoc($Delete));
}

if(isset($_POST['search'])){
	if(($_POST['class']=="all" or $_POST['class']=="related" or $_POST['class']=="name" or $_POST['class']=="scopes" or $_POST['class']=="email") and empty ($_POST['keyword'])){  //關鍵字是空值
		$query_data = "SELECT * FROM committee_list where cl_history=0 order by cl_related DESC";
	}else{
		$key=$_POST['keyword'];
		if($_POST['class']=="all"){
			$query_data = "SELECT * FROM committee_list where (cl_related like '%$key%' or cl_name like '%$key%' 
			              or cl_scopes like '%$key%' or cl_email like '%$key%') 
						  and cl_history=0 order by cl_related DESC";
		}else if($_POST['class']=="related"){
			$query_data = "SELECT * FROM committee_list where cl_related like '%$key%' and cl_history=0 order by cl_related DESC";
		}else if($_POST['class']=="name"){
			$query_data = "SELECT * FROM committee_list where cl_name like '%$key%' and cl_history=0 order by cl_related DESC";
		}else if($_POST['class']=="scopes"){
			$query_data = "SELECT * FROM committee_list where cl_scopes like '%$key%' and cl_history=0 order by cl_related DESC";
		}else if($_POST['class']=="email"){
			$query_data = "SELECT * FROM committee_list where cl_email like '%$key%' and cl_history=0 order by cl_related DESC";
		}else {
			$query_data = "SELECT * FROM committee_list" ;
		}
	}		

	$contact_search=0;
}else{
	$query_data = "SELECT * FROM committee_list where cl_history=0 order by cl_related DESC";
	$contact_search=1;
}


$data = mysqli_query($link_generic,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);
?>


<div id=content>
<form method="POST" action="committee_list.php">
<h1>審查委員名單</h1>
<p class="rightp">
	<input class="top-button" type="button" name="view" id="view" value="總覽" onClick="self.location='committee_list.php'">
	<input class="top-button" type="button" name="insert" id="insert" value="新增委員" onClick="self.location='committee_list_insert.php'">
	<input class="top-button" type="button" name="dele" id="dele" value="刪除選取" onclick="delect_Case(false, '')"/>
	<input class="top-button" type="button" name="home" id="home" value="回首頁" onClick="self.location='/seminar/home.php'">
</p>
<hr>
<p class="rightp">
搜尋分類：
<select id="class" name="class">
	<option value='all' selected="selected">全部</option>
		<option value='related'>屆數</option>
		<option value='name'>審查委員姓名</option>
		<option value='scopes'>擅長領域</option>
		<option value='email'>Email</option>
</select>
關鍵字：<input type="text" id="keyword" name="keyword" placeholder="請輸入關鍵字">
<input type="submit" name="search" id="search" value="搜尋">
</p>

<table>
<tr >
	<td >選取</td>
	<td>屆數</td>
	<td>審查委員姓名</td>
	<td>擅長領域</td>
	<td>Email</td>
	
	<td class="down-button">修改</td>
	<td class="down-button">刪除</td>
</tr>

<?php if ($contact_search == 1 and $row_sum == 0){ ?>
	<tr><td class="data" colspan="8">目前還沒有資料</td></tr>
<?php }else if($contact_search == 0 and $row_sum == 0){ ?>
	<tr><td class="data" colspan="8">查無此資料</td></tr>
<?php } else{ do{ ?>
<tr>
	<td class="choose"><input type="checkbox" name="<?php echo $row_data['cl_related']; ?>" id="del" value="1" /></td>
	<td><?php echo $row_data['cl_related'] ?></td>
	<td><?php echo $row_data['cl_name'] ?></td>
	<td><?php echo $row_data['cl_scopes'] ?></td>
	<td><?php echo $row_data['cl_email'] ?></td>
	
	<td class="down-button"><input type="button" name="update" id="update" value="修改" onClick="self.location='committee_list_update.php?cl_related=<?php echo $row_data["cl_related"]; ?>'"></td>
	<td class="down-button"><input type="button" name="delete" id="delete" value="刪除" onclick="delect_Case(true, '<?php echo $row_data['cl_related']; ?>')"></td>
</tr>	
<?php } while($row_data = mysqli_fetch_assoc($data)); }?>
</table>
<br>
</form>
</div>
<script>
function delect_Case(single, related) {
	var dele = confirm("確定要刪除選取項目？");
	if (dele == true){
		if (single){
			location.href='committee_list_delete.php?cl_related='+related;	
		}else{
			document.getElementById('dele').type = 'submit';
		}
		
	}
}
</script>
</body>
<?php require_once('../base_footer.php')?>