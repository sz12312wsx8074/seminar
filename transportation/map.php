 <!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>test</title>


 <script src="http://code.jquery.com/jquery-latest.js"></script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=geometry&sensor=false"></script>

<script type="text/javascript">
 $(document).ready(function(){
  var latlng = new google.maps.LatLng(24.069622,120.714738);
  //變數是小寫latlng， new後面的LatLng 的L是大寫!!! 
  var myOptions = {
    zoom: 15,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  //建立google.maps.Map - 基本物件，定義網頁上的單一地圖
  var map = new google.maps.Map(document.getElementById("mymap"),myOptions);
 });
</script>
</head>

<body>
<!-- 測試一個區塊-->
<div id="mymap" style="width:500px; height:500px;"></div>
</body>

</html>