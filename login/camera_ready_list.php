<?php require_once('../base_home.php');
$user = $_SESSION['re_mail'];

$sql_con = "SELECT page_content FROM page_content WHERE page_name = 'back_list'";
$result_con = mysqli_query($link,$sql_con);
$row_data_con = mysqli_fetch_assoc($result_con);

$query_camera = "SELECT camera_ready.*, upload.* FROM camera_ready, upload WHERE camera_ready.ca_upsort = upload.upsort_num and upload.up_user = '$user' ORDER BY ca_no";
$camera = mysqli_query($link,$query_camera) or die(mysqli_error());
$camera_data = mysqli_fetch_assoc($camera);
$camera_sum = mysqli_num_rows($camera);

$sql = "SELECT * FROM time_job WHERE time_no = 3";
$result = mysqli_query($link,$sql);
$row_data = mysqli_fetch_assoc($result);
$date = date("Y-m-d");

?>

<div id=content class="col-sm-9">
<h1>論文列表</h1>
<?php echo $row_data_con['page_content']?>
<div class="topnav">
<a class="label label-primary" href="/seminar/login/camera_ready_list.php?th=<?php echo $th; ?>">論文列表</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/user_update.php?th=<?php echo $th; ?>">帳號管理</a>&nbsp;&nbsp;
<?php if ($date>$row_data['job_end_date']){?>
<a class="label label-Danger" href="/seminar/login/invite.php?th=<?php echo $th; ?>">報名研討會</a>&nbsp;&nbsp;
<?php }?>
<a href="/seminar/login/logout.php?th=<?php echo $th; ?>"><?php echo $user;?>&nbsp;登出</a>
</div>
<br>
<form class="form-horizontal form-text-size" method="POST" action="camera_ready_list.php?th=<?php echo $th; ?>">
  
<table class="table table-striped">
	<thead>
		<tr>
		  <th>論文名稱</th>
		  <th>定稿上傳</th>
		  <th>授權書</th>
		  <th>定稿</th>
		</tr>
	</thead>
	
	<tbody>
		<?php if ($camera_sum == 0){ ?>
			<tr>
				<td colspan="4">目前無上傳論文!!</td>
			</tr>
		<?php } else{ do{ ?>
			 <tr>
				<td><?php echo $camera_data['up_paper']; ?></td>
				<td><?php if($camera_data['ca_file'] == NULL){ echo 'N'; }else{ echo 'Y'; }?></td>
				<td><?php if($camera_data['ca_auth'] == NULL){ echo 'N'; }else{ echo 'Y'; }?></td>
              <?php if ($date <= $row_data['job_end_date']) {?>
				<td><input class="btn btn-primary" type="button" name="camera_ready" id="camera_ready" value="定稿" onclick="self.location='camera_ready_insert.php?th=<?php echo $th; ?>&ca_upsort=<?php echo $camera_data["ca_upsort"]; ?>'" /></td> <!--修改按鈕的地方-->
              <?php }else if (($date>$row_data['job_end_date']) and ($camera_data['ca_file'] !='' and $camera_data['ca_auth'] !='')){
               echo "檢視還沒寫好不要按 按了會很可怕"?> 
               <td><input class="btn btn-warning" type="button" name="camera_ready" id="camera_ready" value="檢視" onclick="self.location='view.php?th=<?php echo $th; ?>&ca_upsort=<?php echo $camera_data["ca_upsort"]; ?>'" /></td> <!--檢視按鈕的地方-->
              <?php }else{?>
               <td></td>
               <?php }?>
			</tr>
		<?php }while($camera_data = mysqli_fetch_assoc($camera)); }?>
	</tbody>
</table>
 
</form>
</div>

</body>

<?php require_once('../base_footer.php')?>