<?php require_once('../base_home.php');

if($_GET){
  $upsort = $_GET['ca_upsort'];
}else{
  $upsort = $_POST['ca_upsort'];
}

$sql = "SELECT * FROM camera_ready where ca_upsort = '$upsort'";
$result = mysqli_query($link,$sql) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($result);

?>
<style>
iframe{
  margin-bottom: 50px;
}
</style>
<div id=content class="col-sm-9">
<h4>定稿論文：</h4>

<font size="4"><b><a href="../login/camera_ready/<?php echo $th.'/'.$row_data['ca_file']?>"><?php echo $row_data['ca_file'];?></a></b></font><br><br>
<font size="4"><b><a href="../login/camera_ready/<?php echo $th.'/'.$row_data['ca_auth']?>"><?php echo $row_data['ca_auth'];?></a></b></font><br><br>

<input type="button" name="back" class="btn btn-default" value="回上一頁" onclick="self.location='camera_ready_list.php?th=<?php echo $th; ?>'"><br><br>
</div>


<?php require_once('../base_footer.php')?>