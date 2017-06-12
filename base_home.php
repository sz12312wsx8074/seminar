<?php session_start();?>
<?php require_once('seminar_connect.php');
	$location = '';
	if(isset($_SESSION['admin_id'])){
		$location = 'home';
		$sign = 'back';
	}else if(!isset($_SESSION['admin_id'])){ 
		if($_SERVER['PHP_SELF'] == "/seminar/home/home.php?th=".$th){
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=../login.php?th=$th");
		}else if($_SERVER['PHP_SELF'] == "/seminar/login.php?th=".$th){
			$location = 'login';
		}
	}
	
$query_data = "SELECT * FROM admin";
$data = mysqli_query($link_generic,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);

$sql = "SELECT * FROM accepted_list";
$result = mysqli_query($link,$sql) or die(mysqli_error());
$result_data = mysqli_fetch_assoc($result);
$result_sum = mysqli_num_rows($result);

//mysqli_select_db($link, $database);
$query_data2 = "SELECT * FROM header_img";
$data2 = mysqli_query($link, $query_data2) or die (mysqli_error());
$row_data2 = mysqli_fetch_assoc($data2);
$totalRows_data2 = mysqli_num_rows($data2);

?>

<!doctype html>
<html>
<head>
<meta charset=utf-8>
<title>觀光博彩國際研討會</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<script type="text/javascript" src="../My97DatePicker/WdatePicker.js"></script>
<script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
<link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css>
<link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css>
<link rel=stylesheet href="/seminar/css/base_home.css">
<link rel=stylesheet href="/seminar/css/content.css">
<script src="https://ajax.googleapis.com/
ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>
<body>
<div class="panel">
	<div class="navbar-header navbar-default">
		<button type="button" class="pull-right navbar-toggle collapsed btn-lg" data-toggle="collapse" data-target="#mainNav" aria-expanded="false">
			<span class="glyphicon glyphicon-th-list" id=glyphicon aria-hidden="true"></span>
		</button>   
	</div>
</div>



<button onclick="topFunction()" class="pull-right" id="myBtn" title="Go to top">
	<span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
</button>




