<?php require_once('../base_home.php');   //改版改這裡~~~~~~~~
if(!isset($_SESSION['admin_id'])){
	echo "<script>alert('請先登入!');</script>";
	exit ('<script>location.href="/seminar/login.php"</script>');
	return;
}

$query_data = "SELECT * FROM banquet where bt_no=1";
$data = mysqli_query($link, $query_data) or die (mysqli_error());
$row_data = mysqli_fetch_assoc($data);
$totalRows_data = mysqli_num_rows($data);

?>

<html>

<div id=content class="col-sm-9">    <!--改版改這裡~~~~~~~ -->
<h1>晚宴</h1>
<hr>
<form method="POST" class="form-horizontal form-text-size" action="banquet.php?th=<?php echo $th; ?>" enctype="multipart/form-data">
<?php if($totalRows_data==0){ 

	echo "目前還沒有資料"; ?>
	<br><br>	
	<?php if($button_on){ ?>
		<input type="button" class="btn btn-primary" name="insert" value="新增晚宴內容" onClick="self.location='banquet_insert.php?th=<?php echo $th; ?>'">
	<?php } ?>

<?php }else{ ?>
	
	飯店名稱：<?php echo $row_data['bt_hotel']; ?>
	<br>
	飯店地址：<?php echo $row_data['bt_address']; ?>
	<br>
	聯絡人：<?php echo $row_data['bt_contact_person']; ?>
	<br>
	連絡電話：<?php echo $row_data['bt_phone_number']; ?>
	<br><br>
	飯店地圖：
	<style>
		/*設定gmap_canvas顯示區(寬與高)*/
		#gmap_canvas{
			width:500px;
			height:500px;
		}
	</style>
	
	<!--地圖顯示區-->
	<div id="gmap_canvas"></div>
	<br>
	
	<?php if($button_on){ ?>
		<input type="button" class="btn btn-primary" name="update" value="修改" onClick="self.location='banquet_update.php?th=<?php echo $th; ?>'">
	<?php } ?>
	
	<?php
	//-----Google map value Start-----
	$set_address=$row_data['bt_address']; //填寫所要的地址，Example地址為勤美綠園道
	$data_array = geocode($set_address);
	$latitude = $data_array[0];
	$longitude = $data_array[1];
	$data_address = $data_array[2];
	//-----Google map value End-----
 } ?>

<?php
//-----function start-----
function geocode($address){
	/*用來將字串編碼，在資料傳遞的時候，如果直接傳遞中文會出問題，所以在傳遞資料時，通常會使用urlencode先編碼之後再傳遞*/
	$address = urlencode($address);

	/*可參閱：(https://developers.google.com/maps/documentation/geocoding/intro)*/
	$url = "http://maps.google.com/maps/api/geocode/json?address={$address}&language=zh-TW";

	/*取得回傳的json值*/
	$response_json = file_get_contents($url);

	/*處理json轉為變數資料以便程式處理*/
	$response = json_decode($response_json, true);

	/*如果能夠進行地理編碼，則status會回傳OK*/ 
	if($response['status']='OK'){
		if((!isset($response['results'][0]['geometry']['location']['lat'])) and (!isset($response['results'][0]['geometry']['location']['lng'])) and (!isset($response['results'][0]['formatted_address'])) ){
			echo "google map 無法找到此地址";
		}else{
			//取得需要的重要資訊
			$latitude_data = $response['results'][0]['geometry']['location']['lat']; //緯度
			$longitude_data = $response['results'][0]['geometry']['location']['lng']; //精度
			$data_address = $response['results'][0]['formatted_address'];

			if($latitude_data && $longitude_data && $data_address){

				$data_array = array();            
				
				//一個或多個單元加入陣列末尾
				array_push(
					$data_array,
					$latitude_data, //$data_array[0]
					$longitude_data, //$data_array[1]
					'<b>地址: </b> '.$data_address //$data_array[2]
				);
				return $data_array; //回傳$data_array
			}else{
				return false;
			}
		}
	}else{
		return false;
	}
}
//-----function end-----
?>
</form><!--改版改這裡~這裡以下都要改成這樣-->
<br>
</div>

</div> <!--wrapper-->
</body>
<?php require_once('../base_footer.php')?>

<!-----google map Start----->
<!--可參閱：(https://developers.google.com/maps/documentation/javascript/3.exp/reference)-->
<script src="http://maps.google.com/maps/api/js?language=zh-TW"></script>
<script>
    function init_map() {
        /*地圖參數相關設定 Start*/
        var Options = {
            zoom: 15, /*縮放比例*/
            center: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>) /*所查詢位置的經緯度位置*/
        };
        
        map = new google.maps.Map(document.getElementById("gmap_canvas"), Options);
        /*地圖參數相關設定 End*/
        
        /*自行設定圖標 Start*/
        var image = {
            url: 'https://google-developers.appspot.com/maps/documentation/javascript/examples/full/images/beachflag.png', /*自定圖標檔案位置或網址*/
            // This marker is 20 pixels wide by 32 pixels high.
            size: new google.maps.Size(20, 32), /*自定圖標大小*/
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 32).
            anchor: new google.maps.Point(0, 32)
          };
        
        marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(<?php echo $latitude; ?>, <?php echo $longitude; ?>), /*圖標經緯度位置*/
            icon: image
        });
        /*自行設定圖標 End*/
        
        /*所查詢位置詳細資料 Start*/
        infowindow = new google.maps.InfoWindow({
            content: "<?php echo $data_address; ?>"
        });
        
        infowindow.open(map, marker);
        /*所查詢位置詳細資料 End*/
    }
    
    /*
    事件偵聽器
    (可參閱：https://developers.google.com/maps/documentation/javascript/events)
    */
    google.maps.event.addDomListener(window, 'load', init_map);
</script>
<!-----google map End----->