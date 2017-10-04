<?php get_header(); ?>

<div class="row">
    <?php if (get_current_blog_id() !== 1 && !is_tag() && !is_category() && !is_author() && !is_archive()) {
    $category_id = get_cat_ID('News');
    $category_link = get_category_link($category_id);
    global $wp_query;
    $id = $wp_query->queried_object->ID;
    echo NHSEngland\SubNav::generate($id, 'desktop-only', [
            'parent' => [
                'label' => 'News',
                'link'  => get_the_permalink($id)
            ]
        ]);
} ?>
    <div class="blog-posts group">

        <?php if (is_tag()) {
    ?>

            <header id="main-content">
                <h1>News</h1>
                <h2>Posts tagged as <?php echo strtolower(single_tag_title("", false)); ?></h2>
            </header>

            <?php
} elseif (is_category()) {
        ?>

            <header>
                <h1>News</h1>
                <h2>Topic: <?php echo single_cat_title("", false); ?></h2>
            </header>

            <?php
    } elseif (is_author()) {
        ?>
            <?php
            $user_info = get_userdata($post->post_author); ?>

            <header>
                <h1>News</h1>
                <h2>Posts by: <?php echo $user_info->display_name; ?></h2>
            </header>

            <?php
    } elseif (is_archive()) {
        ?>

            <?php echo category_description(); ?>

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
                <h2>Archive for: <?php echo $month . " " . $year; ?></h2></header>

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
                        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                    </header>
                    <time datetime="<?php the_time('Y-m-d'); ?>" pubdate  class="post-date"><?php the_time('j F Y - H:i'); ?></time>

                    <div class="rich-text">
                        <?php the_excerpt(); ?>
                    </div>
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="read-more">Read more</a>
                </article>

            <?php endwhile; ?>
        <?php endif; ?>

        <?php
        h()->pagination();
        ?>
    </div>

</div>

<?php get_footer(); ?>
