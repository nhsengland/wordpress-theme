<?php

if (function_exists('get_coauthors')) {
    $authors = get_coauthors();
    foreach ($authors as $author) {
        ?>
        <div class="author-meta group">

            <div class="avatar-photo">

                <?php
                h()->nhs_avatar($author); ?>

            </div>
            <div class="biography">
                <h5><?php echo coauthors_posts_links_single($author) ?></h5>
                <div class="rich-text">
                    <?php $content = $author->description;
        echo apply_filters('the_content', $content); ?>
                </div>
            </div>

        </div>
        <?php
    }
}
