<?php get_header(); ?>

<div class="row">

    <div class="single-post">

        <div class="single-post-article group" id="main-content">

            <header>
                <h1>Blog</h1>
            </header>

            <?php if (have_posts()) : ?>

                <?php while (have_posts()) : the_post(); ?>

                <article class="post group">
                    <header>
                        <h2><?php the_title(); ?></h2>
                        <?php get_template_part('partials/entry-meta') ?>
                    </header>

                    <?php if (get_the_post_thumbnail()): ?>
                        <div class="post-thumbnail"> <?php the_post_thumbnail(); ?> </div>
                    <?php endif; ?>

                    <div class="rich-text">
                        <?php the_content(); ?>
                    </div>


                    <?php
                    //Unless the query is reset, the theme displays blog owner's details
                    wp_reset_query(); ?>
                    <?php include locate_template('partials/blog-post-bio.php'); ?>

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

    <aside class="sidebar blog-sidebar group" role="complementary">

        <?php
        $current_post_id = get_the_ID();
        $args = [
            'post_type' => 'blog',
            'posts_per_page' => 5,
            'post__not_in' => [$current_post_id],
        ];
        $related = new WP_Query($args);
        ?>

        <?php if ($related->have_posts()) : ?>
            <div class="blog-sidebar--related-posts">
                <h2>Latest posts</h2>
                <ul class="related-posts">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                    <li> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php endif; ?>

</aside>

    </div>

</div>

<?php get_footer(); ?>
