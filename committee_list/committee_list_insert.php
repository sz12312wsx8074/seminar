<?php require_once('../base_home.php');
mysqli_select_db($link,$database);

if(isset($_POST['submit'])){
	
	if ($_POST['cl_related'] == "" or $_POST['cl_name'] == "" or $_POST['cl_scopes'] == "" or $_POST['cl_email'] == ""){
			echo "<script>alert('資料不齊全，請重新輸入。');</script>";
	}else{		
		$sqlIns = sprintf('INSERT INTO committee_list(cl_related, cl_name, cl_scopes,cl_email) values (%s, %s, %s, %s)',
		'"'.$_POST['cl_related'].'"', '"'.$_POST['cl_name'].'"', '"'.$_POST['cl_scopes'].'"', '"'.$_POST['cl_email'].'"');
		$sqlI = mysqli_query($link_generic, $sqlIns) or die('error MYSQL');
		if($sqlI){
			echo "<script>alert('已新增!!');</script>";
			exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=committee_list.php>');
		}
	}		
}
?>

<div id=content>
<h1>新增審查委員</h1><br>
<form id="form" method="POST" action="committee_list_insert.php">

<?php if(empty($_POST['cl_related']) or isset($_POST['cancel'])){ ?> <font color="red">*</font> <?php } ?>
屆數：<select class="input" id="cl_related" name="cl_related"
<?php if(isset($_POST['cancel'])){"value=''";}else if(!empty($_POST['cl_related'])){ echo "value='".$_POST['cl_related']."'";}?> required/>
<option value='1' selected="selected">1</option>
<option value='2' >2</option>
<option value='3' >3</option>
<option value='4' >4</option>
<option value='5' >5</option>
<option value='6' >6</option>
<option value='7' >7</option>
<option value='8' >8</option>
<option value='9' >9</option>
<option value='10' >10</option>
<option value='11' >11</option>
<option value='12' >12</option>
<option value='13' >13</option>
</select><br><br>

<?php if(empty($_POST['cl_name']) or isset($_POST['cancel'])){ ?> <font color="red">*</font> <?php } ?>
審查委員姓名：<input class="input" type="text" id="cl_name" name="cl_name"
<?php if(isset($_POST['cancel'])){"value=''";}else if(!empty($_POST['cl_name'])){ echo "value='".$_POST['cl_name']."'";}?> required/><br><br>

<?php if(empty($_POST['cl_scopes']) or isset($_POST['cancel'])){ ?> <font color="red">*</font> <?php } ?>
擅長領域：<textarea  class="input"  id="cl_scopes" name="cl_scopes"
<?php if(isset($_POST['cancel'])){"value=''";}else if(!empty($_POST['cl_scopes'])){ echo "value='".$_POST['cl_scopes']."'";}?> required/></textarea><br><br>

<?php if(empty($_POST['cl_email']) or isset($_POST['cancel'])){ ?> <font color="red">*</font> <?php } ?>
Email：<input class="input" type="text" id="cl_email" name="cl_email"
<?php if(isset($_POST['cancel'])){"value=''";}else if(!empty($_POST['cl_email'])){ echo "value='".$_POST['cl_email']."'";}?> required/><br><br>

<input id="clear" type="submit" name="cancel" value="清除"/>
<input id="send" type="submit" name="submit" value="送出"/>
</form>
<br>
</div>
</body>

<?php require_once('../base_footer.php')?>