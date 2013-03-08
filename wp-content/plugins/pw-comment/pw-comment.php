<?php
/*
Plugin Name: pw-comment
Author: Tim Wang
Author URI: http://www.xxx.com/
Plugin URI: http://wordpress.org/extend/plugins/xxx/
Description: PingWest comments。
Version: 0.0.1
*/

define('WP_CONNECT_VERSION', '2.4.9');
$wpurl = get_bloginfo('wpurl');
$siteurl = get_bloginfo('url');
$plugin_url = plugins_url('pw-comment');
$PWPlugin_url = $plugin_url;
$wptm_basic = get_option('wptm_basic'); // denglu
$wptm_options = get_option('wptm_options');
$wptm_connect = get_option('wptm_connect');
$wptm_comment = get_option('wptm_comment'); // denglu
$wptm_advanced = get_option('wptm_advanced');
$wptm_share = get_option('wptm_share');
$wptm_version = get_option('wptm_version');
$wptm_key = get_option('wptm_key');
$wp_connect_advanced_version = "1.7.3";

//update_option('wptm_basic', '');


include_once(dirname(__FILE__) . '/functions.php');
include_once(dirname(__FILE__) . '/pw.func.php');



add_filter('language_attributes','wpjam_wb_open_graph_language_attributes');
function wpjam_wb_open_graph_language_attributes($text){
	if(is_single()){
		return $text . ' xmlns:wb="http://open.weibo.com/wb"';
	}
}



add_action('wp_head','wpjam_wb_like_head');

function wpjam_wb_like_head(){
	global $post;
	if(is_single()|| is_page()){
?>
<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=761238391" type="text/javascript" charset="utf-8"></script>
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo get_permalink($post->ID); ?>" />
<meta property="og:title" content="<?php echo $post->post_title; ?>" />

<meta property="og:description" content="<?php echo get_post_excerpt($post);?>" />

<?php if($post_first_image = get_post_first_image($post->post_content)){?>
<meta property="og:image" content="<?php echo $post_first_image; ?>" />
<?php } ?>
<meta name="weibo: article:create_at" content="<?php echo $post->post_date; ?>" />
<meta name="weibo: article:update_at" content="<?php echo $post->post_modified; ?>" />
       
<?php   
	} elseif(is_home())
	{
?>
<!--必填-->
<meta property="og:type" content="webpage" />
<meta property="og:url" content="www.pingwest.com" />
<meta property="og:title" content="全球视野的前沿中文科技媒体" />
<meta property="og:description" content="PingWest是一家提供关于硅谷与中国最前沿科技创业资讯、趋势与洞见的在线媒体，致力于成为沟通中国与美国这两个全球最大的互联网/移动市场的互联网社区。" />
<!--选填-->
<meta property="og:image" content="http://www.pingwest.com/wp-content/uploads/2013/01/pw120x120.jpg" />
<?php
		}    
}



add_filter('the_content','wpjam_wb_like_content');
function wpjam_wb_like_content($text){
	if(is_single()){
		return $text . '<br/>
		<wb:like></wb:like>
		';
	}
	return $text;
}

//add_filter('the_content','wpjam_wb_share_content');
function wpjam_wb_share_content($text){
	if(is_single()){
		return $text . '<br/>
		<wb:share-button size="middle" relateuid="2833534593" ></wb:share-button>
		';
	}
	return $text;
}


function wb_like(){
?>
<wb:like></wb:like>
<?php 
}

function wb_praise(){
	wb_like();
}


if(!function_exists('get_post_first_image'))
{
function get_post_excerpt()
{
	global $post;
			$text = $post->post_content;

		$text = strip_shortcodes( $text );

		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
		$excerpt_length = apply_filters('excerpt_length', 55);
		//$text = wp_trim_words( $text, 88, $excerpt_more );
		$text = mb_substr(strip_tags($text), 0, 140, 'UTF-8');

		return $text;
}
}


if(!function_exists('get_post_first_image'))
	{

    function get_post_first_image($post_content)
		{

        preg_match_all('|<img.*?src=[\'"](.*?)[\'"].*?>|i', $post_content, $matches);

        if($matches){      

            return $matches[1][0];

        }else{

            return false;

        }

    }

}


add_action( 'wp_ajax_prologue_new_comment', 'prologue_new_comment_pw' ); //Ajax Commenting
add_action( 'wp_ajax_nopriv_prologue_new_comment', 'prologue_new_comment_pw' ); // Load new posts 

function prologue_new_comment_pw() {

	if( 'POST' != $_SERVER['REQUEST_METHOD'] || empty( $_POST['action'] ) || $_POST['action'] != 'prologue_new_comment' ) {

	    //die();
		echo __("aaaaaaaaaaaaaaaaaaaaaa.", 'p2');

	}

	

	check_ajax_referer( 'ajaxnonce', '_ajax_post' );

	

	$comment_content = isset( $_POST['comment'] )? trim( $_POST['comment'] ) : null;

	$comment_post_ID = isset( $_POST['comment_post_ID'] )? trim( $_POST['comment_post_ID'] ) : null;



	if ( '' == $comment_content ) {

	    die('<p>'.__('Error: Please type a comment.', 'p2').'</p>');

	}

	

	$comment_parent = isset( $_POST['comment_parent'] ) ? absint( $_POST['comment_parent'] ) : 0;



	$commentdata = compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID' );



		$commentdata = array('comment_post_ID' => $_POST['comment_post_ID'],
			'comment_author' => $_POST['comment_author'],
			'comment_author_email' => $_POST["comment_author_email"],
			'comment_author_url' => $_POST['comment_author_url'],
			'comment_content' => $_POST['comment'],
			'comment_type' => '',
			'comment_parent' => $_POST["comment_parent"],
			'user_id' => ($user_id) ? $user_id : 0,
			'comment_author_IP' => getenv("REMOTE_ADDR"),
			'comment_agent' => 'pw_website',
			'comment_date' => $_POST['date'],
			'comment_approved' => '1'
			);
		
	$comment_id = wp_new_comment( $commentdata );
	
	$meta_key = "sinaid";
	$meta_value = $_POST['sinaid'];
	
	add_comment_meta($comment_id, $meta_key, $meta_value, true);

	$comment = get_comment( $comment_id );


	if ($comment)

		echo $comment_id;

	else 

		echo __("Error: Unknown error occured. Comment not posted.", 'p2');

    exit;

}


add_action('publish_post', 'pw_connect_publish');
function pw_connect_publish($post_ID) {
	$meta_key = "lastsyc";
	$meta_value = "0";
	update_post_meta($post_ID, $meta_key, $meta_value);
	
	$meta_key = "url_short";
	$meta_value = "";
	update_post_meta($post_ID, $meta_key, $meta_value);
}
