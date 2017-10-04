<?php get_header(); ?>

<div class="row">
    <?php

    if (get_current_blog_id() !== 1) {
        $category_id = get_cat_ID('News');
        $category_link = get_category_link($category_id);
        echo NHSEngland\SubNav::generate(get_the_ID(), 'desktop-only', [
            'parent' => [
                'label' => 'News',
                'link'  => $category_link
            ]
        ]);
    } else {
        ?>
        <aside id="subnav" class="widget-news-sidebar group desktop-only" role="complementary">
            <?php get_template_part('widget-news-sidebar'); ?>
        </aside>
        <?php
    } ?>
    <div class="archive-posts group" id="main-content">

        <?php if (get_current_blog_id()==1 && is_category('news')) {
        get_template_part('news-all');
    } elseif (get_current_blog_id()==1 && is_date()) {
        get_template_part('news-all-date-archive');
    } else {
        if (is_tag()) {
            ?>

                <header>
                    <h1><?php echo strtolower(single_tag_title("", false)); ?></h1>
                    <?php echo category_description(); ?>
                </header>

                <?php
        } elseif (is_category()) {
            ?>

                <header>
                    <h1><?php echo single_cat_title("", false); ?></h1>
                    <div class="rich-text">
                        <?php echo category_description(); ?>

                        <?php echo get_field('description', $wp_query->get_queried_object()); ?>
                    </div>
                </header>

                <?php
        } elseif (is_author()) {
            ?>

                <?php $user_info = get_userdata($post->post_author); ?>

                <header>
                    <h1>News</h1>
                    <h2>Posts by: <?php echo $user_info->display_name; ?></h2>
                </header>

                <?php
        } elseif (is_archive()) {
            ?>

                <?php
                $month = get_query_var('monthnum');
            $year = get_query_var('year');

            if ($month == 0) {
                $month = "";
            } else {
                $month = date('F', mktime(0, 0, 0, $month));
            } ?>

                <header>
                    <h1>News</h1>
                    <h2>Archive for: <?php echo $month . " " . $year; ?></h2>
                </header>

                <?php
        } else {
            ?>

                <header>
                    <h1>News</h1>
                </header>

                <?php
        } ?>

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>

                <article class="post group">
                    <header>
                        <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                        <?php get_template_part('partials/entry-meta') ?>
                    </header>

                    <div class="rich-text">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="read-more">Read more</a>
                </article>

            <?php endwhile; ?>
            <?php endif;
    } ?>

        <?php
        h()->pagination();
        ?>
    </div>

    <aside id="subnav" class="widget-news-sidebar group mobile-only" role="complementary">
        <?php get_template_part('widget-news-sidebar'); ?>
    </aside>

</div>

<?php get_footer(); ?>
