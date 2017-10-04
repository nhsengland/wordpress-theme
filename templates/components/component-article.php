<?php
//
// Component ### Article
//
if (get_row_layout() === 'article_component') :

    $article_image = get_sub_field('article_image');
    $article_image_alignment = get_sub_field('article_image_alignment');
    $article_image_size = get_sub_field('article_image_size');

    if ($article_image || $article_image_size === 'has-zero-width-image') {
        $class = $article_image_size. ' ' .$article_image_alignment;
    } else {
        $class = 'no-image';
    }

    $background = get_sub_field('article_background');
    $background_colour = get_sub_field('article_background_colour');

    // We need to adjust the colour and the margins too
    $container_style = '';
    if ($background === true && preg_match('/^#[0-9a-f]{6}$/', $background_colour)) {
        $container_style = 'background-color: '.$background_colour.';';
    }
    ?>

    <div class="article-component-container" style="<?php echo esc_attr($container_style) ?>">
        <div class="row">
            <article class="article-component <?php echo esc_attr($class) ?>" style="<?php echo esc_attr($component_style) ?>">

                <?php if (get_sub_field('article_image') && get_sub_field('article_url')) : ?>
                    <figure class="article-image">
                        <a href="<?php the_sub_field('article_url'); ?>"><img src="<?php echo $article_image[url]; ?>" alt="<?php echo $article_image[title]; ?>"></a>
                        <?php if ($article_image[caption]) : ?>
                            <figcaption><?php echo $article_image[caption]; ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php elseif (get_sub_field('article_image')) : ?>
                    <figure class="article-image">
                        <img src="<?php echo $article_image[url]; ?>" alt="<?php echo $article_image[title]; ?>">
                        <?php if ($article_image[caption]) : ?>
                            <figcaption><?php echo $article_image[caption]; ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endif; ?>

                <div class="article-content">

                    <?php if (get_sub_field('article_title') && get_sub_field('article_url')) : ?>
                        <h2><a href="<?php the_sub_field('article_url'); ?>"><?php the_sub_field('article_title'); ?></a></h2>
                    <?php elseif (get_sub_field('article_title')) : ?>
                        <h2><?php the_sub_field('article_title'); ?></h2>
                    <?php endif; ?>

                    <div class="rich-text content ">
                        <?php the_sub_field('article_content') ?>
                    </div>

                </div>

            </article>
        </div>
    </div>

<?php endif; ?>
