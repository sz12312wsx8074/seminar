<?php require_once('../base_home.php');

$name = $_POST['id'];
$mail = base64_decode($name);
$source = $_POST['source'];

if($_POST['pwd'] != $_POST['secpwd']){
    echo "<script>alert('密碼與確認密碼不相符!');</script>";
    if ($source == 'user_update') {
      exit ("<meta http-equiv=REFRESH CONTENT=0.1;url=user_update.php?th=$th>");
    }else{
      exit ("<meta http-equiv=REFRESH CONTENT=0.1;url='reset_pwd.php?th=$th&id=$name'>");
    }
}else{
    $sqlUpd = sprintf("UPDATE register SET re_pwd='%s' WHERE re_mail = '$mail'", md5($_POST['pwd']));
    $sqlU = mysqli_query($link, $sqlUpd) or die ("MYSQL Error");
    if($sqlU){
        echo "<script>alert('密碼已重設，請重新登入!');</script>";
        exit ("<meta http-equiv=REFRESH CONTENT=0.5;url=/seminar/login/logout.php?th=$th>");
    }
}

require_once('../base_footer.php')?>