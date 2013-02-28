<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0649)http://open.denglu.cc/connect/comment.jsp?appid=20592denRbLvRNV4o0NRZSqfVtBsXA&domain=www.pingwest.com&postid=5118&title=%E2%80%9C%E6%B5%81%E6%B0%93%E5%8A%A9%E6%89%8B%E2%80%9D%E5%92%8C%E5%AE%83%E7%9A%84%E6%9C%8B%E5%8F%8B%E4%BB%AC&from=http%3A%2F%2Fwww.pingwest.com%2Fwhat-is-chinese-market-the-hell%2F&image=http%3A%2F%2Fwww.pingwest.com%2Fwp-content%2Fuploads%2F2013%2F01%2F%E5%B1%8F%E5%B9%95%E5%BF%AB%E7%85%A7-2013-01-19-%E4%B8%8A%E5%8D%888.21.22.png&exit=http%3A%2F%2Fwww.pingwest.com%2Fwp-login.php%3Faction%3Dlogout%26amp%3Bredirect_to%3Dhttp%253A%252F%252Fwww.pingwest.com%252Fwhat-is-chinese-market-the-hell%252F%26amp%3B_wpnonce%3Da5ec8b1a49 -->
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<?php 
	global $PWPlugin_url;
?>
	

<title>评论</title>
<link href="<?php echo $PWPlugin_url;?>/css/static.css" rel="stylesheet" type="text/css">
<link href="<?php echo $PWPlugin_url;?>/css/model3.css" rel="stylesheet" type="text/css">
<style type="text/css">
</style>

<script type="text/javascript">
var ajaxUrl = "<?php echo js_escape( get_bloginfo( 'wpurl' ) . '/wp-admin/admin-ajax.php' ); ?>";
var nonce ="<?php echo js_escape(wp_create_nonce( 'ajaxnonce' ));?>";
var usrInfo;
var commenttext;
usrInfo = {screen_name : "匿名",id : "",profile_image_url:'http://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=96'};

var isWeiboUser = false;

function login(o) {
	//alert(o.screen_name);
	usrInfo = o;
	$(".dl_publish_textarea .dl_avatar img").each(function(){
		this.src = usrInfo.profile_image_url;
	});
	isWeiboUser = true;
}
 
function logout() {
	isWeiboUser = false;
	usrInfo = {screen_name : "匿名",id : "",profile_image_url:'http://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=96'};
}

//发布微博
function publish(c){
	$sync = $($(c).parent().parent().find('ul > li > ul > li')[1]).attr('ck');
	$("label#commenttext_error").text('This field is required').hide();
	commenttext = $.trim($('#pw_comment_content').val()) ? $.trim($('#pw_comment_content').val()):$.trim($('#pw_reply_content').val());
	
	if ("" == commenttext) {
		$("label#commenttext_error").text('This field is required').show().focus();
		return false;
	}
	
	if(isWeiboUser && ($sync == '1'))
	{
		commenttext = encodeURI(commenttext);
		WB2.anyWhere(function(W){
			// 获取评论列表
			W.parseCMD("/statuses/update.json", 
			function(sResult, bStatus){
				if(bStatus == true) 
				{
					//alert(sResult);//ddddd
					newcomment(sResult.id);
				}
			},
			{
				status : commenttext + '  <?php echo get_permalink($post->ID);?>'
			},
			{
					method: 'post'
			});
		});
	}
	else
	{
		newcomment('-1');
	}
}

