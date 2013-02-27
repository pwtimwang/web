<?php
include "../../../wp-config.php";
$to;
$debug = false;

//session_start();

define('PW_SINA_APP_KEY','761238391');
define('PW_SINA_APP_SECRET','f5c78be32687cf7427a1e81d9c9df290');

include_once(dirname(__FILE__) . '/config.php');

class_exists('OAuthV2') or require(dirname(__FILE__) . "/OAuth/OAuthV2.php");
class_exists('sinaClientV2') or require($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/pw-comment/OAuth/sina_OAuthV2.php");
$to = new sinaClientV2(PW_SINA_APP_KEY, PW_SINA_APP_SECRET, '2.00rmNlFD0ZsEWp38edcaaca6LXm2sB');//for pingwest

if(isset($_GET['p']) || isset($_GET['d']))
{
	$debug = true;
}

if(1)
{
	echo '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
	echo '</head><body>';
}

//$result = $to -> get_comments('3538077135454750');
//if($result)
//{
//	echo "<br/>get_comments OK<br/>";
//	echo var_dump($result);
//	
//}
//else
//{
//	echo "<br/>get_comments failure<br/>";
//	echo var_dump($result);
//}

getPost();

if(1)
{
	echo '</body></html>';
}

$callback = isset($_GET['callback']) ? $_GET['callback'] : '';
require_once(dirname(__FILE__) . '/OAuth/OAuth.php');

function getPost()
{
	global $wpdb;
	global $post;
	global $to;
	global $debug;

	if(isset($_GET['p']))
	{
		 $post->ID = $_GET['p'];
		$debug = true;
	}
	else
	{
		//$query = "SELECT TIMESTAMPDIFF(HOUR, post_date, now()), post_date, ID FROM `wp_posts` where TIMESTAMPDIFF(HOUR, post_date, now()) < 72 and ID = 6692";
		$query ="SELECT wp_posts.post_date, wp_postmeta.meta_value as lastsyc, wp_posts.ID FROM `wp_posts` join wp_postmeta on wp_posts.ID = wp_postmeta.post_id 
where TIMESTAMPDIFF(HOUR, wp_posts.post_date, now()) < 48
and wp_postmeta.meta_key = 'lastsyc'
AND wp_posts.post_status =  'publish'
AND wp_posts.post_type =  'post'
order by wp_postmeta.meta_value ASC
limit 0, 1";

//		$query ="SELECT wp_posts.post_date, wp_postmeta.meta_value as lastsyc, wp_posts.ID FROM `wp_posts` join wp_postmeta on wp_posts.ID = wp_postmeta.post_id 
//where wp_postmeta.meta_key = 'lastsyc'
//AND wp_posts.post_status =  'publish'
//AND wp_posts.post_type =  'post'
//order by wp_postmeta.meta_value ASC
//limit 0, 1";


		$posts = $wpdb->get_results( $query );
		
		//echo "<br/><br/>################Posts in 72 hours ################<br/>";
		//echo var_dump($posts);
		
		$post = $posts[0];
		

		
	}
		
	//foreach($posts as $post)
	{
		//get the latest sina sinceid from db.
		//$query = 'SELECT wp_comments.comment_post_ID,MAX(wp_commentmeta.meta_value) AS latest_sina FROM wp_comments join wp_commentmeta on wp_comments.comment_ID = wp_commentmeta.comment_ID where wp_commentmeta.meta_key = "sinaid" group by wp_comments.comment_post_ID';
		//$query = 'SELECT wp_comments.comment_post_ID,MAX(wp_commentmeta.meta_value) AS latest_sina FROM wp_comments join wp_commentmeta on wp_comments.comment_ID = wp_commentmeta.comment_ID where wp_commentmeta.meta_key = "sinaid" and wp_comments.comment_agent != "pw_website" group by wp_comments.comment_post_ID';
		$query = 'SELECT MAX(wp_commentmeta.meta_value) AS latest_sina FROM wp_comments join wp_commentmeta on wp_comments.comment_ID = wp_commentmeta.comment_ID where wp_commentmeta.meta_key = "sinaid" and wp_comments.comment_agent = "pw_sina" and wp_comments.comment_post_ID ="' . $post->ID . '"';
		$sinceid = $wpdb->get_var( $query );
		
		//echo "<br/><br/>################Posts in 72 hours ################<br/>";
		//echo var_dump($posts);
		//echo "<br/><br/>################Posts with latest Sina id ################<br/>";
		//echo var_dump($postswithSina);
		//echo "<br/><br/>";
		

		//$sinceid = 0;
		//foreach($postswithSina as $postwithsina)
		//{
		//	if($postwithsina->comment_post_ID == $post->ID )
		//	{
		//		$sinceid = $postwithsina->latest_sina;
		//		break;
		//	}
		//}
		
		//if($debug)
		//{
			echo "<br/>################post $post->ID : latest sina id: $sinceid ###############<br/>";
		//}
		
		$url_short = '';
		$url_short = get_post_meta($post->ID, "url_short", true);
		if (empty($url_short)) 
		{
			if($debug)
			{
				echo "<br/>url_short is null, request <br/>";
			}
			$result = $to->shorten(get_permalink($post->ID));
			if($result)
			{
				$url_short = $result["urls"][0]["url_short"];
				update_post_meta($post->ID,  "url_short", $url_short);
			}
		}
		if($url_short)
		{
			if($debug)
			{
				echo $url_short;
			}
			$result1 = $to->short_comments($url_short, $sinceid);
			
			if($result1)
			{
				// get all the local comments sine id to compare the comments.
				//$query = 'SELECT wp_commentmeta.meta_value AS latest_sina FROM wp_comments join wp_commentmeta on wp_comments.comment_ID = wp_commentmeta.comment_ID where wp_commentmeta.meta_key = "sinaid" and wp_comments.comment_agent != "pw_sina" and wp_comments.comment_post_ID = "' . $post->ID . '" ';
				$query = 'SELECT wp_commentmeta.meta_value AS latest_sina FROM wp_comments join wp_commentmeta on wp_comments.comment_ID = wp_commentmeta.comment_ID where wp_commentmeta.meta_key = "sinaid" and wp_comments.comment_post_ID = "' . $post->ID . '" ';
				$localcommentsinaid = $wpdb->get_col( $query );
				if($debug)
				{
					echo "<br/>################ local comment sinaid start:   ###############<br/>";
					echo var_dump($localcommentsinaid);
					echo "<br/>################ local comment sinaid end:   ###############<br/>";			
					
					echo var_dump($result1);
				}
				
				if($result1["share_comments"])
				{
					foreach($result1["share_comments"] as $sharecomment)
					{
						if(!in_array($sharecomment['idstr'],$localcommentsinaid))
						{
							if($debug)
							{
								echo "<br/>*****************weibo comment begin  **************************************************<br/>";
								echo var_dump($sharecomment);
							}
							date_default_timezone_set("Asia/Shanghai");
							$commentdata = array('comment_post_ID' => $post->ID,
								'comment_author' => $sharecomment["user"]["screen_name"],
								'comment_author_email' => ($sharecomment["user"]["id"])."@weibo.com",
								'comment_author_url' => "http://weibo.com/".($sharecomment["user"]["id"]),
								'comment_content' => $sharecomment["text"],
								'comment_type' => '',
								'comment_parent' => '0',
								'user_id' => '0',
								'comment_author_IP' => '',
								'comment_agent' => 'pw_sina',
								'comment_date' =>  date("Y-m-d H:i:s", strtotime($sharecomment["created_at"])),
								'comment_approved' => '1'
								);
							
							
							if($debug)
							{
								echo "<br/>".$sharecomment["created_at"]."<br/>";
								echo date_default_timezone_get(void)."<br/>";
								echo strtotime($sharecomment["created_at"])."<br/>";
								echo gmdate("Y-m-d H:i:s", strtotime($sharecomment["created_at"]))."<br/>";
								echo date("Y-m-d H:i:s", strtotime($sharecomment["created_at"]))."<br/>";
								
								
								echo "<br/>#########################################################<br/>";
								echo var_dump($commentdata);
							}
							
							$comment_id = wp_insert_comment( $commentdata );
							//$comment_id = wp_new_comment_pw( $commentdata );
							//the sina api will return the same comment with the different id.
							// wordpress will check it and make the wordpress die.
							// in this case, it needs to call insert_comment directly.
							
							if($debug)
							{
								echo "<br/>*****************$comment_id********************************<br/>";
							}
							$meta_key = "sinaid";
							$meta_value = $sharecomment['idstr'];
							
							add_comment_meta($comment_id, $meta_key, $meta_value, true);
							
							if($debug)
							{
								echo "<br/>*****************weibo comment end  **************************************************<br/>";
							}
						}
					}
				}
				else
				{
					if($debug)
					{
						echo "<br/>no comments from sina<br/>";
					}
				}
				
				
			}
			else
			{
				if($debug)
				{
					echo "<br/>short_comments failure<br/>";
					echo var_dump($result1);
				}
			}
			
			$meta_key = "lastsyc";
			$meta_value = current_time('mysql');
			update_post_meta($post->ID, $meta_key, $meta_value);
			
			echo "<br/>set  lastsyc<br/>";
			
			
		}
		if($debug)
		{
			echo "<br/>################ end of ". $post->ID. "###############<br/><br/>";		
			echo "<br/>";
		}
	}	
	if($debug)
	{
		echo "<br/>end<br/>";
	}
}

// modified from wp_new_comment.
// see below commented code for more info.
function wp_new_comment_pw( $commentdata ) {
	$commentdata = apply_filters('preprocess_comment', $commentdata);

	$commentdata['comment_post_ID'] = (int) $commentdata['comment_post_ID'];
	if ( isset($commentdata['user_ID']) )
		$commentdata['user_id'] = $commentdata['user_ID'] = (int) $commentdata['user_ID'];
	elseif ( isset($commentdata['user_id']) )
		$commentdata['user_id'] = (int) $commentdata['user_id'];

	$commentdata['comment_parent'] = isset($commentdata['comment_parent']) ? absint($commentdata['comment_parent']) : 0;
	$parent_status = ( 0 < $commentdata['comment_parent'] ) ? wp_get_comment_status($commentdata['comment_parent']) : '';
	$commentdata['comment_parent'] = ( 'approved' == $parent_status || 'unapproved' == $parent_status ) ? $commentdata['comment_parent'] : 0;

	//$commentdata['comment_author_IP'] = preg_replace( '/[^0-9a-fA-F:., ]/', '',$_SERVER['REMOTE_ADDR'] );
	//$commentdata['comment_agent']     = substr($_SERVER['HTTP_USER_AGENT'], 0, 254);

	//$commentdata['comment_date']     = current_time('mysql');
	//$commentdata['comment_date_gmt'] = current_time('mysql', 1);

	$commentdata = wp_filter_comment($commentdata);

	$commentdata['comment_approved'] = wp_allow_comment($commentdata);

	$comment_ID = wp_insert_comment($commentdata);

	do_action('comment_post', $comment_ID, $commentdata['comment_approved']);

	/*if ( 'spam' !== $commentdata['comment_approved'] ) { // If it's spam save it silently for later crunching
		if ( '0' == $commentdata['comment_approved'] )
			wp_notify_moderator($comment_ID);

		$post = &get_post($commentdata['comment_post_ID']); // Don't notify if it's your own comment

		if ( get_option('comments_notify') && $commentdata['comment_approved'] && ( ! isset( $commentdata['user_id'] ) || $post->post_author != $commentdata['user_id'] ) )
			wp_notify_postauthor($comment_ID, isset( $commentdata['comment_type'] ) ? $commentdata['comment_type'] : '' );
	}*/

	return $comment_ID;
}

?>