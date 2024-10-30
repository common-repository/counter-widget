<?php
/* 
Plugin Name: Counter Widget
Plugin URI: http://cyberbundle.com/counter-widget/	
Description: Displays number of posts, categories, comments and members from your website.
Version: 1.0
Author: Cyberbundle
Author URI: http://cyberbundle.com
License: GPL2

Counter Widget is a free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Counter Widget is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Counter Widget. If not, see www.gnu.org/licenses/old-licenses/gpl-2.0.html.
*/

add_action( 'wp_enqueue_scripts', 'cw_add_css' );

function cw_add_css() {
        wp_register_style( 'cwStyle', plugins_url('css/cwStyle.css', __FILE__) );
		wp_enqueue_style( 'cwStyle' );
}

add_action( 'widgets_init', 'cw_register_widget_init' );
  
function cw_register_widget_init() {
    register_widget( 'cw_counts' );
}
 
class cw_counts extends WP_Widget {
		
    public function cw_post_count() {
        $count_posts = wp_count_posts('post');
        $published_posts = $count_posts->publish; 
        echo $published_posts;
    }
    public function cw_category_count() {
    $category = array(
	'type' => 'post',
	'taxonomy' => 'category',
    );
	
    $categories = get_categories( $category );
    $category_count = count($categories);
    echo $category_count;
    }

    public function cw_comment_count() {
    $comments_count = wp_count_comments();
    $total_comments = $comments_count->total_comments;
    echo $total_comments;
    }

    public function cw_users_count() {
    $result = count_users();
    $total_users = $result['total_users'];
    echo $total_users;
    }
	
    public function __construct()
    {
        $widget_details = array(
            'classname' => 'cw_counts',
            'description' => 'Widget for displaying post, category, comment and user count.'
        );
 
        parent::__construct( 'cw_counts', 'Counter Widget', $widget_details );
    }
 
    public function form( $instance ) {
    $defaults = array( 'cw-post-title' => 'Posts', 'cw-category-title' => 'Categories', 'cw-comment-title' => 'Comments', 'cw-user-title' => 'Members' );
    $instance = wp_parse_args( (array) $instance, $defaults );
	?>
	<p>
    <label for="<?php echo $this->get_field_id('cw-post-title'); ?>">Post Count Title:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('cw-post-title'); ?>" name="<?php echo $this->get_field_name('cw-post-title'); ?>" type="text" value="<?php echo $instance['cw-post-title']; ?>" />
    </p>
	<p>
    <input class="checkbox" type="checkbox" <?php checked( $instance[ 'display_post_count' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'display_post_count' ); ?>" name="<?php echo $this->get_field_name( 'display_post_count' ); ?>" /> 
    <label for="<?php echo $this->get_field_id( 'display_post_count' ); ?>">Hide post count</label>
    </p>
	<br/>

	<p>
    <label for="<?php echo $this->get_field_id('cw-category-title'); ?>">Category Count Title:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('cw-category-title'); ?>" name="<?php echo $this->get_field_name('cw-category-title'); ?>" type="text" value="<?php echo $instance['cw-category-title']; ?>" />
    </p>
	<p>
    <input class="checkbox" type="checkbox" <?php checked( $instance[ 'display_category_count' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'display_category_count' ); ?>" name="<?php echo $this->get_field_name( 'display_category_count' ); ?>" /> 
    <label for="<?php echo $this->get_field_id( 'display_category_count' ); ?>">Hide category count</label>
    </p>
	<br/>

    <p>
    <label for="<?php echo $this->get_field_id('cw-comment-title'); ?>">Comment Count Title:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('cw-comment-title'); ?>" name="<?php echo $this->get_field_name('cw-comment-title'); ?>" type="text" value="<?php echo $instance['cw-comment-title']; ?>" />
    </p>
	<p>
    <input class="checkbox" type="checkbox" <?php checked( $instance[ 'display_comment_count' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'display_comment_count' ); ?>" name="<?php echo $this->get_field_name( 'display_comment_count' ); ?>" /> 
    <label for="<?php echo $this->get_field_id( 'display_comment_count' ); ?>">Hide comment count</label>
    </p>
	<br/>

    <p>
    <label for="<?php echo $this->get_field_id('cw-user-title'); ?>">Member Count Title:</label>
    <input class="widefat" id="<?php echo $this->get_field_id('cw-user-title'); ?>" name="<?php echo $this->get_field_name('cw-user-title'); ?>" type="text" value="<?php echo $instance['cw-user-title']; ?>" />
    </p>
	<p>
    <input class="checkbox" type="checkbox" <?php checked( $instance[ 'display_user_count' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'display_user_count' ); ?>" name="<?php echo $this->get_field_name( 'display_user_count' ); ?>" /> 
    <label for="<?php echo $this->get_field_id( 'display_user_count' ); ?>">Hide member count</label>
    </p>		
	<?php
	}
 
    public function update( $new_instance, $old_instance ) {  
    $instance = $old_instance;

    $instance[ 'display_post_count' ] = $new_instance[ 'display_post_count' ];
	$instance[ 'display_category_count' ] = $new_instance[ 'display_category_count' ];
	$instance[ 'display_comment_count' ] = $new_instance[ 'display_comment_count' ];
	$instance[ 'display_user_count' ] = $new_instance[ 'display_user_count' ];
	
	$instance['cw-post-title'] = strip_tags($new_instance['cw-post-title']);
    $instance['cw-category-title'] = strip_tags($new_instance['cw-category-title']);
    $instance['cw-comment-title'] = strip_tags($new_instance['cw-comment-title']);
	$instance['cw-user-title'] = strip_tags($new_instance['cw-user-title']);

    return $instance;
    }
 
    public function widget( $args, $instance ) {
		
	extract( $args );
	
    $display_post_count = $instance[ 'display_post_count' ] ? 'true' : 'false';
    $display_category_count = $instance[ 'display_category_count' ] ? 'true' : 'false';
	$display_comment_count = $instance[ 'display_comment_count' ] ? 'true' : 'false';
	$display_user_count = $instance[ 'display_user_count' ] ? 'true' : 'false';
	
	$post_title = $instance[ 'cw-post-title' ];
	$category_title = $instance[ 'cw-category-title' ];
	$comment_title = $instance[ 'cw-comment-title' ];
	$member_title = $instance[ 'cw-user-title' ];
	
	echo $before_widget;
	?>	
	<aside class="counter-widget">
	<?php
	if ($display_post_count == 'false') {
    ?>
	<ul>
	<li class="post-count">
		<p><?php echo $post_title; ?></p>
        <p><span><?php $this->cw_post_count(); ?></span></p>
    </li>
    <?php
	}
	if ($display_category_count == 'false') {
    ?>
    <li class="category-count">
		<p><?php echo $category_title; ?></p>
        <p><span><?php $this->cw_category_count(); ?></span></p>
    </li>
	<?php
	}
	
	if ($display_comment_count == 'false') {
    ?>
    <li class="comment-count">
		<p><?php echo $comment_title; ?></p>
        <p><span><?php $this->cw_comment_count() ?></span></p>
    </li>
	<?php
	}
	if ($display_user_count == 'false') {
    ?>
    <li class="user-count">
		<p><?php echo $member_title; ?></p>
        <p><span><?php $this->cw_users_count() ?></span></p>
    </li>
	</ul>	
    <?php
	}
	?>
	</aside>
	<?php
	echo $after_widget;	
	} 
} ?>