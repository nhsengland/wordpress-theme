<?php
if (get_row_layout() === 'section_header_component') :

    $backgroundScroll = get_sub_field('section_header_background_scroll');
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
    ?>

    <div class="component-section-header">
        <div class="parallax-window" data-parallax="scroll" data-speed="<?php echo $speed ?>" data-image-src="<?php echo get_sub_field('section_header_image'); ?>">
            <div class="section-caption row">
                <?php if (get_sub_field('section_header_white_background_behind_text') == true): ?>
                    <div class="overlay">
                    <?php endif; ?>
                    <?php if (get_sub_field('section_header_section_title')): ?>
                        <b><?php the_sub_field('section_header_section_title') ?></b>
                    <?php endif; ?>
                    <?php if (get_sub_field('section_header_section_title')): ?>
                        <h1><span><?php the_sub_field('section_header_page_title')?></span></h1>
                    <?php else: ?>
                    <h1><span><?php the_title(); ?></span></h1>
                <?php endif; ?>
                <?php if (get_sub_field('section_header_content')): ?>
                    <div class="rich-text content">
                        <?php the_sub_field('section_header_content') ?>
                    </div>
                <?php endif; ?>
                <?php if (get_sub_field('white_background_behind_text') == true): ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php endif; ?>
