<?php require_once('../seminar_connect.php');   //改版改這裡~~~~~~~~
if(!isset($_SESSION['cc_email'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/current_committee/cg_login.php"</script>');
	return;
}

$query_data4 = "SELECT * FROM time_job where time_no = '2'";
$data4 = mysqli_query($link, $query_data4) or die (mysqli_error());
$row_data4 = mysqli_fetch_assoc($data4);
$totalRows_data4 = mysqli_num_rows($data4);

$starttime = $row_data4['job_start_date'];
$endtime = $row_data4['job_end_date'];
$today = date("Y-m-d");

if((strtotime($today) >= strtotime($starttime))AND(strtotime($today) <= strtotime($endtime))){
}elseif((strtotime($today) > strtotime($endtime)) and $endtime!=null ){
	echo "<script>alert('審稿時間已經結束!');</script>";
	exit ('<script>location.href="/seminar/current_committee/cg_login.php"</script>');
}else{
	echo "<script>alert('審稿時間尚未開始!');</script>";
	exit ('<script>location.href="/seminar/current_committee/cg_login.php"</script>');
}

$rw_num = $_GET["rw_num"];

$email = $_SESSION['cc_email'];
$query_data3 = "SELECT * FROM list where cc_email = '$email'";
$data3 = mysqli_query($link, $query_data3) or die (mysqli_error());
$row_data3 = mysqli_fetch_assoc($data3);
$totalRows_data3 = mysqli_num_rows($data3);


$query_data = "SELECT * FROM upload WHERE upsort_num = '$rw_num'";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);


$query_data2 = "SELECT * FROM review where rw_num = '$rw_num' and cc_email = '$email'";  //查詢已審過否
$data2 = mysqli_query($link, $query_data2) or die (mysqli_error());
$row_data2 = mysqli_fetch_assoc($data2);
$totalRows_data2 = mysqli_num_rows($data2);

$score = 0;

if(isset($_POST['submit'])){
	$score = $_POST['score1']+$_POST['score2']+$_POST['score3']+$_POST['score4']+$_POST['score5'];
	$sqlIns = sprintf("INSERT INTO review(rw_num, rw_score, rw_suggest, rw_recommend, cc_email, score1, score2, score3, score4, score5) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)", '"'.$rw_num.'"','"'.$score.'"','"'.$_POST['rw_suggest'].'"','"'.$_POST['rw_recommend'].'"','"'.$email.'"','"'.$_POST['score1'].'"','"'.$_POST['score2'].'"','"'.$_POST['score3'].'"','"'.$_POST['score4'].'"','"'.$_POST['score5'].'"');
	$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
	echo "<script>alert('儲存完成!!');</script>";
	exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=review.php>');
}

if(isset($_POST['update'])){
	$score = $_POST['score1']+$_POST['score2']+$_POST['score3']+$_POST['score4']+$_POST['score5'];
	$sqlUpd = sprintf("UPDATE review SET rw_num = '%s',rw_score = '%s',rw_suggest= '%s',rw_recommend = '%s',cc_email = '%s',score1 = '%s',score2 = '%s',score3 = '%s',score4 = '%s',score5 = '%s'WHERE rw_num = '%s' and cc_email = '%s'"	,$rw_num,$score,$_POST['rw_suggest'],$_POST['rw_recommend'],$email,$_POST['score1'],$_POST['score2'],$_POST['score3'],$_POST['score4'],$_POST['score5'],$rw_num,$email);
	$sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
	echo "<script>alert('儲存完成!!');</script>";
	exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=review.php>');
}

if(isset($_POST['countscore'])){
	$score = $_POST['score1']+$_POST['score2']+$_POST['score3']+$_POST['score4']+$_POST['score5'];
}

?>


<html>
<script src="https:ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="jquery.twzipcode.min.js"></script>
<link rel="stylesheet" href="zip.css">
<div id=content class="col-md-8 col-md-offset-2">    <!--改版改這裡~~~~~~~ -->
<!--class="form-control"-->
<form method="POST" action="review_insert.php?rw_num=<?php echo $rw_num; ?>" class="form-horizontal form-text-size" role="form" enctype="multipart/form-data">
<h1 class="text-center" >審稿</h1>
<script>tinymce.init({selector:'textarea',width:575, height:200});</script>  <!--設定文字編輯器的大小-->
<input type="button" class="col-sm-offset-9 btn btn-primary" name="back" id="back" value="回審稿列表" onClick="self.location='review.php'">
<input type="button" class="btn btn-primary" name="update" value="登出" onClick="self.location='/seminar/current_committee/cg_logout.php'">
<br><br>
<table class="table table-striped table-hover">
<tr ><td>論文編號</td>
	<td ><?php echo $rw_num; ?></td></tr>

<tr ><td>論文名稱</td>
	<?php if(mb_strlen($row_data['up_paper'],'utf-8')<=50){?>
		<td><?php echo $row_data['up_paper']; ?></a></td> <?php }else{ ?>
		<td><?php echo substr($row_data['up_paper'], 0, 45); ?></a></td></tr> <?php } ?>

<tr ><td>論文摘要</td>
	<td ><?php echo $row_data['up_summary'];?></td></tr>

<tr ><td>分類</td>
	<td ><?php echo $row_data['up_sort'];?></td></tr>

<tr ><td>網頁瀏覽論文</td>
	<td ><?php if($row_data['up_pdf']!=null){?>
				<a Target="_blank" href="../login/upload/<?php echo $row_data['up_pdf']; ?>">PDF網頁瀏覽</a>
		<?php }else{
				echo"無檔案可供瀏覽";
			}?>
	</td></tr>
<tr ><td>論文下載</td>
	<td ><?php if(($row_data['up_pdf']==null) AND ($row_data['up_word']==null)){ 
				echo"無上傳檔案";
			}elseif($row_data['up_pdf']!=null){?>
				<a href="../login/upload/downloadfile.php?up_word=<?php echo $row_data['up_pdf']; ?>">PDF檔下載</a>
		<?php }elseif($row_data['up_word']!=null){?>
				<a href="../login/upload/downloadfile.php?up_word=<?php echo $row_data['up_word']; ?>">WORD檔下載</a>
		<?php }else{ ?>
				<a href="../login/upload/downloadfile.php?up_word=<?php echo $row_data['up_pdf']; ?>">PDF檔下載</a>&nbsp;&nbsp;&nbsp;
				<a href="../login/upload/downloadfile.php?up_word=<?php echo $row_data['up_word']; ?>">WORD檔下載</a>
		<?php } ?>
	</td></tr>
</table>
<br>
<table class="table table-striped table-hover">
<tr ><td>NO.</td><td>審查項目</td><td>優<br>(5分)</td><td>良好<br>(4分)</td><td>普通<br>(3分)</td><td>差<br>(2分)</td><td>極差<br>(1分)</td>
</tr >
<tr >
	<td>1</td><td >論文品質(論文寫作之完善性)</td>
		<td ><input type="radio" name="score1" value="20" <?php if(isset($_POST['score1'])){if($_POST['score1']=="20"){ ?> checked <?php }}elseif($row_data2['score1']=="" or $row_data2['score1']=="20" ){ ?> checked <?php } ?>></td>
		<td ><input type="radio" name="score1" value="16" <?php if(isset($_POST['score1'])){if($_POST['score1']=="16"){ ?> checked <?php }}elseif($row_data2['score1']=="16" ){ ?> checked <?php } ?>></td>
		<td ><input type="radio" name="score1" value="12" <?php if(isset($_POST['score1'])){if($_POST['score1']=="12"){ ?> checked <?php }}elseif($row_data2['score1']=="12" ){ ?> checked <?php } ?>></td>
		<td ><input type="radio" name="score1" value="8"  <?php if(isset($_POST['score1'])){if($_POST['score1']=="8"){ ?> checked <?php }}elseif($row_data2['score1']=="8" ){ ?> checked <?php } ?>></td>
		<td ><input type="radio" name="score1" value="4"  <?php if(isset($_POST['score1'])){if($_POST['score1']=="4"){ ?> checked <?php }}elseif($row_data2['score1']=="4" ){ ?> checked <?php } ?>></td>
	
</tr>
<tr ><td>2</td><td >論文原創性(觀念或方法之創新)</td>
	<td ><input type="radio" name="score2" value="20" <?php if(isset($_POST['score2'])){if($_POST['score2']=="20"){ ?> checked <?php }}elseif(($row_data2['score2']=="")OR($row_data2['score2']=="20")){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score2" value="16" <?php if(isset($_POST['score2'])){if($_POST['score2']=="16"){ ?> checked <?php }}elseif($row_data2['score2']=="16"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score2" value="12" <?php if(isset($_POST['score2'])){if($_POST['score2']=="12"){ ?> checked <?php }}elseif($row_data2['score2']=="12"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score2" value="8"  <?php if(isset($_POST['score2'])){if($_POST['score2']=="8"){ ?> checked <?php }}elseif($row_data2['score2']=="8"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score2" value="4"  <?php if(isset($_POST['score2'])){if($_POST['score2']=="4"){ ?> checked <?php }}elseif($row_data2['score2']=="4"){ ?> checked <?php } ?>></td>
</tr>
<tr ><td>3</td><td >論文架構及內容之充實性(含重要文獻之蒐集及分析)</td>
	<td ><input type="radio" name="score3" value="20" <?php if(isset($_POST['score3'])){if($_POST['score3']=="20"){ ?> checked <?php }}elseif(($row_data2['score3']=="")OR($row_data2['score3']=="20")){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score3" value="16" <?php if(isset($_POST['score3'])){if($_POST['score3']=="16"){ ?> checked <?php }}elseif($row_data2['score3']=="16"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score3" value="12" <?php if(isset($_POST['score3'])){if($_POST['score3']=="12"){ ?> checked <?php }}elseif($row_data2['score3']=="12"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score3" value="8"  <?php if(isset($_POST['score3'])){if($_POST['score3']=="8"){ ?> checked <?php }}elseif($row_data2['score3']=="8"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score3" value="4"  <?php if(isset($_POST['score3'])){if($_POST['score3']=="4"){ ?> checked <?php }}elseif($row_data2['score3']=="4"){ ?> checked <?php } ?>></td>
</tr>
<tr ><td>4</td><td >論文寫作之完善性(結構及章節安排與參考文獻之正確性)</td>
	<td ><input type="radio" name="score4" value="20" <?php if(isset($_POST['score4'])){if($_POST['score4']=="20"){ ?> checked <?php }}elseif(($row_data2['score4']=="")OR($row_data2['score4']=="20")){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score4" value="16" <?php if(isset($_POST['score4'])){if($_POST['score4']=="16"){ ?> checked <?php }}elseif($row_data2['score4']=="16"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score4" value="12" <?php if(isset($_POST['score4'])){if($_POST['score4']=="12"){ ?> checked <?php }}elseif($row_data2['score4']=="12"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score4" value="8"  <?php if(isset($_POST['score4'])){if($_POST['score4']=="8"){ ?> checked <?php }}elseif($row_data2['score4']=="8"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score4" value="4"  <?php if(isset($_POST['score4'])){if($_POST['score4']=="4"){ ?> checked <?php }}elseif($row_data2['score4']=="4"){ ?> checked <?php } ?>></td>
</tr>
<tr ><td>5</td><td >學術理論之貢獻或實務應用之價值(研究成效)</td>
	<td ><input type="radio" name="score5" value="20" <?php if(isset($_POST['score5'])){if($_POST['score5']=="20"){ ?> checked <?php }}elseif(($row_data2['score5']=="")OR($row_data2['score5']=="20")){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score5" value="16" <?php if(isset($_POST['score5'])){if($_POST['score5']=="16"){ ?> checked <?php }}elseif($row_data2['score5']=="16"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score5" value="12" <?php if(isset($_POST['score5'])){if($_POST['score5']=="12"){ ?> checked <?php }}elseif($row_data2['score5']=="12"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score5" value="8"  <?php if(isset($_POST['score5'])){if($_POST['score5']=="8"){ ?> checked <?php }}elseif($row_data2['score5']=="8"){ ?> checked <?php } ?>></td>
	<td ><input type="radio" name="score5" value="4"  <?php if(isset($_POST['score5'])){if($_POST['score5']=="4"){ ?> checked <?php }}elseif($row_data2['score5']=="4"){ ?> checked <?php } ?>></td>
</tr>
	
<tr>
	<td>
		<input type="submit" class="btn btn-default" name="countscore" value="試算分數"/>
	</td>
	<td colspan="6"><input type="text" id="rw_score" name="rw_score" readonly=readonly; value="<?php if($score!=0){ echo $score; }elseif($row_data2['rw_score']!=0){echo $row_data2['rw_score'];} ?>"></td></tr>

<tr ><td>建議</td>
	<td colspan="6"><textarea id="rw_suggest" name="rw_suggest"><?php if(!empty($_POST['rw_suggest'])){echo $_POST['rw_suggest'];}else{echo $row_data2['rw_suggest'];} ?></textarea>
</td></tr>

<tr><td>是否推薦</td>
<?php if(!empty($_POST['rw_recommend'])){ ?>
	<td colspan="6">
		<input type="radio" name="rw_recommend" value="1" <?php if($_POST['rw_recommend']==1){ ?> checked <?php } ?> >是
		<input type="radio" name="rw_recommend" value="0" <?php if($_POST['rw_recommend']==0){ ?> checked <?php } ?> >否
	</td>
<?php }else{ ?>
	<td colspan="6">
		<input type="radio" name="rw_recommend" value="1" <?php if($row_data2['rw_recommend']==1){ ?> checked <?php } ?> >是
		<input type="radio" name="rw_recommend" value="0" <?php if($row_data2['rw_recommend']==0){ ?> checked <?php } ?> >否
	</td>
<?php } ?>
</tr>
	
</table>
<br>

<?php if($totalRows_data2==0){ ?>
	<input type="submit" class=" col-sm-offset-11 btn btn-primary" name="submit" id="submit" value="儲存">
<?php }else{ ?>
	<input type="submit" class=" col-sm-offset-11 btn btn-primary" name="update" id="update" value="儲存">
<?php } ?>

<br><br>
</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>