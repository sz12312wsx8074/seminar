<?php require_once('../base_home.php');

mysqli_select_db($link,$database);

if(isset($_POST['search'])){
	if(empty($_POST['keyword'])){ //關鍵字是空值
		if($_POST['class']=="all"){
			$query_data = "SELECT * FROM committee_list where cl_history=0 order by cl_email DESC";
		}
	}
	if(!empty($_POST['keyword'])){//關鍵字不是空值
		$key=$_POST['keyword'];
		if($_POST['class']=="all"){
			$query_data = "SELECT * FROM committee_list where (cl_name like '%$key%' 
			              or cl_scopes like '%$key%' or cl_email like '%$key%') 
						  and cl_history=0 order by cl_email DESC";
		}else if($_POST['class']=="name"){
			$query_data = "SELECT * FROM committee_list where cl_name like '%$key%' and cl_history=0 order by cl_email DESC";
		}else if($_POST['class']=="scopes"){
			$query_data = "SELECT * FROM committee_list where cl_scopes like '%$key%' and cl_history=0 order by cl_email DESC";
		}else if($_POST['class']=="email"){
			$query_data = "SELECT * FROM committee_list where cl_email like '%$key%' and cl_history=0 order by cl_email DESC";
		}
	}
	$contact_search=0;
}else{
	$query_data = "SELECT * FROM committee_list where cl_history=0 order by cl_email DESC";
	$contact_search=1;
}


$data = mysqli_query($link_generic,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);
?>


<div id=content>
<form method="POST" action="committee_list_search.php">

<h1>審查委員名單</h1>

<p class="rightp">
搜尋分類：
<select id="class" name="class">
	<option value='all' selected="selected">全部</option>
		<option value='name'>審查委員姓名</option>
		<option value='scopes'>擅長領域</option>
		<option value='email'>Email</option>
</select>
關鍵字：<input type="text" id="keyword" name="keyword" placeholder="請輸入關鍵字">
<input type="submit" name="search" id="search" value="搜尋">
<input class="top-button" type="button" name="view" id="view" value="總覽" onClick="self.location='committee_list_search.php'">
</p>

<table>
<tr id="title">
	<td class="choose">審查委員姓名</td>
	<td>擅長領域</td>
	<td>Email</td>
</tr>

<?php if ($contact_search == 1 and $row_sum == 0){ ?>
	<tr><td colspan="5" class="data">目前還沒有資料</td></tr>
<?php }else if($contact_search == 0 and $row_sum == 0){ ?>
	<tr><td colspan="5" class="data">查無此資料</td></tr>
<?php } else{	
do{ ?>
<tr>
	<td class="choose"><?php echo $row_data['cl_name']."　"; ?></td>
	<td><?php echo $row_data['cl_scopes']."　";?></td>
	<td><?php echo $row_data['cl_email']."　";?></td>
</tr>	
<?php } while($row_data = mysqli_fetch_assoc($data)); }?>
</table>
</form>
<br>
</div>
</body>
<?php require_once('../base_footer.php')?>