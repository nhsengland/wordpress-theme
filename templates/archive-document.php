<?php
$keyword = $GLOBALS['wp_query']->get('s');
if (!is_null($keyword) && $keyword !== '') {
    relevanssi_do_query($GLOBALS['wp_query']);
}
?>

<?php get_header(); ?>

<section class="document-container-archive" id="main-content">

    <div class="row">

        <?php if (get_field('show_publication_container_beta_banner', 'options')): ?>
            <div class="alert-container rich-text">
                <div class="wpc-message alert">
                    <?php echo apply_filters('the_content', get_field('publication_banner_beta_text', 'options')); ?>
                </div>
            </div>
        <?php endif; ?>

        <header>
            <h1>Publications</h1>
        </header>

        <aside class="sidebar sidebar-filters">
            <p>You can use the filters to show only publications that match your interests</p>

            <form class="filters" action="<?php echo esc_url(get_post_type_archive_link('document')) ?>" method="GET">

                <div class="filter-group">
                    <?php h()->renderDocumentSearchField('keyword') ?>
                </div>

                <div class="filter-group">
                    <?php h()->renderDocumentSearchField('category') ?>
                </div>

                <div class="filter-group">
                    <?php h()->renderDocumentSearchField('publication') ?>
                </div>

                <fieldset>
                    <legend>Date range</legend>

                    <div class="filter-group">
                        <?php h()->renderDocumentSearchField('dateFrom') ?>
                    </div>

                    <div class="filter-group">
                        <?php h()->renderDocumentSearchField('dateTo') ?>
                    </div>

                </fieldset>

                <input type="submit" value="Search">

                <input type="reset" value="Reset" id="js-form-reset-button">

            </form>
        </aside>

        <div class="filtered-list">

            <div class="filtered-list-summary">
                <?php
                $count = $GLOBALS['wp_query']->found_posts;
                echo sprintf(_n('<span class="count">%s</span> <strong>publication</strong>', '<span class="count">%s</span> <strong>publications</strong>', $count), number_format_i18n($count));
                ?>
            </div>

            <?php while (have_posts()) : ?>
                <?php the_post(); ?>
                <article class="post group">
                    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                    <?php get_template_part('partials/entry-meta') ?>
                </article>
            <?php endwhile; ?>

            <?php
            h()->pagination();
            ?>

            <?php wp_reset_postdata(); ?>

        </div>

    </div>
</section>

<?php get_footer(); ?>
