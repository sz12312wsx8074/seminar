<?php require_once('../base_home.php');

$member = $_GET['member'];
$query_register = "SELECT re_mail, re_lastName, re_firstName, re_phone, re_zipcode,
re_county, re_district, re_address FROM register where register.re_mail = '$member'";
$data_register  = mysqli_query($link,$query_register );
$row_register  = mysqli_fetch_assoc($data_register );
 
 
$query_upload = "SELECT upsort_num, up_paper, up_sort FROM upload where up_user = '$member'";
$data_upload = mysqli_query($link,$query_upload);
$row_upload = mysqli_fetch_assoc($data_upload);
$row_sum = mysqli_num_rows($data_upload);
?>

<div id=content class="col-sm-9">
<h1>會員資訊</h1>
<div class="topnav">
<a class="label label-pill label-success" href="/seminar/login/back_content.php?th=<?php echo $th;?>">編輯說明文字</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_member.php?th=<?php echo $th;?>">會員查詢</a>&nbsp;&nbsp;
<a class="label label-pill label-success" href="/seminar/login/back_sendmail.php?th=<?php echo $th;?>">編輯信件</a>
</div>
<br>
<ul class="list-group">
  <li class="li-font-size">信箱：<?php echo $row_register['re_mail'];?></li>
  <li class="li-font-size">姓名：<?php echo $row_register['re_lastName'].$row_register['re_firstName'];?></li>
  <li class="li-font-size">電話：<?php echo $row_register['re_phone'];?></li>
  <li class="li-font-size">地址：<?php echo $row_register['re_zipcode'].'&nbsp;&nbsp;'.
$row_register['re_county'].$row_register['re_district'].$row_register['re_address'];?></li>
</ul>
<br>
<h3>該會員上傳之論文</h3>
<br>
 <table class="table table-striped table-hover">
  <thead>
    <tr>
      <th>論文編號</th>
      <th>論文名稱</th>
      <th>論文分類</th>
    </tr>
  </thead>
  <tbody>
    <?php if($row_sum == 0){ ?>
    <tr>
        <td colspan="3">目前沒有上傳論文</td>
    </tr>
    <?php } else { do{ ?>
	<tr>
		<td><?php echo $row_upload['upsort_num']?></a></td>
		<td><?php echo $row_upload['up_paper']?></td>
		<td><?php echo $row_upload['up_sort']?></td>
	</tr>
    <?php }while($row_upload = mysqli_fetch_assoc($data)); }?>
  </tbody>
</table> 
</div>
</body>
<?php require_once('../base_footer.php')?>