<?php
/*
Plugin Name: 中文摘要插件
Version: 1.0
Author: 树是我的朋友
Author URI: http://kutailang.com/
Plugin URI: http://kutailang.com/
Description: 由<a href="http://www.kutailang.com>"树是我的朋友</a>修改制作的中文摘要插件,有疑问请访问插件地址,或阅读readme.txt...该插件参考和应用了WP-UTF8-Excerp插件的代码.
*/

//定义选项默认值,分别是:首页摘要长度、存档页摘要长度、允许的标签、是否more标签有限、继续阅读字样
define ('HOME_EXCERPT_LENGTH', 150);
define ('ARCHIVE_EXCERPT_LENGTH', 150);
define ('ALLOWD_TAG', '<a><b><blockquote><cite><code><dd><del><div><dl><dt><em><h1><h2><h3><h4><h5><h6><i><img><li><ol><p><pre><span><strong><ul>');
define ('MORE_TAG_PRIOR', false);
define ('READ_MORE_LINK', __( '阅读全文', 'kutailang') );

//计算字符串长度(中文)函数
if ( !function_exists('mb_strlen') ) {
	function mb_strlen ($text, $encode) {
		if ($encode=='UTF-8') {
			return preg_match_all('%(?:
					  [\x09\x0A\x0D\x20-\x7E]           # ASCII
					| [\xC2-\xDF][\x80-\xBF]            # non-overlong 2-byte
					|  \xE0[\xA0-\xBF][\x80-\xBF]       # excluding overlongs
					| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
					|  \xED[\x80-\x9F][\x80-\xBF]       # excluding surrogates
					|  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
					| [\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
					|  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
					)%xs',$text,$out);
		}else{
			return strlen($text);
		}
	}
}

//截取字符函数
if (!function_exists('mb_substr')) {
    function mb_substr($str, $start, $len = '', $encoding="UTF-8"){
        $limit = strlen($str);
        for ($s = 0; $start > 0;--$start) {
            if ($s >= $limit)
                break;
            if ($str[$s] <= "\x7F")
                ++$s;
            else {
                ++$s;
                while ($str[$s] >= "\x80" && $str[$s] <= "\xBF")
                    ++$s;
            }
        }
        if ($len == '')
            return substr($str, $s);
        else
            for ($e = $s; $len > 0; --$len) {
                if ($e >= $limit)
                    break;
                if ($str[$e] <= "\x7F")
                    ++$e;
                else {
                    ++$e;
                    while ($str[$e] >= "\x80" && $str[$e] <= "\xBF" && $e < $limit)
                        ++$e;
                }
            }
        return substr($str, $s, $e - $s);
    }
}

//添加摘要过滤器函数
if (!function_exists('chinese_excerpt_for_excerpt')) {
	function chinese_excerpt_for_excerpt ($text) {
		return chinese_excerpt($text, 'excerpt');
	}
}
add_filter('get_the_excerpt', 'chinese_excerpt_for_excerpt', 9);

//为内容添加过滤器
if (!function_exists('chinese_excerpt_for_content')) {
	function chinese_excerpt_for_content ($text) {
		return chinese_excerpt($text, 'content');
	}
}
add_filter('the_content', 'chinese_excerpt_for_content', 9);

//判断是否有more标签函数,如果有把原来的"(readmore)"去掉
function chinese_excerpt_has_more( $more ) {
	if ( '' !== $more) {
		return '';
	} 
}
add_filter( 'the_content_more_link', 'chinese_excerpt_has_more' );

//给截取的摘要添加"阅读全文"字样
if (!function_exists('chinese_excerpt_readmore')) {
	function chinese_excerpt_readmore ($text) {
		$read_more_link = get_option('read_more_link') ? get_option('read_more_link') : READ_MORE_LINK;
		$text .= "......";
		//$text .= '<span class="read-more"><a href="'.get_permalink().'">'.$read_more_link.'</a></span>';
		return $text;
	}
}

//截取摘要的主要函数
if (!function_exists('chinese_excerpt')) {
	function chinese_excerpt ($text, $type) {
		//获取所有内容
		global $post;
		//选择首页代码类型
		switch ($type) {
			case 'content':
				//这里传递的$text变量是全部内容,获取手工添加的摘要
				$manual_excerpt = $post->post_excerpt;
				break;
			case 'excerpt':
				//这里传递的$text变量是手工添加的摘要
				$manual_excerpt = $text;
				//获取并整理所有内容
				$text = $post->post_content;
				$text = str_replace(']]>', ']]&gt;', $text);
				$text = trim($text);
				break;
			default:
				break;
		}
		//匹配more标签
		if ( preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches) ) {
			$if_has_more_tag = true;
		}
		//如果不是首页、归档页、搜索页直接返回
		if ( !is_home() && !is_archive() && !is_search() ) {
			return $text;
		}
		//如果存在手工添加的摘要，直接返回手工添加的摘要
		if ( '' !==  $manual_excerpt ) {
			$text = $manual_excerpt;
			$text = chinese_excerpt_readmore ($text);
			return $text;
		}
		//判断是否存在more标签
		switch ($type) {
			case 'content':
				//如果存在more标签
				$more_tag_prior = get_option('more_tag_prior') ? get_option('more_tag_prior') : MORE_TAG_PRIOR;
				if ($if_has_more_tag&&$more_tag_prior) {
					//删除这个21个字符的标记
					$text = chinese_excerpt_readmore ($text);
					return $text;
				}
				break;
			case 'excerpt':
				//这里内容后面有一个<!--more-->标签
		 		$more_position = stripos ($text, "<!--more-->");
				if ($more_position !== false) {
					$text = substr ($text, 0, $more_position);
					$text = chinese_excerpt_readmore ($text);
				    	return $text;
				}
				break;
			default:
				break;
		}
		//获取选项
		$home_excerpt_length = get_option('home_excerpt_length') ? get_option('home_excerpt_length') : HOME_EXCERPT_LENGTH;
		$archive_excerpt_length = get_option('archive_excerpt_length') ? get_option('archive_excerpt_length') : ARCHIVE_EXCERPT_LENGTH;
		$allowd_tag = get_option('allowd_tag') ? get_option('allowd_tag') : ALLOWD_TAG;
		//设置主页或存档页的摘要长度
		if ( is_home()) {
			$length = $home_excerpt_length;
		} elseif ( is_archive() || is_search() ) {
			$length = $archive_excerpt_length;
		}
		//用来控制下面函数是否执行
		$strip_short_post = true;
		 //如果文章已经很短了,用户希望去除标签
		if(($length > mb_strlen(strip_tags($text), 'utf-8')) && ($strip_short_post === true) ) {
			$text = strip_tags($text, $allowd_tag); 		
			$text = trim($text);
			$text = chinese_excerpt_readmore ($text);
			return $text;
		}
		//去除标签
		$text = strip_tags($text, $allowd_tag); 		
		$text = trim($text);
		//计算字数，截取摘要
		$num = 0;
		$in_tag = false;
		for ($i=0; $num<$length || $in_tag; $i++) {
			if(mb_substr($text, $i, 1) == '<')
				$in_tag = true;
			elseif(mb_substr($text, $i, 1) == '>')
				$in_tag = false;
			elseif(!$in_tag)
				$num++;
		}
		$text = mb_substr ($text,0,$i, 'utf-8');    
		$text = trim($text);
		$text = force_balance_tags($text);
		$text = chinese_excerpt_readmore ($text);
		return $text;
	}
}

