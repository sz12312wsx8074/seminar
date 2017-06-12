<?php require_once('../base_home.php');
$user = $_SESSION['re_mail'];

$sql_time = "SELECT * FROM time_job where time_no = 1"; // 期間
$result_time = mysqli_query($link,$sql_time);
$time_data = mysqli_fetch_assoc($result_time);

$current_time = date("Y-m-d");
if ($current_time > $time_data['job_end_date']){
    echo "<script>alert('以超過論文截稿時間!');</script>";
	exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=login.php?th=$th>");
	return;
}

$sql_user = "SELECT re_wright FROM register where re_mail = '$user'";
$result_user = mysqli_query($link,$sql_user);
$user_data = mysqli_fetch_assoc($result_user);

$query_data = "SELECT up_no, up_paper, up_status, up_upload FROM upload where up_user = '$user'";
$data = mysqli_query($link,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);

$sql_con = "SELECT page_content FROM page_content where page_name = 'back_list'";
$result_con = mysqli_query($link,$sql_con);
$row_data_con = mysqli_fetch_assoc($result_con);


?>

<div id=content class="col-sm-9">
<h1>論文列表</h1>
<p><?php echo $row_data_con['page_content'];?></p>
<div class="topnav">
<?php if ($user_data['re_wright'] == 1){?>
<a class="label label-primary" href="/seminar/login/paper.php?th=<?php echo $th; ?>">上傳論文</a>&nbsp;&nbsp;
<a class="label label-primary" href="/seminar/login/paper_list.php?th=<?php echo $th; ?>">論文列表</a>&nbsp;&nbsp;
<?php }?>
<a class="label label-primary" href="/seminar/login/user_update.php?th=<?php echo $th; ?>">帳號管理</a>&nbsp;&nbsp;
<a href="/seminar/login/logout.php?th=<?php echo $th; ?>"><?php echo $user;?>&nbsp;登出</a>
</div>
<br>
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>論文名稱</th>
      <th>狀態</th>
      <th>論文上傳</th>
      <th>修改</th>
      <th>刪除</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($row_sum == 0){ ?>
    <tr>
        <td colspan="5">目前無上傳論文!!</td>  
    </tr>
    <?php } else{ do{?>
    <tr>
        <td><?php echo $row_data['up_paper']; ?></td>
        <td><?php echo $row_data['up_status']; ?></td>
        <?php if($row_data['up_upload'] == '0'){
            $upload = 'N';?>
            <td><?php echo $upload; ?></td>
        <?php }else{
            $upload = 'Y';?>
            <td><?php echo $upload; ?></td>
        <?php }?>

        <td><input type="button" name="update" id="update" value="修改" onclick="self.location='paper_update.php?th=<?php echo $th; ?>&up_no=<?php echo $row_data["up_no"]; ?>'"></td> <!--修改按鈕的地方-->
        <td><input type="button" name="delete" id="delete" value="刪除"  onclick="delete_Case('<?php echo $row_data['up_no']; ?>')"></td>
    </tr>	
    <?php }while($row_data = mysqli_fetch_assoc($data)); }?>
  </tbody>
</table>  
</div>

<script>
function delete_Case(no) {
	var dele = confirm("確定要刪除此篇論文？");
	if (dele == true){
		location.href='paper_delete.php?th=<?php echo $th;?>&up_no='+no;
	}
}
</script>

</body>

<?php require_once('../base_footer.php')?>