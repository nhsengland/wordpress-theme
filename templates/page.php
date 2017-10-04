<?php get_header(); ?>

<?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

    <div class="row">

        <?php echo NHSEngland\SubNav::generate(get_the_ID(), 'desktop-only'); ?>

        <div class="page-content" id="main-content">

            <?php get_template_part('partials/breadcrumbs'); ?>

            <header>
                <h1><?php the_title(); ?></h1>
            </header>

            <article class="rich-text">
                <?php the_content(); ?>
            </article>

            <?php if (function_exists('can_be_rated')) {
    get_template_part('inc-ratings');
} ?>

            <?php if (have_rows('default_template_hidden_text_blocks')) : ?>
                <div class="row">
                    <div class="hidden-text-component group">
                        <?php if (get_field('default_template_hidden_text_section_title')) : ?>
                            <h2><?php the_field('default_template_hidden_text_section_title') ?></h2>
                        <?php endif; ?>
                        <?php while (have_rows('default_template_hidden_text_blocks')) : the_row();
                        $slug = sanitize_title(get_sub_field('default_template_hidden_text_summary'));
                        ?>

                        <div class="details">
                            <div class="summary">
                                <a href="#<?php echo $slug ?>" id="<?php echo $slug ?>" class="anchor"><?php the_sub_field('default_template_hidden_text_summary') ?> </a>
                            </div>
                            <div class="panel rich-text content">
                                <?php the_sub_field('default_template_hidden_text_details') ?>
                            </div>
                        </div>

                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="comment-area">
            <?php comments_template('', true); ?>
        </div>

    </div>

    <?php echo NHSEngland\SubNav::generate(get_the_ID(), 'mobile-only'); ?>

</div>

<?php endwhile; ?>

<?php endif; ?>

<?php get_footer(); ?>
