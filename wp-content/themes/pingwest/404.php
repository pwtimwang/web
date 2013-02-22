<?php get_header(); ?>

	<div id="container">
		<div id="content" role="main">

			<div id="post-0" class="post error404 not-found" style="height: 600px;">
				<h1 class="entry-title"><?php _e( '404 没有找到相关页面', 'imbalance2' ); ?></h1>
				<div class="entry-content">
					<p><?php _e( '你访问的页面不存在，可能是输入的网址不正确，或者是这个页面被删除了。', 'imbalance2' ); ?></p>
					<a href="/">返回首页</a>
					<br /><br />
				</div><!-- .entry-content -->
			</div><!-- #post-0 -->

		</div><!-- #content -->
        <div id="side">
				<?php get_sidebar(); ?>
	    </div>

	</div><!-- #container -->
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>

<?php get_footer(); ?>