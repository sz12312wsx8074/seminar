<?php require_once('../base_home.php');

//將session清空
unset($_SESSION['re_mail']);
echo '登出中......';
echo "<meta http-equiv=REFRESH CONTENT=0.5;url=/seminar/login/login.php?th=$th>";
?>