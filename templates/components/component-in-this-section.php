<?php if (get_row_layout() === 'in_this_section_component') : ?>
    <div class="row in-this-section-row">
        <div class="in-this-section-component">
            <?php if (get_sub_field('in_this_section_title')): ?>
                <h2><?php the_sub_field('in_this_section_title') ?></h2>
            <?php endif; ?>
            <ul>
                <?php while (have_rows('in_this_section_topics')) : the_row(); ?>
                <li class="js-equalheight">
                    <?php if (get_sub_field('type') === 'page'):
                    $post = get_sub_field('in_this_section_page'); ?>
                    <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
                <?php else: ?>
                <a href="<?php echo esc_url(get_sub_field('in_this_section_link_url')) ?>"><?php echo esc_html(get_sub_field('in_this_section_link_title')) ?></a>
            <?php endif; ?>
        </li>
        <?php wp_reset_postdata();
        endwhile; ?>
    </ul>
</div>
</div>
<?php endif; ?>
