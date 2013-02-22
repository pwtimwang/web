<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" class="post">
					<?php if ( is_front_page() ) { ?>
						<h2 class="entry-title"><?php the_title(); ?></h2>
					<?php } else { ?>
						<h1 class="entry-title"><?php the_title(); ?></h1>
					<?php } ?>

						<?php the_content(); ?>

						<?php if (function_exists( 'wp_pagenavi')) wp_pagenavi( array( 'type' => 'multipart' ) ); ?>
						<?php edit_post_link( __( '编辑页面', 'imbalance2' ), '<span class="edit-link">', '</span>' ); ?>					
	
				</div><!-- #post-## -->

				<?php comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>