function buildCommentHTML(commentID){
	usrurl = usrInfo.id? 'http://weibo.com/' + usrInfo.id : "";
	
	return '<div id="c-' + commentID + '" class="dl_post" style="display:none"><div class="dl_post_main"><div class="dl_post_avatar"><img alt="" src="'+
			usrInfo.profile_image_url + '" class="avatar avatar-96 photo avatar-default" height="96" width="96"></div>' + 
			'<div class="dl_post_body"><div class="dl_post_name"><div class="dl_name"><a class="dl_name_text" rel="nofollow" target="_blank" href="' + usrurl + '">' + 
			usrInfo.screen_name + '</a>'+
			'<span class="dl_name_time" title="发表于2013-01-29 15:04:28">刚刚</span></div></div><div class="dl_post_content"><p>' + 
			commenttext + '</p></div><div class="dl_post_control layout">'+
			'<ul class="dl_control_message"><li class="dl_control_time" style="padding-top:0px; border-top:none" title="">刚刚</li></ul>'+
			'<ul class="dl_post_function"><li class="dl_function_reply" style="padding-top:0px; border-top:none"><span></span>' + 
			'<a rel="nofollow" target="_parent" data-author="' + usrInfo.screen_name + '" class="comment-reply-link" href="javascript:;" onclick="return moveForm1(this, ' + commentID + ')">回复</a>' + 
			'</li></ul></div></div></div></div>' +
			'<div id="idenglu_replys_' + commentID + '" class="dl_reply"></div>';
}
	
	
function newcomment(sinaid)
{
	commenttext = $.trim($('#pw_comment_content').val()) ? $.trim($('#pw_comment_content').val()):$.trim($('#pw_reply_content').val());
	if ("" == commenttext) {
		$("label#commenttext_error").text('This field is required').show().focus();
		return false;
	}
	
	var comment_post_ID = <?php echo $post->ID;?>;
	var comment_parent = currentParentComment;

	var dataString = {action: 'prologue_new_comment' , _ajax_post: nonce, comment: commenttext,  comment_parent: comment_parent, comment_post_ID: comment_post_ID,
	comment_author: usrInfo ? usrInfo.screen_name: "",
	comment_author_email:usrInfo.id ? usrInfo.id + '@weibo.com' : "",
	comment_author_url: usrInfo.id? 'http://weibo.com/' + usrInfo.id : "",
	comment_agent: 'pw_comment_agent',
	sinaid: sinaid
	};
	var errorMessage = '';
	
	var errorMessage = '';
	$.ajax({
		type: "POST",
		url: ajaxUrl,
		data: dataString,
		success: function(result) {
			
			$("label#commenttext_error").text('DB result: ' + result);
			
			var lastComment = $("#respond").prev("li");
			if (isNaN(result) || 0 == result || 1 == result)
				errorMessage =result;
			$('#comment').val('');
			if (errorMessage != "")
				return false;

			$("#respond").slideUp('fast');
			
			commentHTML = buildCommentHTML(result);	
			if(currentParentComment == 0)
			{
				if($('#idenglu_comments .dl_post:first').length)
				{
					$('#idenglu_comments .dl_post:first').before(commentHTML);
				}
				else
				{
					$('#idenglu_comments')[0].innerHTML = commentHTML;
				}
			}
			else
			{
				if($('#idenglu_replys_' + currentParentComment + ' .dl_relay_row:first').length)
				{
					$('#idenglu_replys_' + currentParentComment + ' .dl_relay_row:first').before(commentHTML);
				}
				else
				{
					$('#idenglu_replys_' + currentParentComment)[0].innerHTML = commentHTML;
				}
				$('#c-' + result).attr('class', 'dl_relay_row');
			}
			
			$('#c-' + result).slideDown();
			$('#response').slideUp();
			
			result = 0;	
			$('#pw_comment_content').val("");
			$('#pw_reply_content').val("");
		  }
	});
}

var currentParentComment = 0;

function moveForm1(tag,commentID)
{
	if(currentParentComment!=commentID)
	{
		$("#response").slideUp('fast',function(){
		$("#c-"+commentID).after($("#response"));
		$("#response").slideDown();});
		currentParentComment = commentID;
		
		$('#pw_reply_content').val("回复@" + $(tag).attr("data-author") + ": ");
	}
	else
	{
		$("#response").slideUp();
		currentParentComment = 0;
		$('#pw_reply_content').val();
	}
}	

function clickSynchro(c)
{
	var a=$(c);
	var b=a.attr("mid");
	if(a.attr("ck")==1)
	{
		a.attr("ck",0).removeClass("icon_"+b).addClass("icon_"+b+"_n");
	}
	else
	{
		$(c).attr("ck",1).removeClass("icon_"+b+"_n").addClass("icon_"+b);
	}
}

	
</script>


<!--
<script src="<?php echo $PWPlugin_url;?>/css/comment_a.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $PWPlugin_url;?>/css/model3.js"></script>
-->


</head>
<body>




<div class="dengluComments">
<div class="dl_comment">
	<!--头部信息及功能 begin-->
	<div class="dl_head layout">
		<div class="dl_head_tlt">PingWest</div>
		<ul class="dl_head_link layout">
		</ul>
	</div>
	<!--头部信息及功能 end-->
	<!--评论框部分 begin-->
	<div class="dl_publish">
		<div class="dl_publish_login">
			<div id="idenglu_userinfo" class="dl_publish_success">
				<wb:login-button type="3,2" onlogin="login" onlogout="logout" ></wb:login-button>
				<label id="commenttext_error"></label>
			</div>
		</div>
		<div class="dl_publish_textarea">
			<!--<div class="dl_avatar"><img src="http://1.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=96"></div>-->
			<div class="dl_textarea">
			<div class="dl_textarea_jiao"></div>
				<div class="dl_textarea_input"><textarea id="pw_comment_content"></textarea></div>
				<div class="dl_textarea_tool layout">
					<ul class="dl_tool_btn layout">
						<!--同步部分 begin-->
						<li  class="dl_tool_feed">
							<ul class="dl_feed_layout layout">
								<li class="dl_feed_text">同步到：</li>
								<li onclick="clickSynchro(this);" mid="3" ck="0" class="icon_3_n"></li>
							</ul>
						</li>
						<!--同步部分 end-->
					</ul>
					<div class="dl_tool_submit">
						<a class="dl_submit_btn" target="_parent" href="javascript:;" onclick="currentParentComment = 0; publish(this);">发布评论</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--评论框部分 end-->
	<!--评论列表信息部分 包括信息、排序、分页等 begin-->
	<div class="dl_infor layout">
		<div class="dl_infor_message layout">
			<div class="dl_message">已有<em><?php echo get_comments_number($post->ID); ?></em>条评论</div>
			<div class="dl_sort">

			</div>
		</div>
		<div class="dl_infor_page layout"></div>

	</div>
	<!--评论列表信息部分 包括信息、排序、分页等 end-->
	<!--评论列表 begin-->
	<div id="idenglu_comments" class="dl_list">
	
