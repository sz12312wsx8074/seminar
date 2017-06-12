<?php require_once('seminar_connect.php');

if(!isset($_SESSION)){  //開啟session功能，要是已經開啟就不用在開
	session_start();
}

if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

date_default_timezone_set('Asia/Taipei');
$year = date("Y");

if(isset($_POST['submit'])){
	$query_data2 = "SELECT MAX(seminar_th) FROM seminar_th"; //抓seminar最大值
	$data2 = mysqli_query($link_generic, $query_data2) or die (mysqli_error());
	$row_data2 = mysqli_fetch_array($data2);
	if($row_data2[0]==0){ //完全沒資料時
		$new_db = "seminar_".$year;
		$sqlIns = sprintf("INSERT INTO seminar_th (seminar_th) values (%s)", '"'.$year.'"');
		$sqlI = mysqli_query($link_generic, $sqlIns) or die ("MYSQL Error");
		if($sqlI){
			$create_db = "create database $new_db default character set utf8 default collate utf8_unicode_ci";
			$sqlI_db = mysqli_query($link_generic, $create_db) or die ("MYSQL Error");
			echo "<script>alert('沒有');</script>";
			chdir('../../mysql/bin/');//到bin資料夾才有辦法運行SQL檔，所以把路徑指到bin
			exec("mysql -u root -padmin $new_db < ../../htdocs/seminar/seminar.sql");//匯入SQL檔，< 之後打SQL路徑名
			chdir('../../htdocs/seminar/');//新增完時，須把路徑指回原本地方
		}
		if (!is_dir('seminar/login/upload/'.$year.'/')) {
            $path = "login/upload/".$year;
			mkdir($path, 0777, true); //create folder
		}
        if (!is_dir('seminar/login/camera_ready/'.$year.'/')) { //怡萱create好了
            $path = "login/camera_ready/".$year;
			mkdir($path, 0777, true); //create folder
		}
		if(!(is_dir('seminar/file/'.$year.'/'))){
			$path="file/".$year;
			mkdir($path, 0777, true); //新增資料夾
		}
	}else{
		$new_year = $row_data2[0]+1;
		$sqlIns = sprintf("INSERT INTO seminar_th (seminar_th) values (%s)", '"'.$new_year.'"');
		$sqlI = mysqli_query($link_generic, $sqlIns) or die ("MYSQL Error");
		if($sqlI){
			$new_db = "seminar_".$new_year;
			$create_db = "create database $new_db default character set utf8 default collate utf8_unicode_ci";
			$sqlI_db = mysqli_query($link_generic, $create_db) or die ("MYSQL Error");
			chdir('../../mysql/bin/');//到bin資料夾才有辦法運行SQL檔，所以把路徑指到bin
			exec("mysql -u root -padmin $new_db < ../../htdocs/seminar/seminar.sql");//匯入SQL檔，< 之後打SQL路徑名
			chdir('../../htdocs/seminar/');
		}
		if (!is_dir('seminar/login/camera_ready/'.$new_year.'/')) {
            $path = "login/camera_ready/".$new_year;
			mkdir($path, 0777, true); //create folder
		}
        if (!is_dir('seminar/login/upload/'.$new_year.'/')) {
            $path = "login/upload/".$new_year;
			mkdir($path, 0777, true); //create folder
		}
		if(!(is_dir('seminar/file/'.$new_year.'/'))){
			$path="file/".$new_year;
			mkdir($path, 0777, true); //新增資料夾
		}
	}
}

if(!isset($_GET["page"])){
	$page=1;  //設定起始頁//isset 在此是判斷後方參數是否存在
	$query_data = "SELECT * FROM seminar_th order by seminar_th DESC";
}else{
	$page = intval($_GET["page"]);  //確認頁數只能夠是數值資料                 
}

$query_data = "SELECT * FROM seminar_th order by seminar_th DESC";
$data = mysqli_query($link_generic, $query_data) or die (mysqli_error());
$totalRows_data = mysqli_num_rows($data);

$per = 10; //設定每頁顯示項目數量
$pages = ceil($totalRows_data/$per); //計算總頁數
$start = ($page-1)*$per; //每頁起始資料序號,以便分次藉由sql語法去取得資料       
//每點任一分頁便執行取得該頁數的資料筆數
$result = mysqli_query($link_generic, "$query_data limit $start,$per");
$row_data = mysqli_fetch_assoc($result); 

?>


<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<div class="col-sm-offset-3 col-sm-6">
	<h1 class="text-center">觀光博彩國際研討會</h1>
	
	<form class="navbar-form" method="POST" action="seminar_th.php">
        <p class="text-right"><input type="submit" class="btn btn-primary" name="submit" value="新增屆數"></p>
		<table class="table table-striped table-hover table-text">
			<thead>
				<tr bgcolor="aquamarine" >
					<td><p class="text-center">請於此頁面選擇想要瀏覽的研討會，若想新增一屆研討會，請點擊新增屆數按鈕。</p>
					</td>
				</tr>
			</thead>
			
			<tbody>
				<?php if($totalRows_data!=0){
					do{ ?>
						<tr>
							<td align="center"><a href="/seminar/home/home.php?th=<?php echo $row_data['seminar_th']; ?>"> <?php echo $row_data['seminar_th']."年觀光博彩國際研討會"; ?></a></td>
						</tr>
					<?php }while($row_data = mysqli_fetch_assoc($result)); 
				}else{ ?>
					<tr><td align="center">目前沒有資料</td></tr>
				<?php } ?>
				
			</tbody>
		</table>

		
		<nav style="text-align:center">
			<ul class="pagination">
			<?php 
				if($totalRows_data!=0){
					echo '共 ' . $totalRows_data . ' 筆' . ' - 共 ' . $pages . ' 頁' . "<br>"; ?>
					
					<li><a href="?page=1"><span aria-hidden="true">&laquo;</span><span class="sr-only">first</span></a></li>
			
			<?php 	if($page==1){  //上一頁 ?>
						<li><a href="?page=<?php echo $page ?>"><span aria-hidden="true">&lt;</span><span class="sr-only">Previous</span></a></li><?php
					}else{ ?>
						<li><a href="?page=<?php echo $page-1 ?>"><span aria-hidden="true">&lt;</span><span class="sr-only">Previous</span></a></li><?php
					}
					
					for( $i=1 ; $i<=$pages ; $i++ ){  //列出分頁
						if ( $page-5 < $i && $i < $page+5 ){ 
							if($i==$page){ ?>
								<li class="active"><a href="?page=<?php echo $i ?>"><?php echo $i ?></a></li><?php
							}else{ ?>
								<li><a href="?page=<?php echo $i ?>"><?php echo $i ?></a></li><?php
							}
						}
					}			

					if($page==$pages){  //下一頁 ?>
						<li><a href="?page=<?php echo $page ?>"><span aria-hidden="true">&gt;</span><span class="sr-only">Next</span></a></li><?php
					}else{ ?>
						<li><a href="?page=<?php echo $page+1 ?>"><span aria-hidden="true">&gt;</span><span class="sr-only">Next</span></a></li><?php
					}?>
					
					<li><a href="?page=<?php echo $pages ?>"><span aria-hidden="true">&raquo;</span><span class="sr-only">last</span></a></li>
				
				<?php } ?>
			</ul>
		</nav>

	</form>
</div></div>