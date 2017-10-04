<?php
$keyword = $GLOBALS['wp_query']->get('s');
if (!is_null($keyword) && $keyword !== '') {
    relevanssi_do_query($GLOBALS['wp_query']);
}
?>

<?php get_header(); ?>
<section class="news-archive">
    <div class="row">

        <?php if (get_field('show_news_homepage_banner', 'options')): ?>
            <div class="alert-container rich-text">
                <div class="wpc-message">
                    <?php echo apply_filters('the_content', get_field('news_homepage_banner_text', 'options')); ?>
                </div>
            </div>
        <?php endif; ?>

        <header>
            <h1>News</h1>
        </header>

        <?php
        $posts_to_exclude = [];

        if (!is_paged() && !isset($_GET['filter-keyword'])):

            //get the most recent sticky, or just the most recent if no stickies
            $sticky_args = [
                'post_type' => 'post',
                'post__in' => get_option('sticky_posts'),
                'posts_per_page' => 1,
                'ignore_sticky_posts' => 1
            ];
            $sticky = new WP_Query($sticky_args);
            $sticky_post_id = wp_list_pluck($sticky->posts, 'ID');
            ?>

            <div class="news-intro">

                <?php if ($sticky->have_posts()) : ?>
                    <?php
                    while ($sticky->have_posts()) : $sticky->the_post();
                    ?>
                    <article class="sticky">
                        <?php if (get_the_post_thumbnail()): ?>
                            <div class="sticky--post-thumbnail"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></div>
                        <?php endif; ?>

                        <div class="sticky--post-content">

                            <header>
                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <?php get_template_part('partials/entry-meta') ?>
                            </header>

                            <div class="content rich-text">
                                <?php the_excerpt(); ?>
                            </div>

                        </div>

                    </article>
                <?php endwhile; ?>
            <?php endif; ?>

        </div>

        <?php
    endif;

    wp_reset_query();

    //get everything else (filtered by pre_get_posts in app/NewsHomepage.php)

    global $wp_query;
    $main_filter_query = $wp_query;
    ?>

    <div class="all-other-posts">

        <?php get_template_part('partials/news-filter'); ?>

        <?php if ($main_filter_query->have_posts()) : ?>
            <div class="filtered-list">

                <div class="filtered-list-summary">
                    <?php
                    $count = $main_filter_query->found_posts;
                    echo sprintf(_n('<span class="count">%s</span> <strong>item</strong>', '<span class="count">%s</span> <strong>items</strong>', $count), number_format_i18n($count));
                    ?>
                </div>
                <?php while ($main_filter_query->have_posts()) : $main_filter_query->the_post(); ?>

                <article class="post group">
                    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                    <?php get_template_part('partials/entry-meta') ?>
                    <div class="rich-text">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink() ?>" class="read-more">Read more</a>
                </article>
            <?php endwhile; ?>
            <?php
            global $wp_query;
            $x = $wp_query;
            $wp_query = $main_filter_query;
            h()->pagination();
            $wp_query = $x;
            ?>
        </div>

    <?php else : ?>
        <div class="filtered-list-summary">
            <span class="count">0</span> <strong>items</strong>
        </div>

        <p class="filtered-list-no-results">
            No results found for your search query.
        </p>

    <?php endif; ?>

</div>



    </div>
</section>

<?php get_footer(); ?>
