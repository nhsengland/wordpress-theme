<div class="home-page-static group">

    <?php $slider_images = get_field('slider_images'); ?>

    <?php if ($slider_images) {
    $n = 1; ?>

        <?php foreach ($slider_images as $slider_image) {
        ?>

            <?php if ($n == 1) {
            ?>

                <section class="homepage-featured group">
                    <a href="<?php echo $slider_image['link'] ?>">
                        <figure>
                            <img src="<?php echo $slider_image['image'] ?>" alt="<?php echo $slider_image['title'] ?>">
                        </figure>
                        <article class="excerpt rich-text">
                            <h1><?php echo $slider_image['title'] ?></h1>
                            <p><?php echo $slider_image['body'] ?></p>
                        </article>
                    </a>
                </section>

                <?php $n++;
        } else {
            ?>

                <div class="homepage-news-wrapper">
                    <section class="homepage-news-item <?php echo ($n++ % 4 === 0) ? 'last' : '' ?>">
                        <a href="<?php echo $slider_image['link'] ?>">
                            <figure>
                                <img src="<?php echo $slider_image['image'] ?>" alt="<?php echo $slider_image['title'] ?>">
                            </figure>
                            <article>
                                <h2><?php echo $slider_image['title'] ?></h2>
                                <p><?php echo $slider_image['body'] ?></p>
                            </article>
                        </a>
                    </section>
                </div>

                <?php
        } ?>

            <?php
    } ?>

        <?php
} ?>

</div>
