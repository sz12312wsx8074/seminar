<?php $str="大明dami";
echo "strlen：".strlen($str)."<br/>";
echo "mb_strlen：".mb_strlen($str,'utf-8')."<br/>";
echo substr("09333333333", 0, 2);
if(substr("0366555333", 0, 2)!="09"){
echo "<script>alert('聯絡電話格式錯誤，是以09開頭');</script>";}
?>