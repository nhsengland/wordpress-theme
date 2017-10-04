<?php
//
// Content Block ### Latest posts
//
if (get_row_layout() === 'latest_posts') {
    $category = get_sub_field('select_category');
    $number_of_posts = get_sub_field('number_of_posts');
    $sticky = get_option('sticky_posts');
    $args = [
        'post_type' => 'post',
        'numberposts' => $number_of_posts
    ];
    if (!empty($category)) {
        $args['category'] = $category;
    }
    $stickyPosts = get_posts(
        array_merge($args, [
            'include' => implode(',', $sticky)
        ])
    );
    $normalPosts = get_posts(
        array_merge($args, [
            'post__not_in' => $sticky
        ])
    );

    $posts = array_slice(
        array_merge($stickyPosts, $normalPosts),
        0,
        $number_of_posts
    );

    // Background
    $background = get_sub_field('background');
    $background_colour = get_sub_field('background_colour');
    $container_style = 'margin: 30px 0; padding: 30px 0;';
    if ($background === true && preg_match('/^#[0-9a-f]{6}$/', $background_colour)) {
        $container_style = 'margin: 30px 0; padding: 30px 0; background-color: '.$background_colour.';';
    } ?>
    <div style="<?php echo esc_attr($container_style) ?>">
        <div class="row">
            <div class="topic-blog">
                <header>
                    <h2><?php the_sub_field('section_title') ?></h2>
                </header>
                <?php

                $post = $posts[0];
    setup_postdata($post); ?>
                <article class="topic-blog-sticky-post">

                    <header>
                        <h3><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
                        <time class="post-meta" datetime="<?php echo get_the_date('Y-m-d') ?>" pubdate="<?php echo get_the_date('Y-m-d') ?>"><img src="<?php bloginfo('template_directory'); ?>/../assets/img/date.svg" alt="date icon"><?php echo get_the_date('jS F Y') ?></time>
                        <p class="author"><a href="<?php the_author_link() ?>"><img src="<?php bloginfo('template_directory'); ?>/../assets/img/human.svg" alt="human"><?php the_author() ?></a></p>
                    </header>

                    <div class="excerpt rich-text">
                        <?php the_excerpt() ?>
                    </div>

                </article>

                <div class="topic-blog-recent-posts">

                    <ul>
                        <?php

                        for ($i=1; $i < $number_of_posts; $i++) {
                            $post = $posts[$i];
                            setup_postdata($post); ?>
                            <li class="group">
                                <a href="<?php the_permalink() ?>">
                                    <h4><?php the_title() ?></h4>
                                    <time class="post-meta" datetime="<?php echo get_the_date('Y-m-d') ?>" pubdate="<?php echo get_the_date('Y-m-d') ?>"><img src="<?php bloginfo('template_directory'); ?>/../assets/img/date.svg" alt="date icon"><?php echo get_the_date('jS F Y') ?></time>
                                    <p class="author"><img src="<?php bloginfo('template_directory'); ?>/../assets/img/human.svg" alt="human"><?php the_author() ?></p>
                                </a>
                            </li>
                            <?php
                            wp_reset_postdata();
                        } ?>
                    </ul>

                </div>
                <?php include locate_template('content-blocks/call-to-action.php') ?>
            </div>
        </div>
    </div>
    <?php
}
