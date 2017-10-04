<?php
$month = get_query_var('monthnum');
$year = get_query_var('year');

if ($month == 0) {
    $month = "";
} else {
    $month = date('F', mktime(0, 0, 0, $month));
}
?>

<header>
    <h1>News</h1>
    <h2>Archive for: <?php echo $month . " " . $year; ?></h2>
</header>

<?php

$ids = dxw_news_get_public_blog_ids();

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

network_query_posts([
    'posts_per_page' => 10,
    'paged' => $paged,
    'blog_id__in' => $ids,
    'meta_key' => '_dxw_news_aggregator_setting',
    'meta_value' => 1,
    'date_query' => [
        'year' => get_query_var('year'),
        'month' => get_query_var('monthnum'),
    ],
]);

global $network_query, $network_post;

if (function_exists('network_query_posts')) {
    if (network_have_posts()) {
        while (network_have_posts()) {
            network_the_post();
            $post = $GLOBALS['network_post']; ?>
            <article class="post group">
                <header>
                    <h2><a href="<?php echo network_get_permalink(); ?>" rel="bookmark" title="<?php network_the_title_attribute(); ?>"><?php network_the_title(); ?></a></h2>
                </header>
                <time datetime="<?php echo network_get_post_time('Y-m-d'); ?>"><?php echo network_get_post_time('j F Y - H:i'); ?></time>
                <?php if ($post->BLOG_ID!=1) {
                ?>
                    <a href="<?php echo esc_attr(dxw_news_blog_url($post->BLOG_ID)) ?>/category/news" class="read-more">See more <?php echo esc_attr(dxw_news_blog_title($post->BLOG_ID)) ?> news</a>
                    <?php
            } ?>
            </article>
            <?php
        }
    }
}
