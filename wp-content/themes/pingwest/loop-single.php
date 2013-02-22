<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	
    <div class="post" id="post-<?php the_ID(); ?>" >
    	<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
						<?php imbalance2_posted_by() ?>
						<span class="main_separator">/</span>
						<?php imbalance2_posted_on() ?>
						<span class="main_separator">/</span>
						<?php imbalance2_posted_in() ?>
						<span class="main_separator">/</span>
						<?php if ( get_comments_number() != 0 ) : ?>
		<a href="#idenglu_comments" target="_parent"><?php printf( _n( '1条评论', '%1$s条评论', get_comments_number(), 'imbalance2' ),
							number_format_i18n( get_comments_number() )
						); ?></a>
	<?php else: ?>
						<a href="#idenglu_comments" target="_parent">0条评论</a>
	<?php endif ?>
		</div><!-- .entry-meta -->
	
    <?php the_content(); ?>
    
        <div class="entry-utility">
		<?php imbalance2_tags() ?>
		<?php edit_post_link( __( '编辑文章', 'imbalance2' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-utility -->
    
    		<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
                                <div id="entry-author-info">
                                    <div id="author-avatar">
                                        <?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'imbalance2_author_bio_avatar_size', 50 ) ); ?>
                                    </div><!-- #author-avatar -->
                                <div id="author-description">
                                        <h3><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></h3>
                                        <p><?php the_author_meta( 'description' ); ?></p>
                                        <p>邮箱: <a href="mailto:<?php the_author_meta( 'user_email'); ?>"><?php the_author_meta( 'user_email'); ?></a></p>
                                    </div><!-- #author-description -->
                                </div><!-- #entry-author-info -->
		<?php endif; ?>

    
	<?php if(!function_exists( 'wp_pagenavi')) wp_pagenavi( array( 'type' => 'multipart' ) ); ?>


		</div><!-- #post-## -->

	<div id="nav-below" class="navigation">
		<div class="nav-previous">
	<?php if (get_previous_post(false) != null): ?>
							<?php previous_post_link( '%link', '&laquo; 前一篇: %title' ); ?>
	<?php endif ?>
						</div>
						<div class="nav-next">
	<?php if (get_next_post(false) != null): ?>
							<?php next_post_link( '%link', '&raquo; 后一篇: %title' ); ?>
	<?php endif ?>
						</div>
					</div><!-- #nav-above -->
				<?php comments_template( '', true ); ?>

<?php endwhile; ?>