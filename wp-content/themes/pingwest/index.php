﻿<?php get_header(); ?>

<script type="text/javascript">
function closeSubject()
{
	$("#subject").slideUp('slow');
}
</script>


<div id="subject" style="position:relative;width:940px;height:100px;">
	<a style="width:940px;height:100px;" href="http://pingwest.com/giant2013/index.html">
		<img style="width:940px;height:100px;" src="http://pingwest.com/giant2013/images/banner.png"></img>
	</a>
	<span style="position:absolute; top:0px;right:0px; background:url(http://pingwest.com/wp-content/themes/pingwest/images/close1.gif) no-repeat;width:18px;height:18px;float:right" title="关闭" onclick="closeSubject();"></span>
</div>



    <div id="wall">
    <?php 
    
    query_posts('posts_per_page=5&meta_key=_jsFeaturedPost&meta_value=yes');	
    
    // The Loop
	$counter = 1;
    while ( have_posts() ) : the_post(); ?>

	<div class="wall-<?php echo $counter ?>">
  		<a href="<?php the_permalink(); ?>">
<?php 
		$wall_image = get_post_meta($post->ID, 'wall_image', true);
		if($wall_image != '') {
  			echo '<img class="wall-img wp-post-image" alt="" title="" src="'.$wall_image.'" >';
		} else {
			the_post_thumbnail('homepage-thumb', array('alt' => '', 'title' => '', 'class' => 'wall-img')); 
		}
?>
            <span class="wall-caption">
				<span class="wall-bg">&nbsp;</span>
				<span class="wall-title"><?php the_title() ?></span>
                <?php if( $counter == 1):?>
            	<span class="wall-excerpt">
            	<?php the_excerpt_max_charlength(140); ?>
                </span>
            <?php endif ?>
			</span>
        </a>

      </div>
		<?php $counter ++;
    endwhile;
    
    // Reset Query
    wp_reset_query(); ?>
    </div>
    




		<div id="container">
			<div id="content" role="main">
				<?php get_template_part( 'loop', 'index' ); ?>
			</div><!-- #content -->
            <div id="side">
				<?php get_sidebar(); ?>
	    	</div>
		</div><!-- #container -->
	
<?php get_footer(); ?>

