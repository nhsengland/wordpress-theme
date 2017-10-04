<?php get_header(); ?>

<section class="blog-archive" id="main-content">

    <div class="row">

        <header>
            <h1>Blogs</h1>
        </header>

        <div class="blog-posts-archive group">

            <?php get_template_part('partials/blogs-filter'); ?>

            <div class="filtered-list">
                <?php
                global $wp_query;
                $blog_filter_query = $wp_query;
                ?>
                <?php if ($blog_filter_query->have_posts()) : ?>

                    <div class="filtered-list-summary">
                        <?php
                        $count = $GLOBALS['wp_query']->found_posts;
                        echo sprintf(_n('<span class="count">%s</span> <strong>post</strong>', '<span class="count">%s</span> <strong>posts</strong>', $count), number_format_i18n($count));
                        ?>
                    </div>

                    <?php while ($blog_filter_query->have_posts()) : $blog_filter_query->the_post(); ?>
                    <article class="post group">
                        <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                        <?php get_template_part('partials/entry-meta') ?>
                    </article>
                <?php endwhile; ?>

            <?php else : ?>
                <div class="filtered-list-summary">
                    <span class="count">0</span> <strong>posts</strong>
                </div>

                <p class="filtered-list-no-results">
                    No results found for your search query.
                </p>

            <?php endif; ?>
            <?php
            global $wp_query;
            $x = $wp_query;
            $wp_query = $blog_filter_query;
            h()->pagination();
            $wp_query = $x;
            ?>

        </div>

    </div>

</div>

</section>

<?php get_footer(); ?>
