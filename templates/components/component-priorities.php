<?php if (get_row_layout() === 'priorities_component') : ?>

    <div class="row">
        <?php if (get_sub_field('priorities_section_title')): ?>
            <h2><?php the_sub_field('priorities_section_title') ?></h2>
        <?php endif; ?>
        <?php if (have_rows('our_priorities')) : ?>
            <ul class="component-our-priorities">
                <?php while (have_rows('our_priorities')) : the_row(); ?>
                <li class="priority js-equalheight">
                    <?php if (get_sub_field('priority_title')): ?>
                        <span>
                            <?php if (get_sub_field('priority_url')): ?>
                                <a href="<?php the_sub_field('priority_url') ?>"><?php the_sub_field('priority_title') ?></a>
                            <?php else : ?>
                                <?php the_sub_field('priority_title') ?>
                            <?php endif; ?>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="arrow"><path fill="#ffffff" d="M14.9 8.4c.1-.2.1-.5 0-.7 0 0 0-.1-.1-.1 0-.1 0-.1-.1-.2l-5-6c-.4-.4-1-.5-1.4-.1-.4.4-.5 1-.1 1.4L11.9 7H2c-.6 0-1 .4-1 1s.4 1 1 1h9.9l-3.6 4.4c-.4.4-.3 1.1.1 1.4.1.1.4.2.6.2.3 0 .6-.1.8-.4l5-6s.1-.1.1-.2z"/></svg>
                        </span>
                    <?php endif; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>
</div>

<?php endif; ?>
