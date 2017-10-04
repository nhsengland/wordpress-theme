<?php get_header(); ?>

<div class="row">

    <?php
    if (get_current_blog_id() !== 1) {
        $category_id = get_cat_ID('News');
        $category_link = get_category_link($category_id);
        $blog_details = get_blog_details();
        $site_name = $blog_details->blogname;
        $site_link = $blog_details->siteurl;
        echo NHSEngland\SubNav::create([
            'top' => [
                'label' => $site_name,
                'link' => $site_link
            ],
            'parent' => [
                'label' => 'News',
                'link' => $category_link
            ]
        ], 'desktop-only');
    } else {
        get_template_part('partials/news-filter');
    } ?>

    <div class="single-post-article group" id="main-content">

        <header>
            <h1>News</h1>
        </header>

        <?php if (have_posts()) : ?>

            <?php while (have_posts()) : the_post(); ?>

            <article class="post group">
                <header>
                    <h2><?php the_title(); ?></h2>
                    <?php get_template_part('partials/entry-meta') ?>
                </header>

                <div class="rich-text">
                    <?php the_content(); ?>
                </div>

                <?php if (get_terms()): ?>
                    <div class="entry-meta entry-meta--topics">
                        <h4>Topics</h4>
                        <ul class="topics"><?php the_terms(0, 'category', '<li>', '</li><li>', '</li>') ?></ul>
                    </div>
                <?php endif; ?>
                <?php if (function_exists('can_be_rated')) {
        get_template_part('inc-ratings');
    } ?>
            </article>

            <div class="comment-area">
                <?php comments_template('', true); ?>
            </div>

        <?php endwhile; ?>

    <?php endif; ?>

</div>

<?php
if (get_current_blog_id() !== 1) {
        echo NHSEngland\SubNav::create([
        'top' => [
            'label' => $site_name,
            'link' => $site_link
        ],
        'parent' => [
            'label' => 'News',
            'link' => $category_link
        ]
    ], 'mobile-only');
    } ?>

</div>

<?php get_footer(); ?>
