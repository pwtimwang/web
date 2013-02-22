<?php
include "../../../wp-config.php";
$to;

session_start();


echo '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
echo '</head><body>';

echo "<br/>start:<br/>";

getPost();

echo '</body></html>';


function getPost()
{
	global $wpdb;
	global $post;

	if(isset($_GET['p']))
	{
		$post->ID = $_GET['p'];
	}
	else
	{
		//$query = "SELECT TIMESTAMPDIFF(HOUR, post_date, now()), post_date, ID FROM `wp_posts` where TIMESTAMPDIFF(HOUR, post_date, now()) < 72 and ID = 6692";
		$query ="SELECT ID FROM `wp_posts`";

		$posts = $wpdb->get_results( $query );

		echo var_dump($posts);
		
	}
	
	foreach($posts as $post)
	{		
		$meta_key = "lastsyc";
		$meta_value = "0";
		update_post_meta($post->ID, $meta_key, $meta_value);
	}	
}


?>