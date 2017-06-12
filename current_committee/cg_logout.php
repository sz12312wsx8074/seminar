<?php require_once('../seminar_connect.php');
session_destroy();
echo "<script>alert('已登出!!');</script>";
exit ('<meta http-equiv=REFRESH CONTENT=0.1;url=/seminar/current_committee/cg_login.php>');
require_once('base_footer.php')
?>