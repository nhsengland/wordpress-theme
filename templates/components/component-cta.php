<?php 

if (get_row_layout() === 'call_to_action_component') :

    $ctaStyle = get_sub_field('cta_style');
    $ctaBackgroundImage = get_sub_field('cta_background_image');
    $ctaPosition = get_sub_field('cta_position');

    ?>

    <div class="cta-component cta-<?php echo $ctaStyle; ?> cta-<?php echo $ctaPosition; ?>" <?php if ($ctaBackgroundImage) : ?>style="background-image: url('<?php echo $ctaBackgroundImage; ?>')"<?php endif; ?>>
        <div class="row">
            <section>
                <h2><?php the_sub_field('cta_title'); ?></h2>
                <div class="rich-text content">
                    <?php
                    if (get_sub_field('cta_show_text_field')) {
                        the_sub_field('cta_text');
                    }
                    ?>
                </div>
                <a href="<?php the_sub_field('cta_button_url'); ?>" class="button"><?php the_sub_field('cta_button_text'); ?></a>
            </section>
        </div>
    </div>

<?php endif; ?>
