<?php
// 得到微博头像
if (!function_exists('wp_get_weibo_head')) {
	function wp_get_weibo_head($comment, $size, $email, $author_url) {
		$tname = array('@weibo.com' => '3',
			'@t.qq.com' => '4',
			'@t.sohu.com' => '5',
			'@renren.com' => '7',
			'@kaixin001.com' => '8',
			'@douban.com' => '9',
			'@qzone.qq.com' => '13',
			'@baidu.com' => '19',
			'@tianya.cn' => '17',
			'@twitter.com' => '28'
			);
		$tmail = strstr($email, '@');
		if ($mediaID = $tname[$tmail]) {
			$weibo_uid = str_replace($tmail, '', $email);
			if ($mediaID == 3) {
				$out = 'http://tp' . rand(1, 4) . '.sinaimg.cn/' . $weibo_uid . '/50/0/1';
			} elseif ($mediaID == 9) {
				$out = 'http://img' . rand(1, 5) . '.douban.com/icon/u' . $weibo_uid . '-1.jpg';
			} elseif ($mediaID == 13) {
				$out = 'http://qzapp.qlogo.cn/qzapp/' . $weibo_uid . '/50';
			} elseif ($mediaID == 17) {
				$out = 'http://tx.tianyaui.com/logo/small/' . $weibo_uid;
			} elseif (function_exists('get_avatar_url')) {
				$out = get_avatar_url($weibo_uid, $mediaID);
				if ($out) {
					if ($mediaID == 4) {
						$out = 'http://app.qlogo.cn/mbloghead/' . $out . '/50';
					} elseif ($mediaID == 19) {
						$out = 'http://himg.bdimg.com/sys/portraitn/item/' . $out . '.jpg';
					} 
				} 
			} 
			if ($out) {
				$avatar = "<img alt='' src='{$out}' class='avatar avatar-{$size}' height='{$size}' width='{$size}' />";
				if ($author_url) {
					$avatar = "<a href='{$author_url}' rel='nofollow' target='_blank'>$avatar</a>";
				} 
				return $avatar;
			} 
		} 
	} 
}

add_filter("get_avatar", "wp_connect_avatar", 9, 3);

function wp_connect_avatar($avatar, $id_or_email = '', $size = '32') {
	global $comment, $parent_file, $wp_version;
	if (is_numeric($id_or_email)) { // users.php
		$uid = $userid = (int) $id_or_email;
		$user = get_userdata($uid);
		if ($user) $email = $user -> user_email;
	} elseif (is_object($comment)) {
		$uid = $comment -> user_id;
		$email = $comment -> comment_author_email;
		$author_url = $comment -> comment_author_url;
		if ($avatar1 = wp_get_weibo_head($comment, $size, $email, $author_url)) { // V2.4
			return $avatar1;
		} 
		if ($uid) $user = get_userdata($uid);
	} elseif (is_object($id_or_email)) {
		$user = $id_or_email;
		$uid = $user -> user_id;
		$email = ifab($user -> comment_author_email, $user -> user_email);
		$author_url = $user -> comment_author_url;
		if ($avatar1 = wp_get_weibo_head($user, $size, $email, $author_url)) { 
			return $avatar1;
		} 
	} else {
		$email = $id_or_email;
		if ($parent_file != 'options-general.php') {
			$user = get_user_by_email($email);
			$uid = $user -> ID;
		} 
	} 
	if (!$email) {
		return $avatar;
	} 
	if ($uid) {
		$tid = $user -> last_login;
		if (!$tid) {
			$tname = array('@t.sina.com.cn' => 'stid',
				'@weibo.com' => 'stid',
				'@t.qq.com' => 'qtid',
				'@renren.com' => 'rtid',
				'@kaixin001.com' => 'ktid',
				'@douban.com' => 'dtid',
				'@t.sohu.com' => 'shtid',
				'@t.163.com' => 'ntid',
				'@baidu.com' => 'bdtid',
				'@tianya.cn' => 'tytid',
				'@twitter.com' => 'ttid'
				);
			$tmail = strstr($email, '@');
			$tid = $tname[$tmail];
		} 
		if ($tid) {
			if (($tid == 'qqtid' && !$user -> qqid) || ($tid == 'tbtid' && !$user -> taobaoid))
				return $avatar;
			if ($head = $user -> $tid) {
				$weibo = get_weibo($tid);
				$out = ($weibo[5]) ? str_replace('[head]', $head, $weibo[5]) : $head;
				$avatar = "<img alt='' src='{$out}' class='avatar avatar-{$size}' height='{$size}' width='{$size}' />";
				if ($weibo[3]) {
					$oid = $weibo[1] . 'id';
					$username = $user -> $oid;
					if ($username) {
						$url = $weibo[3] . $username;
						if ($userid) {
							if (is_admin()) { 
								if (version_compare($wp_version, '3.4', '<')) {
									if (!is_admin_footer()) $avatar = "<a href='{$url}' target='_blank'>$avatar</a>";
								} else {
									if (is_admin_footer()) $avatar = "<a href='{$url}' target='_blank'>$avatar</a>";
								} 
							}
						} else {
							$avatar = "<a href='{$url}' rel='nofollow' target='_blank'>$avatar</a>";
						}
					} 
				} 
			} 
		} elseif ($user -> qqid && $out = $user -> qqtid) {
			$avatar = "<img alt='' src='{$out}' class='avatar avatar-{$size}' height='{$size}' width='{$size}' />";
		} elseif ($user -> taobaoid && $out = $user -> tbtid) {
			$avatar = "<img alt='' src='{$out}' class='avatar avatar-{$size}' height='{$size}' width='{$size}' />";
		} 
	} 
	return $avatar;
} 


if (!function_exists('denglu_comments')) {
	if (!$wptm_comment['manual']) {
		add_filter('comments_template', 'denglu_comments');
		function denglu_comments($file) {
			global $post;
			
			//2013/01/16 SCR Tim Don't display denglu comments in home page.
			if (!is_home() && comments_open()) {
				//return dirname(__FILE__) . '/comments.php';
				return dirname(__FILE__) . '/commentsTemplates-1.php';
			} 
		} 
	} 
}
?>