/*后台选项*/
//添加动作
add_action('admin_menu', 'chinese_excerpt_menu');
//在后台注册菜单项
function chinese_excerpt_menu(){
	add_options_page(__( '摘要设置' , 'kutailang'), __( '摘要设置' , 'kutailang'), 8, __FILE__, 'chinese_excerpt_options');	
}

function chinese_excerpt_options() {
?>
<div class="wrap">
    <h2>
        <?php _e( '摘要选项' , 'kutailang'); ?>
    </h2>

<form name="form1" method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<!-- If the options are not set, load the default values.  -->
<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e('首页摘要长度:' , 'kutailang'); ?></th>
		<td><input type="text" name="home_excerpt_length" value="<?php echo get_option('home_excerpt_length') ? get_option('home_excerpt_length') : HOME_EXCERPT_LENGTH ?>" /><?php _e('字符' , 'kutailang'); ?></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('存档页摘要长度:' , 'kutailang'); ?></th>
		<td><input type="text" name="archive_excerpt_length" value="<?php echo get_option('archive_excerpt_length') ? get_option('archive_excerpt_length') : ARCHIVE_EXCERPT_LENGTH ?>"/><?php _e('字符' , 'kutailang'); ?></td>
	</tr>
	<tr valign="top">
		<th><?php _e('是否more标签优先:' , 'kutailang');?></th>
		<td>
			<?php $more_tag_prior = get_option('more_tag_prior') ? get_option('more_tag_prior') : MORE_TAG_PRIOR ?>
			<input type="checkbox" name="more_tag_prior"  <?php if($more_tag_prior){ echo 'checked="checked"';}?>/>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('允许的标签:' , 'kutailang'); ?></th>
		<td><input type="text" name="allowd_tag" value="<?php echo get_option('allowd_tag') ? get_option('allowd_tag') : ALLOWD_TAG ?>" style="width:400px"/></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('将"继续阅读"显示为:' , 'kutailang'); ?></th>
		<td><input type="text" name="read_more_link" value="<?php echo get_option('read_more_link') ? get_option('read_more_link') : READ_MORE_LINK ?>" style="width:400px" /></td>
	</tr>

</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="home_excerpt_length,archive_excerpt_length, more_tag_prior, allowd_tag, read_more_link" />

<p class="submit">
<input type="submit" class="button-primary" name="Submit" value="<?php _e('保存设置' , 'kutailang') ?>" />
</p>

</form>
</div>
<?php
}
?>