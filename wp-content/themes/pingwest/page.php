<?php get_header(); ?>

		<div id="container">
			<div id="content" role="main">

			<?php get_template_part( 'loop', 'page' ); ?>

			</div><!-- #content -->
            <div id="side">
				<?php get_sidebar(); ?>
	    	</div>
		</div><!-- #container -->

<?php get_footer(); ?>
