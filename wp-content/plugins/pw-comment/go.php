<?php
include "../../../wp-config.php";
$to;
//getPost();
//return;

session_start();
//if (empty($_SESSION['wp_url_bind'])) {
//	header('Location:' . get_bloginfo('url'));
//	return;
//} 
define('PW_SINA_APP_KEY','761238391');
define('PW_SINA_APP_SECRET','f5c78be32687cf7427a1e81d9c9df290');

if (is_user_logged_in()) {
	include_once(dirname(__FILE__) . '/config.php');
	$redirect_to = $_SESSION['wp_url_bind'];
	$bind = isset($_GET['bind']) ? strtolower($_GET['bind']) : "";
	if ($bind == "sina") { // OAuth V2
		// $_SESSION['wp_url_login'] = $bind;
		if (SINA_APP_KEY == $sina_app_key_default) { // 默认key
			$aurl = "http://smyx.sinaapp.com/connect.php?client_id=" . PW_SINA_APP_KEY . "&redirect_to=" . urlencode(plugins_url('pw-comment/go.php'));
		} else { // 自定义key
			$_SESSION['source_receiver'] = 'pw-comment/go.php';
			$aurl = "https://api.weibo.com/oauth2/authorize?client_id=" . PW_SINA_APP_KEY . "&redirect_uri=" . urlencode(plugins_url('pw-comment/dl_receiver.php')) . "&response_type=code&with_offical_account=1";
		} 
		header('Location:' . $aurl);
		die();
	} elseif (isset($_GET['code'])) {
		$keys = array();
		class_exists('OAuthV2') or require(dirname(__FILE__) . "/OAuth/OAuthV2.php");
		$o = new OAuthV2(PW_SINA_APP_KEY, PW_SINA_APP_SECRET);
		$keys['code'] = $_GET['code'];
		$keys['access_token_url'] = 'https://api.weibo.com/oauth2/access_token';
		if (!empty($_SESSION['source_receiver'])) {
			$keys['redirect_uri'] = plugins_url('pw-comment/dl_receiver.php');
			$_SESSION['source_receiver'] = "";
		} else {
			$keys['redirect_uri'] = "http://smyx.sinaapp.com/receiver.php";
		} 
		
		$token = $o -> getAccessToken($keys);
		
		echo var_dump($token);
		
		//echo "333333333<br/>";
		if ($token['access_token']) {
			$oauth_token = array('access_token' => $token['access_token'], 'expires_in' => BJTIMESTAMP + $token['expires_in']);
			if ($redirect_to == WP_CONNECT) {
				update_option('wptm_sina', $oauth_token);
			} elseif ($_SESSION['user_id']) {
				update_usermeta($_SESSION['user_id'], 'wptm_sina', $oauth_token);
			} 
		} else {
			return var_dump($token)."1111111111111";
		}
		//echo "222222222222222<br/>";
		//echo var_dump($token)."33333333333333";
		
		
		class_exists('sinaClientV2') or require($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/pw-comment/OAuth/sina_OAuthV2.php");
		$to = new sinaClientV2(PW_SINA_APP_KEY, PW_SINA_APP_SECRET, $token['access_token']);//2.008gYEMD0ZsEWp9bca818109iVwfFB
		echo "<br/>1111111111111111111<br/>";
		echo $token['access_token'];
		echo "<br/>1111111111111111111<br/>";
		
		//$result = $to -> get_comments('3538077135454750');
		if($result)
		{
			//echo "444444444444444";

			echo var_dump($result);
			
		}
		else
		{
			echo "55555555555555555555<br/>";
			echo var_dump($result);
		}
		
		echo '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
		echo '</head><body>';
		
		getPost();
		
		echo '</body></html>';
		
		//header('Location:' . $redirect_to);
		//die();
	} 
	$callback = isset($_GET['callback']) ? $_GET['callback'] : '';
	require_once(dirname(__FILE__) . '/OAuth/OAuth.php');
	if ($bind) {
		include_once(dirname(__FILE__) . '/OAuth/' . $bind . '_OAuth.php');
		switch ($bind) {
			case "sina":
				$to = new sinaOAuth(SINA_APP_KEY, SINA_APP_SECRET);
				break;
			case "qq":
				$to = new qqOAuth(QQ_APP_KEY, QQ_APP_SECRET);
				break;
			case "sohu":
				$to = new sohuOAuth(SOHU_APP_KEY, SOHU_APP_SECRET);
				break;
			case "netease":
				$to = new neteaseOAuth(APP_KEY, APP_SECRET);
				break;
			case "douban":
				$to = new doubanOAuth(DOUBAN_APP_KEY, DOUBAN_APP_SECRET);
				break;
			case "tianya":
				$to = new tianyaOAuth(TIANYA_APP_KEY, TIANYA_APP_SECRET);
				break;
			case "twitter":
				$to = new twitterOAuth(T_APP_KEY, T_APP_SECRET);
				break;
			default:
		} 
		$backurl = plugins_url('pw-comment/go.php?callback=' . $bind);
		$keys = $to -> getRequestToken($backurl);
		$aurl = $to -> getAuthorizeURL($keys['oauth_token'], false, $backurl);
		$_SESSION['keys'] = $keys;
		header('Location:' . $aurl);
	} elseif ($callback) {
		include_once(dirname(__FILE__) . '/OAuth/' . $callback . '_OAuth.php');
		switch ($callback) {
			case "sina":
				$to = new sinaOAuth(SINA_APP_KEY, SINA_APP_SECRET, $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
				break;
			case "qq":
				$to = new qqOAuth(QQ_APP_KEY, QQ_APP_SECRET, $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
				break;
			case "sohu":
				$to = new sohuOAuth(SOHU_APP_KEY, SOHU_APP_SECRET, $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
				break;
			case "netease":
				$to = new neteaseOAuth(APP_KEY, APP_SECRET, $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
				break;
			case "douban":
				$to = new doubanOAuth(DOUBAN_APP_KEY, DOUBAN_APP_SECRET, $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
				break;
			case "tianya":
				$to = new tianyaOAuth(TIANYA_APP_KEY, TIANYA_APP_SECRET, $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
				break;
			case "twitter":
				$to = new twitterOAuth(T_APP_KEY, T_APP_SECRET, $_SESSION['keys']['oauth_token'], $_SESSION['keys']['oauth_token_secret']);
				break;
			default:
		} 
		$redirect_to = $_SESSION['wp_url_bind'];
		$last_key = $to -> getAccessToken($_REQUEST['oauth_verifier']);
		if (!$last_key['oauth_token']) {
			return var_dump($last_key);
		} 
		$update = array ('oauth_token' => $last_key['oauth_token'],
			'oauth_token_secret' => $last_key['oauth_token_secret']
			);
		$tok = 'wptm_' . $callback;
		if ($redirect_to == WP_CONNECT) {
			update_option($tok, $update);
		} elseif ($_SESSION['user_id']) {
			update_usermeta($_SESSION['user_id'], $tok, $update);
		} 
		header('Location:' . $redirect_to);
	} 
} 

function getPost()
{
	global $wpdb;
	global $post;
	global $to;
	//query_posts('posts_per_page=5&meta_key=_jsFeaturedPost&meta_value=yes');	
 //   
 //   // The Loop
	//$counter = 1;
 //   while ( have_posts() ) : the_post(); 
	//
 //   endwhile;
 //   
 //   // Reset Query
 //   wp_reset_query();

	$query = "SELECT TIMESTAMPDIFF(HOUR, post_date, now()), post_date, ID FROM `wp_posts` where TIMESTAMPDIFF(HOUR, post_date, now()) < 72";

	$posts = $wpdb->get_results( $query );
	
	$query = 'SELECT wp_comments.comment_post_ID,MAX(wp_commentmeta.meta_value) AS latest_sina FROM wp_comments join wp_commentmeta on wp_comments.comment_ID = wp_commentmeta.comment_ID where wp_commentmeta.meta_key = "sinaid" group by wp_comments.comment_post_ID';
	$postswithSina = $wpdb->get_results( $query );
	
	echo "<br/>###########################<br/>";
	echo var_dump($posts);
	echo "<br/>###########################<br/>";
	echo var_dump($postswithSina);
	echo "<br/>###########################<br/>";
	
	foreach($posts as $post)
		{
			$sinceid = 0;
			foreach($postswithSina as $postwithsina)
			{
				if($postwithsina->comment_post_ID == $post->ID )
				{
					$sinceid = $postwithsina->latest_sina;
					break;
				}
			}
					
			//echo $post->ID;
			echo get_permalink($post->ID);
			$result = $to->shorten(get_permalink($post->ID));
			if($result)
			{
				//echo var_dump($result);
				echo $result["urls"][0]["url_short"];
				$result1 = $to->short_comments($result["urls"][0]["url_short"], $sinceid);
				echo "<br/>############". $post->ID. "###############<br/>";
				echo "############". $sinceid. "###############<br/>";
				if($result1)
				{
					//echo var_dump($result);
					foreach($result1["share_comments"] as $sharecomment)
					{
						echo "<br/>*******************************************************<br/>";
						echo var_dump($sharecomment);
						$commentdata = array('comment_post_ID' => $post->ID,
							'comment_author' => $sharecomment["user"]["screen_name"],
							'comment_author_email' => "",
							'comment_author_url' => $sharecomment["user"]["url"],
							'comment_content' => $sharecomment["text"],
							'comment_type' => '',
							'comment_parent' => '0',
							'user_id' => '0',
							'comment_author_IP' => '',
							'comment_agent' => 'pw',
							'comment_date' => $sharecomment['created_at'],
							'comment_approved' => '1'
							);
					
						$comment_id = wp_insert_comment( $commentdata );
						echo "<br/>***********************$comment_id********************************<br/>";
						$meta_key = "sinaid";
						$meta_value = $sharecomment['idstr'];
					
						add_comment_meta($comment_id, $meta_key, $meta_value, true);
						echo "<br/>*******************************************************<br/>";
					}
					echo "<br/>33333333333<br/>";
				}
				
			}
				
			echo "<br/>";
		}

	
	//echo "<br/>44444444444<br/>";
	

	
	echo "<br/>555555555555<br/>";
}


?>