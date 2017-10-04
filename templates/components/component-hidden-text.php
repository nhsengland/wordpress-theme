<?php if (get_row_layout() === 'hidden_text_component') : ?>

    <div class="row">
        <div class="hidden-text-component group">
            <?php if (get_sub_field('hidden_text_section_title')) : ?>
                <h2><?php the_sub_field('hidden_text_section_title') ?></h2>
            <?php endif; ?>
            <?php if (have_rows('hidden_text_blocks')) : ?>
                <?php while (have_rows('hidden_text_blocks')) : the_row();
                $slug = sanitize_title(get_sub_field('hidden_text_summary')); ?>
                <div class="details">
                    <div class="summary"><a href="#<?php echo $slug ?>" id="<?php echo $slug ?>" class="anchor"><?php the_sub_field('hidden_text_summary') ?></a></div>
                    <div class="panel rich-text content">
                        <?php the_sub_field('hidden_text_details') ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php endif; ?>
