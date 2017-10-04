<?php get_header(); ?>

<div class="row">

    <section class="blog-author-archive">

        <header>
            <?php
            //Unless the query is reset, the theme displays blog owner's details
            wp_reset_query(); ?>
            <h1>Posts by <?php the_author_meta('display_name'); ?> </h1>
            <div class="author-meta">
                <div class="avatar-photo">
                    <?php h()->nhs_avatar() ?>
                </div>
                <div class="biography rich-text">
                    <?php $content = get_the_author_meta('description');
                    echo apply_filters('the_content', $content); ?>
                </div>
            </div>
        </header>

        <?php if (have_posts()) : ?>
            <div class="author-posts">
                <?php while (have_posts()) : the_post(); ?>
                <article class="post js-equalheight">
                    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                    <?php get_template_part('partials/entry-meta') ?>
                </article>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <?php
    h()->pagination();
    ?>

</section>

</div>

<?php get_footer(); ?>
