<?php
if(!isset($_SESSION)){  //開啟session功能，要是已經開啟就不用在開
	session_start();
}

if(($_SERVER['PHP_SELF'] == "/seminar/home/home.php") and (!isset($_SESSION['admin_id']))){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
}

$hostname="localhost";
$username="root";
$password="admin";

//--------------------------------

$database = "seminar_generic_data";
$link = mysqli_connect($hostname, $username, $password, $database) or die ("Could not connect MySQL");
mysqli_select_db($link, $database) or die ("Could not select database");
mysqli_query($link, "SET NAMES 'utf8'");

$check = "select * from seminar_th";
$data = mysqli_query($link,$check) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);

$history_year = array();
do{              //把歷年push進陣列
  array_push($history_year, $row_data['seminar_th']);
}while($row_data = mysqli_fetch_assoc($data));
$year_num = count($history_year);
$err = 0;

if (!isset($_GET["th"])){  //如果URL 沒有th變數  強制顯示當年度
	$data = mysqli_query($link,$check) or die(mysqli_error());
	$row_data = mysqli_fetch_assoc($data);
	$th = $row_data['seminar_th'];
  list($link, $button_on) = basic($th, $hostname, $username, $password);
}

if(isset($_GET["th"])){  //如果URL 有th變數
  $th = $_GET["th"];
  for ($i=0;$i<$year_num;$i++){
    if ($th == $history_year[$i]){
      list($link, $button_on) = basic($th, $hostname, $username, $password);  //th 變數符合資料庫
    }else {
      $err +=1;
    }
  }
  if ($err == $year_num){  //th 變數不符合資料庫 強制顯示當年度
    $th = date("Y"); 
    list($link, $button_on) = basic($th, $hostname, $username, $password);
  }
}else if(($_SERVER['PHP_SELF'] == "/seminar/current_committee/cg_login.php")  // 審稿委員端
         or ($_SERVER['PHP_SELF'] == "/seminar/current_committee/cg_logout.php")
         or ($_SERVER['PHP_SELF'] == "/seminar/review/review.php")
         or ($_SERVER['PHP_SELF'] == "/seminar/review/review_insert.php")){
    date_default_timezone_set('Asia/Taipei');
    $th_year = date("Y");
    $th = $th_year;
    $database = "seminar_".$th;
    $link = mysqli_connect($hostname, $username, $password, $database) or die ("Could not connect MySQL");
    mysqli_select_db($link, $database) or die ("Could not select database");
    mysqli_query($link, "SET NAMES 'utf8'");
}


function basic($th, $hostname, $username, $password){
    $database = "seminar_".$th;
	$link = mysqli_connect($hostname, $username, $password, $database) or die ("Could not connect MySQL");
	mysqli_select_db($link, $database) or die ("Could not select database");
	mysqli_query($link, "SET NAMES 'utf8'");
	
	date_default_timezone_set('Asia/Taipei');
	$now_date = date("Y-m-d");
	$button_on = true;
	
	$query_data = "SELECT * FROM time_job where time_no='5'";
	$data = mysqli_query($link, $query_data) or die (mysqli_error());
	$row_data = mysqli_fetch_assoc($data);

	if($row_data['job_end_date']!="" and $now_date>$row_data['job_end_date']){
		$button_on = false;
	}
    return array($link, $button_on);
}

$database_generic="seminar_generic_data";
$link_generic = mysqli_connect($hostname, $username, $password, $database_generic) or die ("Could not connect MySQL2");
mysqli_select_db($link_generic, $database_generic) or die ("Could not select database_generic");
mysqli_query($link_generic, "SET NAMES 'utf8'");

?>

<link rel=stylesheet href="/seminar/css/base_home.css">
<link rel=stylesheet href="/seminar/css/content.css">
<link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css>
<link rel=stylesheet href=https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css>