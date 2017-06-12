<?php require_once('../base_home.php');
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

if(isset($_POST['submit'])){
	if ($_POST['sb_shift'] == "" or $_POST['sb_explanation'] == ""  or $_POST['sb_line'] == "" or $_POST['sb_time'] == ""){
			echo "<script>alert('資料不齊全，請重新輸入。');</script>";
	}else{		
	
		$sqlIns = sprintf('INSERT INTO shuttle_bus(sb_shift, sb_explanation ,sb_line , sb_time) values (%s, %s, %s ,%s)',
		'"'.$_POST['sb_shift'].'"', '"'.$_POST['sb_explanation'].'"','"'.$_POST['sb_line'].'"', '"'.$_POST['sb_time'].'"');
		$sqlI = mysqli_query($link, $sqlIns) or die('error MYSQL');
		if($sqlI){
			echo "<script>alert('已新增!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=shuttle_bus.php?th=$th>");
		}
	}		
}
?>


<div id=content class="col-sm-9">

<form class="form-horizontal form-text-size" role="form" method="POST" action="shuttle_bus_insert.php?th=<?php echo $th; ?>">
<h1>新增接駁車</h1>
<hr>
 <div class="form-group">
    <label class="control-label col-sm-2" for="sb_shift"><font color="red">*</font>班次：</label>
    <div class="col-sm-10">
      <input type="sb_shift" id="sb_shift" name="sb_shift" size="30" <?php if(!empty($_POST['sb_shift'])){ echo "value='".$_POST['sb_shift']."'";}?> required>
    </div>
  </div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="sb_explanation"><font color="red">*</font>說明：</label>
    <div class="col-sm-10">
      <input type="sb_explanation" id="sb_explanation" name="sb_explanation" size="30" <?php if(!empty($_POST['sb_explanation'])){ echo "value='".$_POST['sb_explanation']."'";}?> required>
    </div>
  </div>
  
  <div class="form-group">
	<label class="control-label col-sm-2"><font color="red">*</font>單程 / 來回：</label>
	<div class="col-sm-3">
		<select class="form-control" name="sb_line" id="sb_line">
			<option selected="selected">單程</option>
			<option>來回</option>
		</select>
	</div>
</div>
  
  <div class="form-group">
    <label class="control-label col-sm-2" for="sb_time"><font color="red">*</font>發車時間：</label>
    <div class="col-sm-10">
      <input type="sb_time" id="sb_time" name="sb_time" size="30" <?php if(!empty($_POST['sb_time'])){ echo "value='".$_POST['sb_time']."'";}?> required>
    </div>
  </div>




<div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <input type="submit" class="btn btn-primary" name="submit" value="送出">
    </div>
  </div>
</form>
<br>
</div>
</body>

<?php require_once('../base_footer.php')?>