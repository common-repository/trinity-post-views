<?php
add_filter('widget_text', 'do_shortcode');

function tw_most_views_shortcode($param = array()) {

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
    $args = wp_parse_args($param, $defaults);
    $myposts = get_posts($args);
    ?>

    <div class="tw_most_views">
        <ul>
            <?php
            if ($myposts) : foreach ($myposts as $post): setup_postdata($post);
                    $tw_post_counter = get_post_meta($post->ID, 'tw_counter', true);
                    ?>

                    <li>
                        <?php echo $post->post_title; ?>
                        <?php echo " - ({$tw_post_counter})"; ?>
                    </li>

                    <?php
                endforeach;
            else: echo _e('No published post!', TEXT_DOMAIN);
            endif;
            wp_reset_postdata();
            ?>
        </ul>
    </div> <!-- tw_most_views -->

    <?php
}

/* ADICIONANDO SHORTCODE PARA SIDEBAR */
add_shortcode('mostviews', 'tw_most_views_shortcode');
