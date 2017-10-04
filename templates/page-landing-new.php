<?php /* Template name: Landing Page New */ ?>

<?php get_header(); ?>
<?php
$backgroundScroll = get_field('background_scroll');
switch ($backgroundScroll) {
    case 'Parallax':
    $speed = '0.2';
    break;
    case 'Fixed':
    $speed = '0';
    break;
    case 'None':
    default:
    $speed = '1';
    break;
}

$topics = wp_list_pages([
    'depth' => 1,
    'title_li' => '',
    'echo' => false,
    'sort_column' => 'post_title'
]);
?>
<?php if (post_password_required()) : ?>
    <div class="row">
        <div id="main-content" style="margin-left:30px">
            <?php echo get_the_password_form(); ?>
        </div>
    </div>
<?php else : ?>

    <header class="section-header" id="main-content">
        <div class="parallax-window" data-parallax="scroll" data-speed="<?php echo $speed ?>" data-image-src="<?php echo get_field('background_image'); ?>">
            <div class="section-caption row">
                <div class="<?php echo get_field('white_background_behind_text') === true ? 'section-caption-block' : '' ?>">
                    <b><?php the_field('section') ?></b>
                    <h1><?php echo get_field('use_page_title') ? the_title() : get_field('title') ?></h1>
                    <div class="excerpt rich-text">
                        <?php the_field('content') ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="topic-landing">

        <div class="row">

            <div class="topic-sub-sections">

                <?php while (have_rows('links')) : the_row(); ?>
                <article class="topic">
                    <div class="topic-summary">
                        <h3><a href="<?php the_sub_field('url') ?>"><?php the_sub_field('title') ?></a></h3>
                        <div class="excerpt rich-text">
                            <?php the_sub_field('body') ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>

        </div>

        <div class="topic-atoz">
            <header>
                <h2>In this section</h2>
            </header>

            <aside role="complementary">
                <?php get_template_part('partials/page-landing-topics'); ?>
            </aside>

        </div>
    </div>

    <?php
    if (have_rows('blocks')):
        while (have_rows('blocks')) : the_row(); ?>

        <?php include locate_template('content-blocks/video.php'); ?>

        <?php include locate_template('content-blocks/latest-posts.php'); ?>

        <?php include locate_template('content-blocks/document.php'); ?>

        <?php // end content block loop
    endwhile;
endif;
?>



</section>
<?php endif; ?>
<?php get_footer(); ?>
