<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                
               	<div class="posted"><?php imbalance2_posted_on() ?> <span class="main_separator">/</span>
				<?php echo comments_popup_link( __( '0条评论', 'imbalance2' ), __( '1条评论', 'imbalance2' ), __( '%条评论', 'imbalance2' ) ); ?>
				</div>
				<?php the_excerpt() ?>
                
<?php endwhile; // end of the loop. ?>

