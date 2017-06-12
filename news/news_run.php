 <?php require_once('../base_home.php'); 

if(isset($_POST['insert'])){
	if(empty($_POST['news_contant'])){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=news_insert.php?th=$th>");
	}
	else{
		foreach($_FILES["file"]["name"] as $key){
			$have = $key;
		}
		
		$s = count($_FILES["file"]["name"]);
		
		$sqlIns = sprintf("INSERT INTO news (news_title, news_date,news_contant) values (%s, %s, %s)",
		'"'.$_POST['news_title'].'"', '"'.$_POST['news_date'].'"', '"'.$_POST['news_contant'].'"');
		$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");

		$sql_new = "SELECT * FROM news ORDER BY news_no DESC LIMIT 1";
		$result_new = mysqli_query($link,$sql_new);
		$row_data_new = mysqli_fetch_assoc($result_new);
		
		$news_no = $row_data_new['news_no'];
		$i=count($_FILES["file"]["name"]);	

		if($have != null){
			for ($j=0 ; $j<$i ; $j++){ 
				$filename=$_FILES["file"]["name"][$j]; 
				$sqlFs = sprintf("INSERT INTO news_file (file_name, news_no) value ('$filename','$news_no')");
				$sqlF = mysqli_query($link, $sqlFs) or die ('error file MYSQL');
				if($sqlF){
					move_uploaded_file($_FILES['file']['tmp_name'][$j], "news_file/".iconv('UTF-8','BIG5',$filename));
				}
			} 
		}
		if($sqlI){
			echo "<script>alert('已新增!!');</script>";
			exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=news.php?th=$th>");
		}
	}
}


if(isset($_POST['update'])){
	
	$news_no=$_POST['news_no'];	
	
	if(empty($_POST['news_contant'])){
		echo "<script>alert('資料不齊全，請重新輸入');</script>";
		exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='news_update.php?news_no=$news_no&th=$th'>");
	}
	else{
		foreach($_FILES["file"]["name"] as $key){
			$have = $key;
		}
	
		$i=count($_FILES["file"]["name"]);
		
		if($have != null){
			$sqlIns = sprintf("UPDATE news set news_title='%s' , news_date='%s' ,news_contant='%s' WHERE news_no = '$news_no'"
			,$_POST['news_title'],$_POST['news_date'],$_POST['news_contant']);
			$sqlI = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
			
			for ($j=0 ; $j<$i ; $j++){ 
			$filename=$_FILES["file"]["name"][$j]; 
			$sqlFs = sprintf("INSERT INTO news_file (file_name, news_no) value ('$filename','$news_no')");
			$sqlF = mysqli_query($link, $sqlFs) or die ('error file MYSQL');
				if($sqlF){
					move_uploaded_file($_FILES['file']['tmp_name'][$j], "news_file/".iconv('UTF-8','BIG5',$filename));
				}
			}
			if($sqlI){
				echo "<script>alert('已修改!!');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=news.php?th=$th>");
			}
		}else{
			$sqlIns = sprintf("UPDATE news set news_title='%s' , news_date='%s' ,news_contant='%s' WHERE news_no = '$news_no'"
			,$_POST['news_title'],$_POST['news_date'],$_POST['news_contant']);
			$sqlU = mysqli_query($link, $sqlIns) or die ("MYSQL Error");
			
			if($sqlU){
				echo "<script>alert('已修改!!');</script>";
				exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=news.php?th=$th>");
			}					
		}
	}
}

?>

