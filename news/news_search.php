<?php require_once('../base_home.php');

$news_no = $_GET["news_no"];


$query_data = "SELECT * FROM news where news_no='$news_no'";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);

$query_file = "SELECT * FROM news_file WHERE news_no='$news_no'";
$file = mysqli_query($link, $query_file) or die (mysqli_error());
$file_data = mysqli_fetch_assoc($file);
?>




<html>

<div id=content class="col-sm-9"> 




<form method="POST" action="news_select.php?th=<?php echo $th ?>">
<div class="page-header">
	<h1>最新消息</h1>
</div>

<input type="button" class="btn btn-primary" name="up" id="uppage" value="上一頁" onClick="self.location='news_index.php?th=<?php echo $th ?>'">
<br>
<body>

<table class="table table-striped table-hover table-text">
<thead>
	<br>
	<tr>
		<th>標題：</th>
		<td><?php echo $row_data['news_title'];?></td>
	</tr>

	<tr>
		<th>日期：</th>
		<td><?php echo $row_data['news_date'];?></td>
	</tr>


	<tr>
		<th>內容：</th>
		<td><?php echo $row_data['news_contant'];?></td>
	</tr>


	<tr>
		<th>檔案：</th>
		<td class="nevin">
			<?php do{
				if($file_data['news_no'] == $news_no){ ?>
					<a Target="_blank" href="../news/news_file/<?php echo $file_data['file_name'];?>"><?php echo $file_data['file_name'];?></a>
					<?php echo '<br>'; 
				}
			}while($file_data= mysqli_fetch_assoc($file)); ?>
			</td>
	</tr>
</thead>
</table>

</body>
</form>
</div>
</html>






<?php require_once('../base_footer.php')?>