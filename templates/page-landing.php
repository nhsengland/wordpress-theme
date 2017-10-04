<?php /* Template name: Landing Page */ ?>
<?php get_header(); ?>
<?php if (post_password_required()) : ?>
    <div class="row">
        <div id="main-content" style="margin-left:30px">
            <?php echo get_the_password_form(); ?>
        </div>
    </div>
<?php else : ?>
    <div class="row" id="main-content">
        <div class="page-sections">
            <header class="section-header group">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                    <div class="featured-image">
                        <?php the_post_thumbnail(); ?>
                    </div>
                    <h1><?php the_title(); ?></h1>
                    <div class="excerpt rich-text"><?php the_content(); ?></div>
                <?php endwhile; ?>
            <?php endif; ?>
        </header>
        <div class="subpage-grid group top-row">
            <?php if (have_rows('top_row')) : while (have_rows('top_row')) : the_row(); ?>
            <?php
            $title = get_sub_field('title');
            $excerpt = get_sub_field('excerpt');
            $link = get_sub_field('url');
            $image = get_sub_field('image');
            ?>
            <article class="subpage">
                <?php if (get_sub_field('image')) : ?>
                    <div class="featured-image">
                        <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>">
                    </div>
                <?php endif; ?>
                <h2><a href="<?php echo $link; ?>"><?php echo $title; ?></a></h2>
                <div class="rich-text post-excerpt">
                    <?php echo $excerpt; ?>
                </div>
            </article>
            <?php endwhile; endif; ?>
        </div>
        <div class="subpage-grid group bottom-row">
            <?php if (have_rows('bottom_row')) : while (have_rows('bottom_row')) : the_row(); ?>
            <?php
            $title = get_sub_field('title');
            $excerpt = get_sub_field('excerpt');
            $link = get_sub_field('url');
            $image = get_sub_field('image');
            ?>
            <article class="subpage">
                <?php if (get_sub_field('image')) : ?>
                    <div class="featured-image">
                        <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>">
                    </div>
                <?php endif; ?>
                <h2><a href="<?php echo $link; ?>"><?php echo $title; ?></a></h2>
                <div class="rich-text post-excerpt">
                    <?php echo $excerpt; ?>
                </div>
            </article>
            <?php endwhile; endif; ?>
        </div>

        <?php if (have_rows('topics')) : ?>
            <section class="topic-landing row">

                <div class="topic-atoz">
                    <header>
                        <h2>Also in the section</h2>
                    </header>

                    <?php get_template_part('partials/page-landing-topics'); ?>

                </div>

            </section>
        <?php endif; ?>
    </div>
    <aside class="landingpage-sidebar group" role="complementary">

        <?php
        $category = get_field('news_items');
        $sidebar_query = new WP_Query([
            'cat' => $category,
            'posts_per_page' => 5
        ]);
        ?>
        <?php if ($sidebar_query->have_posts()) : ?>
            <h4>News and blogs</h4>
            <?php while ($sidebar_query->have_posts()) : $sidebar_query->the_post(); ?>
            <article class="post group">
                <h5><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h5>
                <time datetime="<?php the_time('Y-m-d'); ?>" pubdate class="post-date"><?php the_time('j F Y'); ?></time>
            </article>
        <?php endwhile; ?>
    <?php endif; ?>
    <?php if (is_active_sidebar('landing_sidebar')) : ?>
        <div class="news-widgets">
            <?php if (dynamic_sidebar('landing_sidebar')) {
        } ?>
        </div>
    <?php endif; ?>

</aside>
    </div>
<?php endif; ?>
<?php get_footer(); ?>
