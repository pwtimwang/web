<?php get_header(); ?>

		<div id="container">
			<div id="content" role="main">
             <?php if (function_exists('show_breadcrumb')) show_breadcrumb(); ?>

				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="archive-meta">' . $category_description . '</div>';

					get_template_part( 'loop', 'category' );
				?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
