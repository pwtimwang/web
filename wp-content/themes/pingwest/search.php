<?php get_header(); ?>

		<div id="container">
			<div id="content" role="main">
             <?php if (function_exists('show_breadcrumb')) show_breadcrumb(); ?>
<?php if ( have_posts() ) : ?>
                <div id="search-results" class="post">
					<?php get_template_part( 'loop', 'search' ); ?>
                </div>
				<?php if ( function_exists( 'wp_pagenavi' ) )  wp_pagenavi(); ?>
<?php else : ?>
				<div id="post-0" class="post no-results not-found">
					<h1 class="entry-title"><?php _e( '没有找到相关结果', 'imbalance2' ); ?></h1>
						<p><?php _e( '换个关键字再试试看：', 'imbalance2' ); ?></p>
						<div id="page_search">
						<?php get_search_form(); ?>
						</div>
				</div><!-- #post-0 -->
<?php endif; ?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
