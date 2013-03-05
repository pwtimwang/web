<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<base target="_blank" />
<meta property="wb:webmaster" content="ccca4b3bfbbf4da7" />
<link href="http://www.pingwest.com/wp-content/uploads/2013/01/pw120x120.jpg" rel="apple-touch-icon-precomposed">
<title><?php
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) echo " | $site_description";
	if ( $paged >= 2 || $page >= 2 ) echo ' | ' . sprintf( __( 'Page %s', 'imbalance2' ), max( $paged, $page ) );
?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
	wp_head();
?>

<style type="text/css">
/* color from theme options */
<?php $color = getColor() ?>
a, .menu a:hover, #nav-above a:hover, #footer a:hover, .entry-meta a:hover { color: <?php echo $color ?>; }
.fetch:hover { background: <?php echo $color ?>; }
blockquote { border-color: <?php echo $color ?>; }
.menu ul .current-menu-item a { color: <?php echo $color ?>; }
#respond .form-submit input { background: <?php echo $color ?>; }

/* fluid grid */
<?php if (!fluidGrid()): ?>
.wrapper { width: 940px; margin: 0 auto; }
<?php else: ?>
.wrapper { margin: 0 40px; }
<?php endif ?>
<?php if (!imagesOnly()): ?>
.box .categories { padding-top: 0; }
<?php endif ?>
</style>