<?php 
global $post;
$args = array(
	'status' => 'approve',
	'search' => '',
	'user_id' => '',
	'offset' => '',
	'number' => '',
	'post_id' => $post->ID,
	'type' => '',
	'orderby' => '',
	'order' => '',
	);

function pw_get_childrencomments( $comments, $parentcomment ) 
{
	$children = array();
	foreach($comments as $comment)
		{
		if($comment->comment_parent == $parentcomment->comment_ID )
			{
				array_push($children, $comment);
			}
		}
	return $children;
}

function outputcomment($Allcomments, $tt, $styleClass)
{
	global $comment;
	$comment = $tt;
?>
	<div id="c-<?php echo $tt->comment_ID; ?>" class="<?php echo $styleClass; ?>">
		<div class="dl_post_main">
			<div class="dl_post_avatar">
				<?php echo get_avatar($tt); ?>
			</div>
			<div class="dl_post_body">
				<div class="dl_post_name">
					<div class="dl_name">
						<a class="dl_name_text" rel="nofollow" target="_blank" href="<?php echo $tt->comment_author_url;?>"><?php comment_author(); ?></a>
						<?php if(strpos($tt->comment_author_url, 'weibo.com/'))
						{ ?>
							<span class="dl_name_icon icon_3"></span>
							<span class="dl_name_from">来自新浪微博</span>
						<?php } ?>
					</div>
				</div>
				<div class="dl_post_content">
					<p><?php echo $tt->comment_content; ?></p>
				</div>
				<div class="dl_post_control layout">
					<ul class="dl_control_message">
						<li class="dl_control_time" style="padding-top:0px; border-top:none" title="发表于<?php echo $tt->comment_date; ?>"><?php echo $tt->comment_date; ?></li>
					</ul>
					<ul class="dl_post_function">
						<li class="dl_function_reply"  style="padding-top:0px; border-top:none">
							<span></span>
							<a rel="nofollow" target="_parent" data-author="<?php comment_author(); ?>" class="comment-reply-link" href="javascript:;" onclick='return moveForm1(this,<?php echo $tt->comment_ID; ?>);'>回复</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div id="idenglu_replys_<?php echo $tt->comment_ID; ?>" class="dl_reply">
		<?php
		if($children = pw_get_childrencomments($Allcomments, $tt))
		{
			foreach($children as $yy)
			{
				outputcomment($Allcomments, $yy, 'dl_relay_row');
			}
		}
		 
		?>
	</div>

<?php }

$Allcomments = get_comments($args);

global $comment;
if ( have_comments() )
{
	foreach($Allcomments as $comment)
	{
		if($comment->comment_parent ==0)
		{
		outputcomment($Allcomments, $comment, 'dl_post');

		}
	}
}
?>

	
</div>
</div>
	<!--评论列表 end-->
	<div class="dl_foot">
		<div class="dl_infor layout">
			<div class="dl_infor_message">
			        <div class="dl_message">已有<em>28</em>条评论,共<em>18</em>人参与</div>
					<div class="dl_sort"></div>
			</div>
			<div class="dl_infor_page layout"></div>
		</div>
	</div>
</div>
<!--灯鹭社会化评论 end-->

<!--回复框 -->
<div id="response" class="dl_publish_textarea" class="dl_reply" style="display: none;">
	<div class="dl_avatar"><img src="http://1.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=96"></div>
	<div class="dl_textarea">
		<div class="dl_textarea_input"><textarea id="pw_reply_content"></textarea></div>
		<div class="dl_textarea_tool layout">
			<ul class="dl_tool_btn layout">
				<!--同步部分 begin-->
				<li  class="dl_tool_feed">
					<ul class="dl_feed_layout layout">
						<li class="dl_feed_text">同步到：</li>
						<li onclick="clickSynchro(this);" mid="3" ck="0" class="icon_3_n"></li>
					</ul>
				</li>
				<!--同步部分 end-->
			</ul>	
			<div class="dl_tool_submit">		
				<a class="dl_submit_btn" target="_parent" href="javascript:;" onclick="return publish(this);">立即回复</a>
			</div>
		</div>
	</div>
</div>
<!--回复框 end -->


<!--</div>-->



</body></html>