<?php
/**
 * The Footer widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
<div id="footer-list" class="widget-area" role="complementary">

<?php
	/* The footer widget area is triggered if any of the areas
	 * have widgets. So let's check that first.
	 *
	 * If none of the sidebars have widgets, then let's bail early.
	 */
	 if ( is_active_sidebar( 'footer-widget-area' ) ) : ?>
   		<ul id="footer-widgets">
			<?php dynamic_sidebar( 'footer-widget-area' ); ?>
		</ul><!-- #first .widget-area -->
     <?php endif;
	// If we get this far, we have widgets. Let do this.
 ?>
</div>