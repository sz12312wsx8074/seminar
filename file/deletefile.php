<?php require_once('../base_home.php');

$file_name = $_GET["file"];
$ext = strrchr($file_name,'.');
$lin = strlen($ext);
$seat = substr($file_name, 4, -$lin);

if($seat=="oral_session"){
	if($ext==".pdf"){
		$sqlUpd = sprintf("UPDATE oral_session SET os_pdf='%s' WHERE os_no='%s'", null, 1);
		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		
		if($sqlU){
			$file_move="../file/".$th."/". $file_name;
			unlink($file_move);
			exit("<script>location.href='../oral_session/oral_session_update.php?th=$th';</script>");
			
		}
	}else{
		$sqlUpd = sprintf("UPDATE oral_session SET os_word='%s' WHERE os_no='%s'", null, 1);
		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		
		if($sqlU){
			$file_move="../file/".$th."/". $file_name;
			unlink($file_move);
			exit("<script>location.href='../oral_session/oral_session_update.php?th=$th';</script>");
		}
	}
}elseif($seat=="poster_session"){
	if($ext==".pdf"){
		$sqlUpd = sprintf("UPDATE poster_session SET ps_pdf='%s' WHERE ps_no='%s'", null, 1);
		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		
		if($sqlU){
			$file_move="../file/".$th."/". $file_name;
			unlink($file_move);
			exit("<script>location.href='../poster_session/poster_session_update.php?th=$th';</script>");
		}
	}else{
		$sqlUpd = sprintf("UPDATE poster_session SET ps_word='%s' WHERE ps_no='%s'", null, 1);
		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		
		if($sqlU){
			$file_move="../file/".$th."/". $file_name;
			unlink($file_move);
			exit("<script>location.href='../poster_session/poster_session_update.php?th=$th';</script>");
		}
	}
}elseif($seat=="program"){
	if($ext==".pdf"){
		$sqlUpd = sprintf("UPDATE program SET prm_pdf='%s' WHERE prm_no='%s'", null, 1);
		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		
		if($sqlU){
			$file_move="../file/".$th."/". $file_name;
			unlink($file_move);
			exit("<script>location.href='../program/program_update.php?th=$th';</script>");
		}
	}else{
		$sqlUpd = sprintf("UPDATE program SET prm_word='%s' WHERE prm_no='%s'", null, 1);
		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		
		if($sqlU){
			$file_move="../file/".$th."/". $file_name;
			unlink($file_move);
			exit("<script>location.href='../program/program_update.php?th=$th';</script>");
		}
	}
}elseif($seat=="special_session"){
	if($ext==".pdf"){
		$sqlUpd = sprintf("UPDATE special_session SET ss_pdf='%s' WHERE ss_no='%s'", null, 1);
		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		
		if($sqlU){
			$file_move="../file/".$th."/". $file_name;
			unlink($file_move);
			exit("<script>location.href='../special_session/special_session_update.php?th=$th';</script>");
		}
	}else{
		$sqlUpd = sprintf("UPDATE special_session SET ss_word='%s' WHERE ss_no='%s'", null, 1);
		$sqlU = mysqli_query($link, $sqlUpd) or die(mysqli_error());
		
		if($sqlU){
			$file_move="../file/".$th."/". $file_name;
			unlink($file_move);
			exit("<script>location.href='../special_session/special_session_update.php?th=$th';</script>");
		}
	}
}


?>