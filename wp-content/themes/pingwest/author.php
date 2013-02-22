<?php get_header(); ?>

		<div id="container">
			<div id="content" role="main">

<?php if ( have_posts() ) the_post(); 

		if (function_exists('show_breadcrumb')) show_breadcrumb(); 
			 
	rewind_posts();
	get_template_part( 'loop', 'author' );
?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
