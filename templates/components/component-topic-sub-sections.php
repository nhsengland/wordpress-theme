<?php if (get_row_layout() === 'topic_section_component') : ?>

    <div class="row">
        <?php if (get_sub_field('topic_section_title')): ?>
            <h2><?php the_sub_field('topic_section_title') ?></h2>
        <?php endif; ?>
        <div class="component-topic-sub-sections">
            <?php if (have_rows('in_this_section')) : ?>
                <?php while (have_rows('in_this_section')) : the_row(); ?>
                <article class="topic js-equalheight">
                    <div class="topic-summary">
                        <h3><a href="<?php the_sub_field('topic_url') ?>"><?php the_sub_field('topic_title') ?></a></h3>
                        <div class="rich-text content">
                            <?php the_sub_field('topic_content') ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php endif; ?>
