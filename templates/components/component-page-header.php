<?php if (get_field('page_title_background_image')):
$pageBackgroundScroll = get_field('page_title_background_scroll');
switch ($pageBackgroundScroll) {
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

<header class="component-section-header">
    <div class="parallax-window" data-parallax="scroll" data-speed="<?php echo $speed ?>" data-image-src="<?php echo get_field('page_title_background_image'); ?>">
        <div class="section-caption row">
            <?php if (get_field('page_title_white_background_behind_text') == true): ?>
                <div class="overlay">
                <?php endif; ?>
                <?php if (get_field('page_title_section_title')): ?>
                    <b><?php the_field('page_title_section_title') ?></b>
                <?php endif; ?>
                <?php if (get_field('page_title_title')): ?>
                    <h1><span><?php the_field('page_title_title')?></span></h1>
                <?php else: ?>
                <h1><span><?php the_title(); ?></span></h1>
            <?php endif; ?>
            <?php if (get_field('page_title_content')): ?>
                <div class="rich-text">
                    <?php the_field('page_title_content') ?>
                </div>
            <?php endif; ?>
            <?php if (get_sub_field('white_background_behind_text') == true): ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</header>

<?php else: ?>

<header class="page-title-header" <?php echo((is_front_page() && is_main_site()) ? 'style="display:none"' : '') ?>>
    <div class="row">
        <h1><?php the_title(); ?></h1>
    </div>
</header>

<?php endif; ?>
