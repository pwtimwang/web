<?php
/**
 * Plugin Name: 评论最多的文章（Widget）
 * Plugin URI: http://www.pingwest.com
 * Description: 显示评论最多的文章
 * Version: 0.1
 * Author: Mikko
 * Author URI: http://www.pingwest.com
 *
 */
    
/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'most_commented_posts' );

/**
 * Register our widget.
 * 'Example_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function most_commented_posts() {
    register_widget( 'Most_Commented_Posts_Widget' );
}

/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class Most_Commented_Posts_Widget extends WP_Widget {

    /**
     * Widget setup.
     */
	public function __construct() {
		parent::__construct(
	 		'widget_most_commented_posts', // Base ID
			'评论最多的文章', // Name
			array( 'description' => __( '显示评论最多的文章', 'text_domain' ), ) // Args
		);
	}

    /**
     * How to display the widget on the screen.
     */
    function widget( $args, $instance ) {
        extract( $args );

        /* Our variables from the widget settings. */
        $title = apply_filters('widget_title', $instance['title'] );
        $count = $instance['count'];

        /* Before widget (defined by themes). */
        echo $before_widget;

        /* Display the widget title if one was input (before and after defined by themes). */
        if ( $title )
            echo $before_title . $title . $after_title;

		global $wpdb;
		$result = $wpdb->get_results("SELECT comment_count,ID,post_title FROM $wpdb->posts ORDER BY comment_count DESC LIMIT 0 ,".$count);

		echo '<ul>';
		foreach ($result as $post) {
			setup_postdata($post);
			$postid = $post->ID;
			$title = $post->post_title;
			$commentcount = $post->comment_count;
			if ($commentcount != 0) { 
				echo  '<li><a href="'.get_permalink($postid).'" title="'.$title.'">'.$title.'</a></li>';
			} 
		} 
		echo '</ul>';


        /* After widget (defined by themes). */
        echo $after_widget;
    }

    /**
     * Update the widget settings.
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        /* Strip tags for title and name to remove HTML (important for text inputs). */
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['count'] = absint( $new_instance['count'] );

        return $instance;
    }

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
    function form( $instance ) {

        /* Set up some default widget settings. */
        $defaults = array( 'title' => __('热门文章', 'most_commented_posts'), 'count' =>5);
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('小标题:', 'most_commented_posts'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>

        <!-- Your Name: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e('条目数:', 'most_commented_posts'); ?></label>
            <input id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" />
        </p>

    <?php
    }
}

?>