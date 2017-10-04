<?php /* Template name: Page Flexible Components */ ?>

<?php get_header(); ?>

<?php if (post_password_required()) : ?>
    <div class="row">
        <div id="main-content" style="margin-left:30px">
            <?php echo get_the_password_form(); ?>
        </div>
    </div>
<?php else : ?>
    <section class="components" id="main-content">

        <?php include locate_template('components/component-page-header.php'); ?>

        <?php
        if (have_rows('components')):
            while (have_rows('components')) : the_row(); ?>
            <?php include locate_template('components/component-recent-posts.php'); ?>
            <?php include locate_template('components/component-section-header.php'); ?>
            <?php include locate_template('components/component-notify.php'); ?>
            <?php include locate_template('components/component-promos.php'); ?>
            <?php include locate_template('components/component-document.php'); ?>
            <?php include locate_template('components/component-video.php'); ?>

            <?php include locate_template('components/component-article.php'); ?>
            <?php include locate_template('components/component-topic-sub-sections.php'); ?>
            <?php include locate_template('components/component-priorities.php'); ?>
            <?php include locate_template('components/component-in-this-section.php'); ?>
            <?php include locate_template('components/component-atoz-index.php'); ?>
            <?php include locate_template('components/component-case-study.php'); ?>
            <?php include locate_template('components/component-recent-case-study.php'); ?>
            <?php include locate_template('components/component-hidden-text.php'); ?>
            <?php include locate_template('components/component-twitter-timeline.php'); ?>
            <?php include locate_template('components/component-cta.php'); ?>
            <?php // end content block loop
        endwhile;
    endif;
    ?>

</section>
<?php endif; ?>
<?php get_footer(); ?>
