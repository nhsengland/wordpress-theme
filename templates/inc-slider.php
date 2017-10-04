<?php $slider_images = get_field('slider_images'); ?>

<ul class="bxslider">
    <?php  if ($slider_images) {
    ?>
        <?php foreach ($slider_images as $slider_image) {
        ?>
            <li>
                <a href="<?php echo $slider_image['link'] ?>">
                    <img src="<?php echo $slider_image['image'] ?>" alt="<?php echo $slider_image['title'] ?>" />
                    <div class="homepage-intro">
                        <h1><?php echo $slider_image['title'] ?></h1>
                        <p><?php echo $slider_image['body'] ?></p>
                    </div>
                </a>
            </li>
            <?php
    } ?>
        <?php
} ?>
</ul>
