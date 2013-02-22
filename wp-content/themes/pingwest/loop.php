<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( '没找到相关内容', 'imbalance2' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( '可以试着搜索一下：' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php $imbalance2_theme_options = get_option('imbalance2_theme_options') ?>

<div id="boxes">
<?php while ( have_posts() ) : the_post(); ?>

	<div class="box">
		<div class="rel">
			<!-- <div class="categories"><?php imbalance2_posted_in(); ?></div> -->
			<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<div class="posted"><?php imbalance2_posted_by() ?><span class="main_separator">/</span><?php imbalance2_posted_on() ?> <span class="main_separator">/</span>
				<?php echo comments_popup_link( __( '0条评论', 'imbalance2' ), __( '1条评论', 'imbalance2' ), __( '%条评论', 'imbalance2' ) ); ?>
			</div>
  			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('homepage-thumb', array('alt' => '', 'title' => '')) ?></a>
			<?php the_excerpt() ?>
			<a href="<?php the_permalink(); ?>" class="read-more">继续阅读</a>
			<div class="texts">
				<!-- <div class="categories"><?php imbalance2_posted_in(); ?></div>  -->
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<div class="posted"><?php imbalance2_posted_by() ?><span class="main_separator">/</span><?php imbalance2_posted_on() ?> <span class="main_separator">/</span>
				<?php echo comments_popup_link( __( '0条评论', 'imbalance2' ), __( '1条评论', 'imbalance2' ), __( '%条评论', 'imbalance2' ) ); ?>
				</div>
				<div class="abs">
				<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('homepage-thumb', array('alt' => '', 'title' => '')) ?></a>
				<?php the_excerpt() ?>
				<a href="<?php the_permalink(); ?>" class="read-more">继续阅读</a>
				</div>
			</div>
		</div>
	</div>

<?php endwhile; ?>
</div>

<?php if(function_exists( 'wp_pagenavi')) wp_pagenavi(); ?>