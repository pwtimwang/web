<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<div id="side" class="widget-area" role="complementary">
<ul id="side-widgets">
<?php 
	if ( is_active_sidebar( 'side-widget-area' ) ) : 
			dynamic_sidebar( 'side-widget-area' ); 
    endif;
	// If we get this far, we have widgets. Let do this.
 ?>
</ul><!-- #first .widget-area -->

</div><!-- #primary .widget-area -->
