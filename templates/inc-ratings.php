<?php if (can_be_rated(get_the_ID())) : ?>
    <div class="rating-item group first">
        <p class="text">Rate format of the product</p>
        <?php ratings_ui('product-format') ?>
    </div>

    <div class="rating-item group">
        <p class="text">Rate the usefulness of the content</p>
        <?php ratings_ui('content-usefulness') ?>
    </div>

    <div class="rating-item group">
        <p class="text">Has the product had a beneficial impact on your work?</p>
        <?php ratings_ui('beneficial-impact') ?>
    </div>
<?php endif ?>
