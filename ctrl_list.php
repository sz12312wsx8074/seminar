<?php require_once('seminar_connect.php');
$query_data = "SELECT * FROM admin";
$data = mysqli_query($link_generic,$query_data) or die(mysqli_error());
$row_data = mysqli_fetch_assoc($data);


$aside = array('home', 'news', 'organizers', 'important_date', 'login', 'paper_format', 'accpet_list', 'best_paper','committee_register',
 'committee_list','current_committee' , 'program','oral_session', 'poster_session','special_session','instructions_for_poster','banquet','transportation','shuttle_bus');
$result = count($aside);
$check = $_GET['check'];

for( $i=0; $i<=$result-1; $i++){
	if($check == $aside[$i]){
		if($row_data[$aside[$i]] == 1){
			$sqlUpd = sprintf("UPDATE admin SET $check = 0");
			$sqlU = mysqli_query($link_generic, $sqlUpd) or die ("MYSQL Error");
		}else{
			$sqlUpd = sprintf("UPDATE admin SET $check = 1");
			$sqlU = mysqli_query($link_generic, $sqlUpd) or die ("MYSQL Error");
		}
		if($sqlU){
			exit ("<meta http-equiv=REFRESH CONTENT=0;url=/seminar/home/home.php?th=$th>");
		}
	}
}
	
	
?>