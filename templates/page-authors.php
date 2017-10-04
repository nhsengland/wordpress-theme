<?php /* Template name: Authors */ ?>

<?php get_header(); ?>

<div class="row">

    <article class="blog-author-archive all-authors">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
            <header>
                <h1><?php the_title(); ?></h1>
            </header>
            <div class="rich-text introduction">
                <?php the_content(); ?>
            </div>

        <?php endwhile; ?>
    <?php endif;?>

    <?php
    //This is to list blog authors, who are all guest-authors
    $args = [
        'post_type' => 'guest-author',
        'posts_per_page' => -1,
        'orderby' => 'meta_value',
        'order' => 'asc',
        'meta_key' => 'cap-last_name',
    ];
    $authors = new WP_Query($args);

    if ($authors->have_posts()) :
        while ($authors->have_posts()) : $authors->the_post();

        global $coauthors_plus;
        $author_object = $coauthors_plus->get_coauthor_by('id', $post->ID);
        $author_term = get_term_by('name', $author_object->user_nicename, 'author');
        if ($author_term->count > 0) {
            $link = get_author_posts_url($author_object->ID, $author_object->user_nicename); ?>

            <div class="author-meta" style="">
                <div class="avatar-photo">
                    <a href="<?php echo esc_url($link) ?>">
                        <?php
                        if (coauthors_get_avatar($author_object, 140) !== false) {
                            echo coauthors_get_avatar($author_object, 140);
                        } else {
                            echo '<img alt="" src="' .  get_avatar_url($author_object, ['default' => 'mystery', 'size' => '140', 'scheme' => 'https']) . '" srcset="' . get_avatar_url($author_object, ['default' => 'mystery', 'size' => '280', 'scheme' => 'https']) . ' 2x" class="avatar avatar-140 photo avatar-default" height="140" width="140">';
                        } ?>
                    </a>
                </div>
                <div class="biography rich-text">
                    <a href="<?php echo esc_url($link) ?>">
                        <h2><?php the_title(); ?></h2>
                    </a>
                    <p>
                        <?php echo apply_filters('the_content', $author_object->description); ?>
                    </p>
                </div>
            </div>

            <?php
        }
    endwhile;
endif;
?>
    </section>
</div>
<?php get_footer(); ?>
