<?php /* Template name: Blog Landing */ ?>

<?php get_header(); ?>

<section class="blog-landing">

    <?php include locate_template('components/component-page-header.php'); ?>

    <?php
    //get most recent sticky blog post, otherwise just most recent post
    $args = [
        'numberposts' => 1,
        'post_type' => 'blog',
        'meta_key' => '_sticky_blog_post',
        'meta_value' => 1,
        'post_status' => 'publish'
    ];
    $featured_post_arr = wp_get_recent_posts($args, OBJECT);
    if (!$featured_post_arr) {
        $args = [
            'numberposts' => 1,
            'post_type' => 'blog',
            'post_status' => 'publish'
        ];
        $featured_post_arr = wp_get_recent_posts($args, OBJECT);
    }
    global $post;
    $post = $featured_post_arr[0];
    setup_postdata($post);
    ?>

    <div class="row">

        <article class="sticky">
            <div class="sticky--post-thumbnail"><a href="<?php the_permalink() ?>"><?php the_post_thumbnail(); ?></a></div>


            <div class="sticky--post-content">

                <header>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php get_template_part('partials/entry-meta') ?>
                </header>

                <div class="rich-text">
                    <?php the_excerpt(); ?>
                </div>
                <a href="<?php the_permalink() ?>" class="read-more">Read more</a>
            </div>

        </article>

    </div>

    <div class="row">

        <?php

        // exclude the featured post from this query

        $recent_args = [
            'post_type' => 'blog',
            'posts_per_page' => 6,
            'post__not_in' => [$post->ID]
        ];
        $recent = new WP_Query($recent_args);
        ?>

        <?php if ($recent->have_posts()) : ?>
            <div class="blog-recent-posts">
                <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                <article class="post group js-equalheight">
                    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                    <?php get_template_part('partials/entry-meta') ?>
                    <div class="rich-text">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink() ?>" class="read-more">Read more</a>
                </article>
            <?php endwhile; ?>

            <div class="button-container">
                <a href="<?php echo get_post_type_archive_link('blog') ?>" class="button nhs-dark-pink">View and search all blogs</a>
            </div>
        </div>
    <?php endif; ?>

</div>


<div class="row">

    <header>
        <h2>Popular topics</h2>
    </header>

    <?php wp_reset_postdata(); ?>
    <div class="blog-categories">
        <ul>
            <?php
            $feature_cats = get_field('blog_categories');
            if ($feature_cats !== null) :?>
            <?php
            $i = 0;
            foreach ($feature_cats as $term_id) {
                if ($i>=8) {
                    break;
                }
                $i++;
                $termLink = h()->getNewsArchiveLink($term_id, '/blog/');
                $termName = get_term($term_id, 'category')->name; ?>
                <a href="<?php echo $termLink ?>"><li class="js-equalheight"><span><?php echo $termName ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="arrow"><path fill="#ffffff" d="M14.9 8.4c.1-.2.1-.5 0-.7 0 0 0-.1-.1-.1 0-.1 0-.1-.1-.2l-5-6c-.4-.4-1-.5-1.4-.1-.4.4-.5 1-.1 1.4L11.9 7H2c-.6 0-1 .4-1 1s.4 1 1 1h9.9l-3.6 4.4c-.4.4-.3 1.1.1 1.4.1.1.4.2.6.2.3 0 .6-.1.8-.4l5-6s.1-.1.1-.2z"></path></svg></span></li></a>
                <?php
            }
        endif;
        ?>
        <!-- view all topics -->
        <a href="<?php echo esc_url(get_post_type_archive_link('blog')) ?>"><li class="view-all js-equalheight"><span>View all topics<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="arrow"><path fill="#ffffff" d="M14.9 8.4c.1-.2.1-.5 0-.7 0 0 0-.1-.1-.1 0-.1 0-.1-.1-.2l-5-6c-.4-.4-1-.5-1.4-.1-.4.4-.5 1-.1 1.4L11.9 7H2c-.6 0-1 .4-1 1s.4 1 1 1h9.9l-3.6 4.4c-.4.4-.3 1.1.1 1.4.1.1.4.2.6.2.3 0 .6-.1.8-.4l5-6s.1-.1.1-.2z"></path></svg></span></li></a>

    </ul>
</div>


    </div>

    <?php if (have_rows('authors')): ?>

        <div class="row">
            <header>
                <h2>Authors</h2>
            </header>

            <div class="blog-authors">

                <ul class="author-bio">

                    <?php while (have_rows('authors')) : the_row();

                    $authorName = get_sub_field('author_name');
                    $authorLink = get_sub_field('author_archive_link');
                    $authorPic  = get_sub_field('author_picture')['url'];

                    if ($authorPic === null) {
                        $authorPic = h()->getAssetPath('img/author.png');
                    } ?>

                    <li class="js-equalheight"><a href="<?php echo $authorLink ?>"><div class="author-photo"><img src="<?php echo $authorPic ?>" alt="" /></div><h4><?php echo $authorName ?></h4></a></article></li>

                <?php endwhile; ?>

            </ul>

            <div class="button-container">
                <a href="<?php the_field('all_authors_page') ?>" class="button nhs-dark-pink">View all authors</a>
            </div>

        </div>

    </div>

<?php endif; ?>

</section>

<?php get_footer(); ?>
