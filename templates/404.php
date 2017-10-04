<?php get_header(); ?>

<div class="row" id="main-content">

    <div class="error-content">

        <header>
            <h1>Page not found (404)</h1>
        </header>

        <p>We're sorry but the page you're looking for doesn't appear to exist or has been moved.</p>
        <p>Try the navigation at the top of the page or perhaps use our site search.</p>

        <div class="error-search">
            <?php get_template_part('searchform'); ?>
        </div>

    </div>

</div>

<?php get_footer(); ?>