<div id=wrapper>
<nav class="panel col-sm-3">
	<div class="container-fluid">
    <div class="collapse navbar-collapse asid-size" id="mainNav" >
        <div class="list-group">
		  <?php if($location != 'login' and $location != 'home'){ ?>
			<?php if($row_data['home'] == 1){ ?>
				<a href="/seminar/index.php?th=<?php echo $th; ?>" class="list-group-item">首頁</a><?php }?>
            <?php if($row_data['news'] == 1){ ?>
				<a href="/seminar/news/news_index.php?th=<?php echo $th; ?>" class="list-group-item">最新消息</a><?php }?>
            <?php if($row_data['organizers'] == 1){ ?>
				<a href="/seminar/organization/organizers_index.php?th=<?php echo $th; ?>" class="list-group-item">主辦單位</a><?php }?>
            <?php if($row_data['important_date'] == 1){ ?>
				<a href="/seminar/important_date/important_date_index.php?th=<?php echo $th; ?>" class="list-group-item">重要日期</a><?php }?>
            <?php if($row_data['login'] == 1){ ?>
				<a href="/seminar/login/login.php?th=<?php echo $th; ?>" class="list-group-item">會員註冊/登入</a><?php }?>
            <?php if($row_data['paper_format'] == 1){ ?>
				<a href="/seminar/paper_format/paper_format_search.php?th=<?php echo $th; ?>" class="list-group-item">論文格式</a><?php }?>
            <?php if($row_data['accpet_list'] == 1){ 
				if($result_data['ac_pdf'] != NULL){?>
					<a target="_blank" href="/seminar/accepted_list/accepted_list_file/<?php echo $result_data['ac_pdf'];?>" class="list-group-item">接受列表</a><?php }?>
				<?php }?>
            <?php if($row_data['best_paper'] == 1){ ?>
				<a href="/seminar/best_paper/best_paper_search.php?th=<?php echo $th; ?>" class="list-group-item">最佳論文獎</a><?php }?>
            <?php if($row_data['current_committee'] == 1){ ?>
				<a href="/seminar/current_committee/current_committee_search.php?th=<?php echo $th; ?>"class="list-group-item">本屆審查委員名單</a><?php }?>
            <?php if($row_data['program'] == 1){ ?>
				<a href="/seminar/program/program_search.php?th=<?php echo $th; ?>" class="list-group-item">議程</a><?php }?>
            <?php if($row_data['oral_session'] == 1){ ?>
				<a href="/seminar/oral_session/oral_session_search.php?th=<?php echo $th; ?>" class="list-group-item">發表議程</a><?php }?>
            <?php if($row_data['poster_session'] == 1){ ?>
				<a href="/seminar/poster_session/poster_session_search.php?th=<?php echo $th; ?>" class="list-group-item">海報議程</a><?php }?>
            <?php if($row_data['special_session'] == 1){ ?>
				<a href="/seminar/special_session/special_session_search.php?th=<?php echo $th; ?>" class="list-group-item">特別議程</a><?php }?>
            <?php if($row_data['instructions_for_poster'] == 1){ ?>
				<a href="/seminar/instructions_for_poster/instructions_for_poster_search.php?th=<?php echo $th; ?>" class="list-group-item">論文海報說明</a><?php }?>
            <?php if($row_data['banquet'] == 1){ ?>
				<a href="/seminar/banquet/banquet_search.php?th=<?php echo $th; ?>" class="list-group-item">晚宴</a><?php }?>
            <?php if($row_data['transportation'] == 1){ ?>
				<a href="/seminar/transportation/transportation_search.php?th=<?php echo $th; ?>" class="list-group-item">交通指南</a><?php }?>
            <?php if($row_data['shuttle_bus'] == 1){ ?>
				<a href="/seminar/shuttle_bus/shuttle_bus_search.php?th=<?php echo $th; ?>" class="list-group-item">接駁車</a><?php }?>
            <br>
            <br>
	    <?php } ?>

        <?php if ($location == 'home' and $sign == 'back'){?>
			<?php if($row_data['home'] == 1){ $check = 'checked';}else{$check='';}?>              
				<a  href="/seminar/home/home.php?th=<?php echo $th; ?>" class="list-group-item">
					<input type="checkbox" class="check" name="check" value="home" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=home'" <?php echo $check;?>/>
					首頁
				</a>
		<?php if($row_data['news'] == 1){ $check = 'checked';}else{$check='';}?>
			<a class="list-group-item" href="/seminar/news/news.php?th=<?php echo $th; ?>">
				<input type="checkbox" class="check" name="check" value="news" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=news'" <?php echo $check;?>/>
				最新消息
			</a>

		<?php if($row_data['organizers'] == 1){ $check = 'checked';}else{$check='';}?>
			<a class="list-group-item" href="/seminar/organization/organizers.php?th=<?php echo $th; ?>">
				<input type="checkbox" class="check" name="check" value="organizers" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=organizers'" <?php echo $check;?>/>
				主辦單位
			</a>

		<?php if($row_data['important_date'] == 1){ $check = 'checked';}else{$check='';}?>
		  <a class="list-group-item" href="/seminar/important_date/important_date.php?th=<?php echo $th; ?>">	
			<input type="checkbox" class="check" name="check" value="important_date" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=important_date'" <?php echo $check;?>/>
			重要日期
		  </a>

		<?php if($row_data['login'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/login/back_member.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="login" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=login'" <?php echo $check;?>/>
			會員管理
		</a>

		<?php if($row_data['paper_format'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/paper_format/paper_format.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="paper_format" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=paper_format'" <?php echo $check;?>/>
			論文格式
		</a>

		<?php if($row_data['accpet_list'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/accepted_list/accepted_list.php?th=<?php echo $th; ?>">		
			<input type="checkbox" class="check" name="check" value="accpet_list" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=accpet_list'" <?php echo $check;?>/>
			接受列表
		</a>

		<?php if($row_data['best_paper'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/best_paper/best_paper.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="best_paper" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=best_paper'" <?php echo $check;?>/>
			最佳論文獎
		</a>

		<?php if($row_data['program'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/program/program.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="program" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=program'" <?php echo $check;?>/>		
			議程
		</a>

		<?php if($row_data['oral_session'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/oral_session/oral_session.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="oral_session" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=oral_session'" <?php echo $check;?>/>
			發表議程
		</a>

		<?php if($row_data['poster_session'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/poster_session/poster_session.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="poster_session" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=poster_session'" <?php echo $check;?>/>
			海報議程
		</a>

		<?php if($row_data['special_session'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/special_session/special_session.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="special_session" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=special_session'" <?php echo $check;?>/>
			特別議程
		</a>

		<?php if($row_data['instructions_for_poster'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/instructions_for_poster/instructions_for_poster.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="instructions_for_poster" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=instructions_for_poster'" <?php echo $check;?>/>
			論文海報說明
		</a>

		<?php if($row_data['banquet'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/banquet/banquet.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="banquet" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=banquet'" <?php echo $check;?>/>
			晚宴
		</a>

		<?php if($row_data['transportation'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/transportation/transportation.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="transportation" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=transportation'" <?php echo $check;?>/>
			交通指南
		</a>

		<?php if($row_data['shuttle_bus'] == 1){ $check = 'checked';}else{$check='';}?>
		<a class="list-group-item" href="/seminar/shuttle_bus/shuttle_bus.php?th=<?php echo $th; ?>">
			<input type="checkbox" class="check" name="check" value="shuttle_bus" onclick="self.location='/seminar/ctrl_list.php?th=<?php echo $th; ?>&check=shuttle_bus'" <?php echo $check;?>/>
			接駁車
		</a>

		<a class="list-group-item" href="/seminar/list/list.php?th=<?php echo $th; ?>">派搞</a>
		<a class="list-group-item" href="/seminar/login/camera_ready.php?th=<?php echo $th; ?>">定稿</a>
		<a class="list-group-item" href="/seminar/current_committee/current_committee.php?th=<?php echo $th; ?>">本屆審查委員名單</a>
		<a class="list-group-item" href="/seminar/review/review_result.php?th=<?php echo $th; ?>">審查結果</a>
		<a class="list-group-item" href="/seminar/preferences/sort.php?th=<?php echo $th; ?>">本屆論文分類</a>
		<a class="list-group-item" href="/seminar/time_job/time_job.php?th=<?php echo $th; ?>">時間設定</a>
		<a class="list-group-item" href="/seminar/admin_update.php?th=<?php echo $th; ?>">修改帳密</a>
		<a class="list-group-item" href="/seminar/seminar_th.php">屆數選擇</a>
		<a class="list-group-item" href="/seminar/logout.php?th=<?php echo $th; ?>">登出</a>
		
		<li class="list-group-item"><input class="btn default btn-block" type="button" <?php if(!$button_on){ echo 'disabled="disabled"'; } ?> name="header_insert" id="header_insert" value="修改頁首圖片" onClick="window.open('/seminar/header_img/header_img_insert.php?th=<?php echo $th; ?> ', '編輯分類', config='height=500,width=500');"/></div>
		</li>
		<?php }?>
        </div> <!--nav navbar-nav-->
    </div>  <!--mainNav-->
  </div><!-- container-fluid -->
</nav>

<header class="hidden-sm">
	<div>
		<img src="/seminar/header_img/<?php echo $row_data2['header_img'];?>" width="100%" height="100%"/>
	</div>
</header>


<script>
// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 40 || document.documentElement.scrollTop > 40) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
</script>
