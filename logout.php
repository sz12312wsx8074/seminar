<?php require_once('base_home.php');
session_destroy();
echo "<script>alert('已登出!!');</script>";
exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/login.php>');
require_once('base_footer.php')
?>