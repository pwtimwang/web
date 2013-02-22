<?php

include_once($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/wp-connect/functions.php");

//$response = w3_http_get($url);
//
//if (!is_wp_error($response)) {
//	return json_decode($response['body']);
//}


//$api_url= 'api.weibo.com/2/statuses/user_timeline.json?screen_name=PingWest%E4%B8%AD%E6%96%87%E7%BD%91&access_token=2.00rmNlFD6QYQNE3f4025103412CFRD';
//$weiboComments = get_url_array($api_url);

//$request = new WP_Http;
//$result = $request -> request($api_url);
//if (is_array($result)) {
//	$result = $result['body'];
//	$result = json_decode($result, true);
//	$result = $result['urls'];
//	$url_short = $result[0]['url_short'];
//	if ($url_short) $long_url = $url_short;
//}
//
class_exists('OAuthV2') or require($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/wp-connect/OAuth/OAuthV2.php");
class_exists('sinaClientV2') or require($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/wp-connect/OAuth/sina_OAuthV2.php");
$to = new sinaClientV2(SINA_APP_KEY, SINA_APP_SECRET, '2.00rmNlFD6QYQNE3f4025103412CFRD');
//$to = new sinaClientV2(SINA_APP_KEY, SINA_APP_SECRET);
//$code = $to->getAuthorizeURL($oauth_token,'http://news.pingwest.com/wp-content/plugins/wp-connect/dl_receiver.php');
//$keys = array();
//$keys['code'] = $code;
//$token = $to->getAccessToken($keys);
//if($token)
//{
//	echo "00000";
//	echo var_dump($token);
//}
$result = $to -> get_comments('3538077135454750');
if($result)
{
	echo "111111111111";
	echo var_dump($result);
}
else
{
	echo "22222222222222";
	echo var_dump($result);
}

//$response = w3_http_get($api_url);
//
//if (!is_wp_error($response)) {
//	return json_decode($response['body']);
//}
