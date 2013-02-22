<?php get_header(); ?>

		<div id="container">
			<div id="content" role="main">
             <?php if (function_exists('show_breadcrumb')) show_breadcrumb(); ?>
				<?php get_template_part( 'loop', 'single' ); ?>
			</div><!-- #content -->
            <div id="side">
				<?php get_sidebar(); ?>
	    	</div>
		</div><!-- #container -->
	
<?php get_footer(); ?>