<script type="text/javascript">
$(document).ready(function() {
	// shortcodes
	$('.wide').detach().appendTo('#wides');
	$('.aside').detach().appendTo('.entry-aside');

	// fluid grid
	<?php if (fluidGrid()): ?>
	function wrapperWidth() {
		var wrapper_width = $('body').width() - 20;
		wrapper_width = Math.floor(wrapper_width / 250) * 250 - 40;
		if (wrapper_width < 1000) wrapper_width = 1000;
		$('.wrapper').css('width', wrapper_width);
	}
	wrapperWidth();
	$(window).resize(function() {
		wrapperWidth();
	});
	<?php endif ?>

	// search
	$(document).ready(function() {
		$('#s').val('搜索文章');

		$('#s').bind('focus', function() {
			$(this).css('border-color', '<?php echo $color ?>');
			if ($(this).val() == '搜索文章') $(this).val('');
		});
	
		$('#s').bind('blur', function() {
			$(this).css('border-color', '#DEDFE0');
			if ($(this).val() == '') $(this).val('搜索文章');
		});

		if($('#mce-EMAIL')[0]) {
			$('#mce-EMAIL').bind('focus', function() {
				$(this).css('border-color', '<?php echo $color ?>');
				if ($(this).val() == '填写邮箱地址') $(this).val('');
			});
		
			$('#mce-EMAIL').bind('blur', function() {
				$(this).css('border-color', '#DEDFE0');
				if ($(this).val() == '') $(this).val('填写邮箱地址');
			});
		}
		
	});

<?php if (!is_singular()): ?>
	// grid
	$('#boxes').masonry({
	itemSelector: '.box',
	columnWidth: 340,
	gutterWidth: 20
	});

	$('#related').masonry({
	itemSelector: '.box',
	columnWidth: 340,
	gutterWidth: 20
	});
<?php endif ?>

	$('.texts').live({
		'mouseenter': function() {
			if ($(this).height() < $(this).find('.abs').height()) {
				$(this).height($(this).find('.abs').height());
			}
			$(this).stop(true, true).animate({
				'opacity': '1',
				'filter': 'alpha(opacity=100)'
			}, 0);
		},
		'mouseleave': function() {
			$(this).stop(true, true).animate({
				'opacity': '0',
				'filter': 'alpha(opacity=0)'
			}, 0);
		}
	});

	// comments
	$('.comment-form-author label').hide();
	$('.comment-form-author span').hide();
	$('.comment-form-email label').hide();
	$('.comment-form-email span').hide();
	$('.comment-form-url label').hide();
	$('.comment-form-comment label').hide();

	if ($('.comment-form-author input').val() == '')
	{
		$('.comment-form-author input').val('Name (required)');
	}
	if ($('.comment-form-email input').val() == '')
	{
		$('.comment-form-email input').val('Email (required)');
	}
	if ($('.comment-form-url input').val() == '')
	{
		$('.comment-form-url input').val('URL');
	}
	if ($('.comment-form-comment textarea').html() == '')
	{
		$('.comment-form-comment textarea').html('Your message');
	}
	
	$('.comment-form-author input').bind('focus', function() {
		$(this).css('border-color', '<?php echo $color ?>').css('color', '#333');
		if ($(this).val() == 'Name (required)') $(this).val('');
	});
	$('.comment-form-author input').bind('blur', function() {
		$(this).css('border-color', '<?php echo '#ccc' ?>').css('color', '#6b6b6b');
		if ($(this).val().trim() == '') $(this).val('Name (required)');
	});
	$('.comment-form-email input').bind('focus', function() {
		$(this).css('border-color', '<?php echo $color ?>').css('color', '#333');
		if ($(this).val() == 'Email (required)') $(this).val('');
	});
	$('.comment-form-email input').bind('blur', function() {
		$(this).css('border-color', '<?php echo '#ccc' ?>').css('color', '#6b6b6b');
		if ($(this).val().trim() == '') $(this).val('Email (required)');
	});
	$('.comment-form-url input').bind('focus', function() {
		$(this).css('border-color', '<?php echo $color ?>').css('color', '#333');
		if ($(this).val() == 'URL') $(this).val('');
	});
	$('.comment-form-url input').bind('blur', function() {
		$(this).css('border-color', '<?php echo '#ccc' ?>').css('color', '#6b6b6b');
		if ($(this).val().trim() == '') $(this).val('URL');
	});
	$('.comment-form-comment textarea').bind('focus', function() {
		$(this).css('border-color', '<?php echo $color ?>').css('color', '#333');
		if ($(this).val() == 'Your message') $(this).val('');
	});
	$('.comment-form-comment textarea').bind('blur', function() {
		$(this).css('border-color', '<?php echo '#ccc' ?>').css('color', '#6b6b6b');
		if ($(this).val().trim() == '') $(this).val('Your message');
	});
	$('#commentform').bind('submit', function(e) {
		if ($('.comment-form-author input').val() == 'Name (required)')
		{
			$('.comment-form-author input').val('');
		}
		if ($('.comment-form-email input').val() == 'Email (required)')
		{
			$('.comment-form-email input').val('');
		}
		if ($('.comment-form-url input').val() == 'URL')
		{
			$('.comment-form-url input').val('');
		}
		if ($('.comment-form-comment textarea').val() == 'Your message')
		{
			$('.comment-form-comment textarea').val('');
		}
	})

	$('.commentlist li div').bind('mouseover', function() {
		var reply = $(this).find('.reply')[0];
		$(reply).find('.comment-reply-link').show();
	});

	$('.commentlist li div').bind('mouseout', function() {
		var reply = $(this).find('.reply')[0];
		$(reply).find('.comment-reply-link').hide();
	});
});
</script>

<?php echo getFavicon() ?>
</head>

<body <?php body_class(); ?>>

<div id="top-bar">
	<div id="top-bar-inner">
    <div id="search">
            <?php get_search_form(); ?>
    </div> 
    </div>   	
 </div>

<div class="wrapper">    
        
    <div id="header">
    	<div id="site-title">
			<a target="_parent" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		</div>


		
		<div id="share-btn">
        	<a href="http://weibo.com/pingwest" class="share-weibo" title="新浪微博">新浪微博</a>
        	<a href="http://t.qq.com/pingwest" class="share-qq" title="腾讯微博">腾讯微博</a>
            <a href="#" class="share-fb" title="Facebook">Facebook</a>
            <a href="#" class="share-tt" title="Twitter">Twitter</a>
            <a href="#" class="share-linkedin" title="Linkedin">Linkedin</a>
            <a href="http://feed.feedsky.com/pingwest" class="share-rss" title="RSS">RSS</a>
        </div>


		
	</div>        
	<div id="main-menu" >
	<?php if(is_home()){ ?>
		<style type="text/css">
		.menu
		{
			float:left;
		}
		</style>
	<?php } ?>
			<?php wp_nav_menu( array( 'container_class' => 'menu', 'theme_location' => 'primary' ) ); ?>
			
			<?php if(is_home()){ ?>
	            <!--<div style="float:right; position:relative;top:8px"><wb:like type="number"></wb:like></div>-->
		    <?php } ?>
		
	</div>


	<div id="main">
