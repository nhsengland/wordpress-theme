<?php if (get_row_layout() === 'notify_component') : ?>

    <div class="row">
        <div class="component-notify <?php the_sub_field('notify_status') ?>">
            <h6><?php the_sub_field('notify_title') ?></h6>
            <div class="rich-text content">
                <?php the_sub_field('notify_content') ?>
            </div>
        </div>
    </div>

<?php endif; ?>
