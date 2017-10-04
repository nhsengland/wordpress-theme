<?php if (post_password_required()) : ?>

    <p>This post is password protected.</p>

    <?php
    /* Stop the rest of comments.php from being processed,
    * but don't kill the script entirely -- we still have
    * to fully load the template.
    */
    return;
endif;
?>

<?php
comment_form();
?>

<?php if (have_comments()) : ?>
    <!-- STARKERS NOTE: The following h3 id is left intact so that comments can be referenced on the page -->
    <h3 class="comments-title"><?php
    printf(
        _n('One comment', '%1$s comments', get_comments_number(), 'twentyten'),
        number_format_i18n(get_comments_number()),
        '' . get_the_title() . ''
    );
    ?></h3>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through??>
    <?php previous_comments_link(__('&larr; Older Comments', 'twentyten')); ?>
    <?php next_comments_link(__('Newer Comments &rarr;', 'twentyten')); ?>
    <?php endif; // check for comment navigation?>

    <ol>
        <?php wp_list_comments([ 'reverse_top_level' => true ]); // HACKED TO SHOW OLDEST COMMENT FIRST?>
    </ol>

    <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // Are there comments to navigate through??>
    <?php previous_comments_link('&larr; Older Comments'); ?>
    <?php next_comments_link('Newer Comments &rarr;'); ?>
    <?php endif; // check for comment navigation?>

    <?php endif; // end have_comments()?>
