<?php require_once('../base_home.php');

if(isset($_POST['search'])){
	if(empty($_POST['keyword'])){ //關鍵字是空白
		$query_data = "SELECT * FROM register ";
	}else{
		$key=$_POST['keyword'];
		if($_POST['class']=="all"){
          $query_data = "SELECT * FROM register where re_mail like '%$key%' or re_phone like '%$key%'";
        }else if($_POST['class']=="email"){
		  $query_data = "SELECT * FROM register where re_mail like '%$key%'";	
		}else if ($_POST['class']=="phone") {
		  $query_data = "SELECT * FROM register where re_phone like '%$key%'";	
		}else{
		  $query_data = "SELECT * FROM register";
		}
	}
}else{
	$query_data = "SELECT * FROM register";
}
$data = mysqli_query($link,$query_data);
$row_data = mysqli_fetch_assoc($data);
$row_sum = mysqli_num_rows($data);
?>

<div id=content class="col-sm-9">
<h1>會員查詢</h1>
<div class="topnav">
<a class="label label-pill label-success" href="/seminar/login/back_member.php?th=<?php echo $th;?>">會員查詢</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_content.php?th=<?php echo $th;?>">編輯說明文字</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_fee.php?th=<?php echo $th;?>">編輯費用</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_sendmail.php?th=<?php echo $th;?>">編輯信件</a>
</div>
<br>
  
<form class="navbar-form navbar-left" role="search" method="post" action="back_member.php?th=<?php echo $th;?>">
  <label for="sel1">搜尋分類：</label>
  <select class="form-control" id="sel1" name="class">
    <option value='all' selected="selected">全部</option>
    <option value='email'>Email</option>
    <option value='phone'>電話</option>
  </select>
  <div class="form-group">
    <input type="text" class="form-control" name="keyword" placeholder="Search">
  </div>
  <input type="submit" class="btn btn-default" name="search" value="搜尋">
</form>
  
  
<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>Email</th>
      <th>姓名</th>
      <th>電話</th>
    </tr>
  </thead>
  <tbody>
    <?php if($row_sum == 0){ ?>
    <tr>
        <td colspan="3">查無此資料</td>
    </tr>
    <?php } else { do{ ?>
	<tr>
		<td><a href="/seminar/login/member_detail.php?member=<?php echo $row_data['re_mail']?>"><?php echo $row_data['re_mail']?></a></td>
		<td><?php echo $row_data['re_lastName'].$row_data['re_firstName']?></td>
		<td><?php echo $row_data['re_phone']?></td>
	</tr>
    <?php }while($row_data = mysqli_fetch_assoc($data)); }?>
  </tbody>
</table>
  
</div>
</body>
<?php require_once('../base_footer.php')?>