<ul>
    <?php while (have_rows('topics')) : the_row(); ?>
    <li>
        <?php if (get_sub_field('type') === 'Page'):
        $post = get_sub_field('page'); ?>
        <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
    <?php else: ?>
    <a href="<?php echo esc_url(get_sub_field('url')) ?>"><?php echo esc_html(get_sub_field('title')) ?></a>
<?php endif; ?>
    </li>
    <?php wp_reset_postdata();
    endwhile; ?>
</ul>
