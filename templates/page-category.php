<?php /* Template name: Category landing page */ ?>

<?php get_header(); ?>

<?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

    <div class="row">

        <?php echo NHSEngland\SubNav::generate(get_the_ID(), 'desktop-only'); ?>

        <div class="category-page-content group" id="main-content">

            <?php get_template_part('partials/breadcrumbs'); ?>

            <header>
                <h1><?php the_title(); ?></h1>
            </header>

            <article class="rich-text">
                <?php the_content(); ?>
            </article>

        <?php endwhile; ?>

    <?php endif; ?>

    <?php
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $category = get_field("category_id");
    $my_query = new WP_Query([
        'cat' => $category,
        'paged' => $paged,
    ]);
    ?>

    <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
    <article class="post group">
        <header>
            <h2>
                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
            </h2>
        </header>
        <time datetime="<?php the_time('Y-m-d'); ?>" pubdate  class="post-date"><?php the_time('j F Y - H:i'); ?></time>
        <div class="rich-text">
            <?php the_excerpt(); ?>
        </div>
        <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>" class="read-more">Read more</a>
    </article>
<?php endwhile; ?>

<?php
global $wp_query;
$x = $wp_query;
$wp_query = $my_query;
h()->pagination();
$wp_query = $x;
?>

        </div>

        <?php echo NHSEngland\SubNav::generate(get_the_ID(), 'mobile-only'); ?>

    </div>

    <?php get_footer(); ?>
