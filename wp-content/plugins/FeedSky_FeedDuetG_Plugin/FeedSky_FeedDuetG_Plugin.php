<?php
/*
Plugin Name: FeedSky FeedDuetG
Plugin URI: http://www.duetg.com/project/wordpress/feedsky-feedduetg/
Description: 本插件基于 <a href="http://www.feedburner.com/fb/a/help/wordpress_quickstart">FeedBurner FeedSmith</a> 修改而来，它将您的 WordPress Feed 地址的全部访问流量转向到您的 FeedSky Feed 地址上，以便于统计订阅信息。 
Author: Duet G.
Author URI: http://www.duetg.com/
Version: 2.2
*/

$data = array(
				'feedsky_url' 			=> '',
				'feedsky_comments_url' 	=> ''
			);
$flash = '';

function is_authorized() {
	global $user_level;
	if (function_exists("current_user_can")) {
		return current_user_can('activate_plugins');
	} else {
		return $user_level > 5;
	}
}
								
add_option('feedsky_settings',$data,'FeedSky Feed Redirect Option');

$feedsky_settings = get_option('feedsky_settings');

function add_feedsky_options_page() {
	if (function_exists('add_options_page')) {
		add_options_page('FeedSky', 'FeedSky', 8, basename(__FILE__), 'feedsky_options_subpanel');
	}
}

function feedsky_options_subpanel() {
	global $flash, $feedsky_settings, $_POST, $wp_rewrite;
	if (is_authorized()) {
		if (isset($_POST['feedsky_url'])) { 
			$feedsky_settings['feedsky_url'] = $_POST['feedsky_url'];
			update_option('feedsky_settings',$feedsky_settings);
			$flash = "您的设置已保存。";
		}
		if (isset($_POST['feedsky_comments_url'])) { 
			$feedsky_settings['feedsky_comments_url'] = $_POST['feedsky_comments_url'];
			update_option('feedsky_settings',$feedsky_settings);
			$flash = "您的设置已保存。";
		} 
	}	else {
		$flash = "您没有足够的权限。";
	}
	
	if ($flash != '') echo '<div id="message"class="updated fade"><p>' . $flash . '</p></div>';
	
	if (is_authorized()) {
	
		echo '<div class="wrap">';
		echo '<h2>设置您的 FeedSky Feed</h2>';
		echo '<p>本插件使您能够便捷地将您的 Feed 的所有访问流量转向至您创建的 FeedSky Feed 上。FeedSky 能够记录您的 Feed 的所有订阅流量以及订阅方式，并为您提供多种用来改进和增强 WordPress 原生 Feed 的功能。</p>
		<form action="" method="post">
		<input type="hidden" name="redirect" value="true" />
		<ol>
		<li>要开始使用，请先<a href="http://www.feedsky.com/add_feed_2.html?source_url=' . get_bloginfo('url') . '/wp-rss2.php" target="_blank">在 Feedsky 创建 ' . get_bloginfo('name') . ' 的 Feed</a>。此 Feed 将处理您文章的所有访问流量。</li>
		<li>如果您已创建了您的 FeedSky Feed，请将它的地址（http://feed.feedsky.com/yourfeed）输入至下方空白处：<br /><input type="text" name="feedsky_url" value="' . htmlentities($feedsky_settings['feedsky_url']) . '" size="45" /></li>
		<li>可选项：如果您还想使用 FeedSky 来处理您的 WordPress 评论 Feed，<a href="http://www.feedsky.com/add_feed_2.html?source_url=' . get_bloginfo('url') . '/wp-commentsrss2.php" target="_blank">创建一个 FeedSky 评论 Feed</a> 并将它的地址输入至下方：<br /><input type="text" name="feedsky_comments_url" value="' . htmlentities($feedsky_settings['feedsky_comments_url']) . '" size="45" />
		</ol>
		<p><input type="submit" value="保存" /></p></form>';
		echo '</div>';
	} else {
		echo '<div class="wrap"><p>抱歉，您没有访问本页面的许可。</p></div>';
	}

}

function feed_redirect() {
	global $wp, $feedsky_settings, $feed, $withcomments;
	if (is_feed() && $feed != 'comments-rss2' && !is_single() && $wp->query_vars['category_name'] == '' && ($withcomments != 1) && trim($feedsky_settings['feedsky_url']) != '') {
		if (function_exists('status_header')) status_header( 307 );
		header("Location:" . trim($feedsky_settings['feedsky_url']));
		header("HTTP/1.1 307 Temporary Redirect");
		exit();
	} elseif (is_feed() && ($feed == 'comments-rss2' || $withcomments == 1) && trim($feedsky_settings['feedsky_comments_url']) != '') {
		if (function_exists('status_header')) status_header( 307 );
		header("Location:" . trim($feedsky_settings['feedsky_comments_url']));
		header("HTTP/1.1 307 Temporary Redirect");
		exit();
	}
}

function check_url() {
	global $feedsky_settings;
	switch (basename($_SERVER['PHP_SELF'])) {
		case 'wp-rss.php':
		case 'wp-rss2.php':
		case 'wp-atom.php':
		case 'wp-rdf.php':
			if (trim($feedsky_settings['feedsky_url']) != '') {
				if (function_exists('status_header')) status_header( 307 );
				header("Location:" . trim($feedsky_settings['feedsky_url']));
				header("HTTP/1.1 307 Temporary Redirect");
				exit();
			}
			break;
		case 'wp-commentsrss2.php':
			if (trim($feedsky_settings['feedsky_comments_url']) != '') {
				if (function_exists('status_header')) status_header( 307 );
				header("Location:" . trim($feedsky_settings['feedsky_comments_url']));
				header("HTTP/1.1 307 Temporary Redirect");
				exit();
			}
			break;
	}
}

if (!preg_match("/feedsky|feedvalidator/i", $_SERVER['HTTP_USER_AGENT'])) {
    add_action('template_redirect',  'feed_redirect');
    add_action('init', 'check_url');
}

add_action('admin_menu', 'add_feedsky_options_page');

?>