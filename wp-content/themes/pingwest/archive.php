<?php get_header(); ?>

		<div id="container">
			<div id="content" role="main">

<?php
	 if (function_exists('show_breadcrumb')) show_breadcrumb(); 
	get_template_part( 'loop', 'archive' );
?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
