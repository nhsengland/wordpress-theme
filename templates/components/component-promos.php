<?php if (get_row_layout() === 'promos_component') : ?>
    <?php
    $rows = get_sub_field('promo_component');
    $count = count($rows);
    switch ($count) {
        case '1':
        $countclass = ' has-one-item';
        break;
        case '2':
        $countclass = ' has-two-items';
        break;
        case '3':
        $countclass = ' has-three-items';
        break;
    }
    ?>

    <?php if (have_rows('promo_component')) : ?>

        <div class="row">
            <div class="component-promos<?php echo $countclass ?>">

                <?php while (have_rows('promo_component')) : ?>
                    <?php the_row() ?>

                    <article class="promo">

                        <?php $promo_image = get_sub_field('promo_image')?>

                        <?php if ($promo_image && get_sub_field('promo_url')) : ?>
                            <div class="promo-image">
                                <a href="<?php the_sub_field('promo_url') ?>"><img src="<?php echo $promo_image['url'] ?>" alt="<?php echo $promo_image['alt'] ?>"></a>
                            </div>
                        <?php elseif ($promo_image) : ?>
                            <div class="promo-image">
                                <img src="<?php echo $promo_image['url'] ?>" alt="<?php echo $promo_image['alt'] ?>">
                            </div>
                        <?php endif ?>

                        <?php if (get_sub_field('promo_title') && get_sub_field('promo_url')) : ?>
                            <h3><a href="<?php the_sub_field('promo_url') ?>"><?php the_sub_field('promo_title') ?></a></h3>
                        <?php elseif (get_sub_field('promo_title')) : ?>
                            <h3><?php the_sub_field('promo_title') ?></h3>
                        <?php endif ?>

                        <?php if (get_sub_field('promo_content')) : ?>
                            <div class="rich-text content"><?php the_sub_field('promo_content') ?></div>
                        <?php endif ?>

                    </article>

                <?php endwhile ?>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>
