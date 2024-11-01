<?php

class tw_widget extends WP_Widget {

    function __construct() {
        parent::__construct(
                // Base ID of your widget
                'tw_widget',
                // Widget name will appear in DASHBOARD / Appearence / Widgets
                __('TW Most Views', TEXT_DOMAIN),
                // Widget description
                array('description' => __('Widget that will show the posts most viewed', TEXT_DOMAIN),)
        );
    }

    // This is where the action happens
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];

        if (!empty($title)):
            echo $args['before_title'] . $title . $args['after_title'];
        endif;

        if (!isset($instance['tw_thumbnail'])):
            $instance['tw_thumbnail'] = 'thumbnail';
        endif;

        $tw_most_views_widget = array(
            'post_type' => $instance['tw_post_type'],
            'posts_per_page' => $instance['tw_showposts'],
            'order' => $instance['tw_order'],
            'image_size' => $instance['tw_thumbnail']
        );

        /* Set the default arguments. */
        $defaults = array(
            'post_type' => 'post',
            'posts_per_page' => 5,
            'no_found_rows' => true,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
            'orderby' => 'meta_value',
            'meta_key' => 'tw_counter',
            'order' => 'DESC',
            'image_size' => 'thumbnail'
        );

        /* Merge the input arguments and the defaults. */
        $tw_args = wp_parse_args($tw_most_views_widget, $defaults);
        $myposts = get_posts($tw_args);
        ?>

        <div class="tw_most_views">
            <ul>
                <?php
                if ($myposts) : foreach ($myposts as $post): setup_postdata($post);
                        $tw_post_counter = get_post_meta($post->ID, 'tw_counter', true);
                        ?>
                        <?php if (isset($instance['tw_check_thumb']) && $instance['tw_check_thumb'] == "YES"): ?>
                            <li>
                                <a href="<?php echo get_the_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>">
                                    <?php echo get_the_post_thumbnail($post->ID, $tw_args['image_size'], array('class' => 'tw_img_mostviews')); ?>
                                </a>
                                <div class = "tw_post_content">
                                    <div class="tw_post_title">
                                        <a href="<?php echo get_the_permalink($post->ID); ?>" title="<?php echo $post->post_title; ?>"><?php echo $post->post_title . "<span>({$tw_post_counter})</span>"; ?></a>
                                    </div>
                                    <ul class="tw_info">
                                        <li class="tw_category"><?php the_category(' | ', '', $post->ID); ?></li>
                                        <li class="tw_date"><?php echo get_the_date(); ?></li>
                                    </ul>
                                </div>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo get_the_permalink($post->ID); ?>">
                                    <?php echo $post->post_title; ?>
                                    <?php echo "<span>({$tw_post_counter})</span>"; ?>
                                </a>
                            </li>
                        <?php
                        endif;
                    endforeach;
                else: echo _e('No published post!', TEXT_DOMAIN);
                endif;
                wp_reset_postdata();
                ?>
            </ul>
        </div> <!-- tw_most_views -->

        <?php
        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance) {
        if (isset($instance['title']) && !empty($instance['title'])):
            $title = $instance['title'];
        else:
            $title = "";
        endif;
        if (isset($instance['tw_post_type']) && !empty($instance['tw_post_type'])):
            $tw_post_type = $instance['tw_post_type'];
        endif;
        if (isset($instance['tw_showposts']) && !empty($instance['tw_showposts'])):
            $tw_showposts = $instance['tw_showposts'];
        else:
            $tw_showposts = 5;
        endif;
        if (isset($instance['tw_order']) && !empty($instance['tw_order'])):
            $tw_order = $instance['tw_order'];
        endif;
        if (isset($instance['tw_check_thumb']) && $instance['tw_check_thumb'] == "YES"):
            $tw_check_thumb = $instance['tw_check_thumb'];
        else:
            $tw_check_thumb = NULL;
        endif;
        if (isset($instance['tw_thumbnail']) && !empty($instance['tw_thumbnail'])):
            $tw_thumbnail = $instance['tw_thumbnail'];
        endif;
        // Widget admin form
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', TEXT_DOMAIN); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />

            <label for="<?php echo $this->get_field_id('tw_post_type'); ?>"><?php _e('Post Type:', TEXT_DOMAIN); ?></label>
            <select class="widefat" name="<?php echo $this->get_field_name('tw_post_type'); ?>" id="<?php echo $this->get_field_id('tw_post_type'); ?>">
                <?php
                $args = array('public' => true);
                $output = 'names';
                $post_types = get_post_types($args, $output);
                $tw_post_type = esc_attr($instance['tw_post_type']);
                foreach ($post_types as $key => $post_type) {
                    echo '<option value="' . $post_type . '"' . selected($tw_post_type, $key) . ' >' . $post_type . '</option>';
                }
                ?>
            </select>

            <label for="<?php echo $this->get_field_id('tw_showposts'); ?>"><?php _e('Number Posts:', TEXT_DOMAIN); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('tw_showposts'); ?>" name="<?php echo $this->get_field_name('tw_showposts'); ?>" type="text" value="<?php echo esc_attr($tw_showposts); ?>" />

            <label for="<?php echo $this->get_field_id('tw_order'); ?>"><?php _e('Order:', TEXT_DOMAIN); ?></label>
            <select name="<?php echo $this->get_field_name('tw_order'); ?>" class="widefat">
                <?php $tw_order = esc_attr($instance['tw_order']); ?>
                <option value="ASC" <?php selected($tw_order, 'ASC'); ?>>ASC</option>
                <option value="DESC" <?php selected($tw_order, 'DESC'); ?>>DESC</option>
            </select>

            <?php $tw_check_thumb = esc_attr($instance['tw_check_thumb']); ?>
            <span class="description"><?php _e('Show thumbnail?', TEXT_DOMAIN); ?></span>
            <input type="checkbox" class="checkbox" name="<?php echo $this->get_field_name('tw_check_thumb'); ?>" <?php checked($tw_check_thumb, 'YES'); ?> value="YES"/><br>

            <?php if (isset($tw_check_thumb) && $tw_check_thumb == "YES"): ?>
                <label for = "<?php echo $this->get_field_id('tw_thumbnail'); ?>"><?php _e('Thumbnail Size:', TEXT_DOMAIN); ?></label>
                <select class="widefat" name="<?php echo $this->get_field_name('tw_thumbnail'); ?>">
                    <?php
                    $additional_image_sizes = get_intermediate_image_sizes();
                    $tw_thumbnail = esc_attr($instance['tw_thumbnail']);
                    foreach ($additional_image_sizes as $size_attrs) {
                        echo '<option value="' . $size_attrs . '"' . selected($tw_thumbnail, $size_attrs) . ' >' . $size_attrs . '</option>';
                    }
                    ?>
                </select>
                <?php
            endif;
            ?>

        </p>
        <?php
    }

}

// Register and load the widget
function tw_load_widget() {
    register_widget('tw_widget');
}

add_action('widgets_init', 'tw_load_widget');
