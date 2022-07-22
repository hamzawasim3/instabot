<?php
include 'config.php';
	$image = null;
$quote = null;
$img = null;
$img1 = null;
$randname = rand(1000000,9000000);
$imageMediaObjectResponseArray = array();
$limitResponseArray = array();
$publishImageResponseArray = array();
define( 'FACEBOOK_APP_ID', $fb_app_id );
define( 'FACEBOOK_APP_SECRET', $fb_app_secret );
define( 'ENDPOINT_BASE', 'https://graph.facebook.com/v5.0/' );
function makeApiCall( $endpoint, $type, $params ) {
		$ch = curl_init();
		if ( 'POST' == $type ) {
			curl_setopt( $ch, CURLOPT_URL, $endpoint );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
			curl_setopt( $ch, CURLOPT_POST, 1 );
		} elseif ( 'GET' == $type ) {
			curl_setopt( $ch, CURLOPT_URL, $endpoint . '?' . http_build_query( $params ) );
		}
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$response = curl_exec( $ch );
		curl_close( $ch );
		return json_decode( $response, true );
	}
function search_img(){
	$randnum = rand(2000000,3000000);
	$randnum2 = rand(130000,160000);
	global $image;

$image_file = file_get_contents("https://images.wallpaperscraft.com/image/single/".$randnum2."_1280x720.jpg");
if(($image_file == "") || ($image_file == null) || (!isset($image_file)) || (empty($image_file))){
	search_img();
}
else{
$image = "https://images.wallpaperscraft.com/image/single/".$randnum2."_1280x720.jpg";
	$width = 1280;
	$height = 720;
}
 if($height>$width){
 	search_img();
 }
 search_quotes();
curl_close($curl_image);
}
function search_quotes(){
	global $quote;
$url_quotes = "https://api.quotable.io/random";
$curl_quotes = curl_init($url_quotes);
curl_setopt($curl_quotes, CURLOPT_URL, $url_quotes);
curl_setopt($curl_quotes, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_quotes, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl_quotes, CURLOPT_SSL_VERIFYPEER, false);
$resp_quotes = curl_exec($curl_quotes);
$resp_quotes1 = json_decode($resp_quotes);
curl_close($curl_quotes);
$quote = $resp_quotes1->content;
if(strlen($quote)>140){
	search_quotes();
}
addtextimage();
}

function addtextimage(){
    global $randname;
	$path = "../instabot/assets/webfonts/anchor.ttf";
	global $image;
	global $quote;
	global $img;
	global $img1;
$img = imagecreatefromjpeg($image);
for ($x=1; $x<10; $x++){
    imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR,999);
} 
    imagefilter($img, IMG_FILTER_SMOOTH,99);
    imagefilter($img, IMG_FILTER_BRIGHTNESS, 10);
    

$img1 = imagescale($img,1080,720);
$half_black = imagecolorallocatealpha($img, 0, 0, 0, 35);
imagefilledrectangle($img1, 0, 270, 1080, 450, $half_black);

$txt = $quote;
$posX = 20;
$posY = 370;
$angle = 0;
if(strlen($txt)>70){	
	$splitpos = strpos(substr($txt,68,5)," ");
	if($splitpos != -1){
		$splitpos += 68;
	}
	else{ 
		$splitpos = 68;
	}
	if($txt[$splitpos]==" "){
	$txt1=str_split($txt,$splitpos);
	$txt=$txt1[0]."\n".$txt1[1];
	$posX -= 5;
	}
	else{
	$txt1=str_split($txt,$splitpos);
	$txt=$txt1[0]."-\n".$txt1[1];
	}
	$fontSize = 20;
	$posY = 355;
	
}
else if(strlen($txt)<45){
	$fontSize = 33;
	$posY = 370;
}
else{
	$fontSize = 20.4;
	$posY = 370;
}
$fontFile = $path;
$fontColor = imagecolorallocate($img1, 255, 255, 255);
imagettftext($img1, $fontSize, $angle, $posX, $posY, $fontColor, $fontFile, $txt);
imagejpeg($img1,$randname.'.jpg',100);
post_image();
}
	
	function post_image(){
	global $randname;
	$path_img = "https://www.hamzawasim.net/projects/instabot/".$randname.".jpg";
	$instagramAccountId = $instagram_account_id;
	$accessToken = $fb_access_token;
    global $imageMediaObjectResponseArray;
    global $limitResponseArray;
    global $publishImageResponseArray;
	$imageMediaObjectEndpoint = ENDPOINT_BASE . $instagramAccountId . '/media';
	$imageMediaObjectEndpointParams = array( 
		'image_url' => $path_img,
		'caption' => '#quotes #dailyquotes #like #motivation #inspiration',
		'access_token' => $accessToken
	);
	$imageMediaObjectResponseArray = makeApiCall( $imageMediaObjectEndpoint, 'POST', $imageMediaObjectEndpointParams );
	$imageMediaObjectStatusCode = 'IN_PROGRESS';

	while( $imageMediaObjectStatusCode != 'FINISHED' ) { 
		$imageMediaObjectStatusEndpoint = ENDPOINT_BASE . $imageMediaObjectResponseArray['id'];
		$imageMediaObjectStatusEndpointParams = array(
			'fields' => 'status_code',
			'access_token' => $accessToken
		);
		$imageMediaObjectResponseArray = makeApiCall( $imageMediaObjectStatusEndpoint, 'GET', $imageMediaObjectStatusEndpointParams );
		$imageMediaObjectStatusCode = $imageMediaObjectResponseArray['status_code'];
		sleep( 5 );
	} 
	
	$imageMediaObjectId = $imageMediaObjectResponseArray['id'];
	$publishImageEndpoint = ENDPOINT_BASE . $instagramAccountId . '/media_publish';
	$publishEndpointParams = array(
		'creation_id' => $imageMediaObjectId,
		'access_token' => $accessToken
	);
	$publishImageResponseArray = makeApiCall( $publishImageEndpoint, 'POST', $publishEndpointParams );
	$limitEndpoint = ENDPOINT_BASE . $instagramAccountId . '/content_publishing_limit';
	$limitEndpointParams = array(
		'fields' => 'config,quota_usage',
		'access_token' => $accessToken
	);
	$limitResponseArray = makeApiCall( $limitEndpoint, 'GET', $limitEndpointParams );
	unlink("../instabot/".$randname.".jpg");
}
search_img();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			Quote Bot API Status
		</title>
		<style>
			body {
				font-family: 'Helvetica';
			}

			.raw-response {
				width: 100%;
				height: 100px;
			}
		</style>
	</head>
	<body>
		<h1>Quote Bot Status</h1>
		<hr />
		<h3>Media Object Raw Response</h3>
		<textarea class="raw-response">
			<?php print_r( $imageMediaObjectResponseArray ); ?>
		</textarea>
		<hr />
		<h3>Publish Media Raw Response</h3>
		<textarea class="raw-response">
			<?php print_r( $publishImageResponseArray ) ; ?>
		</textarea>
		<hr />
		<h3>User API Limit Raw Response</h3>
		<textarea class="raw-response">
			<?php print_r( $limitResponseArray ) ; ?>
		</textarea>
	</body>
</html>
