			<div id="comments">
<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'imbalance2' ); ?></p>
			</div><!-- #comments -->
<?php
		return;
	endif;
?>

<?php if ( have_comments() ) : ?>
			<h3 id="comments-title"><?php
			printf(get_comments_number() );
			?> 条评论</h3>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span>更早的评论', 'imbalance2' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( '更新的评论 <span class="meta-nav">&rarr;</span>', 'imbalance2' ) ); ?></div>
			</div> <!-- .navigation -->
<?php endif; ?>

			<ol class="commentlist">
				<?php wp_list_comments( array( 'callback' => 'imbalance2_comment' ) ); ?>
			</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span>更早的评论', 'imbalance2' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( '更早的评论<span class="meta-nav">&rarr;</span>', 'imbalance2' ) ); ?></div>
			</div><!-- .navigation -->
<?php endif; ?>

<?php else :
	if ( ! comments_open() ) :
?>
<?php endif; ?>

<?php endif; ?>

<?php comment_form(array('comment_notes_after' => '', 'comment_notes_before' => '')); ?>

</div><!-- #comments -->
