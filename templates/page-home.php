<?php /* Template Name: Home */ ?>

<?php get_header(); ?>

<div class="row" id="main-content">

    <div class="homepage-content group">

        <?php if (get_post_meta(get_the_ID(), 'slider_display_type', true) === 'static') : ?>
            <?php get_template_part('inc-static'); ?>
        <?php else : ?>
            <?php get_template_part('inc-slider'); ?>
        <?php endif ?>

        <section class="homepage-small-promo group">
            <?php $small_promos = get_field('small_promo_boxes'); ?>

            <?php if ($small_promos) : ?>
                <ul>
                    <?php foreach ($small_promos as $small_promo) : ?>
                        <li>
                            <a href="<?php echo $small_promo['small_promo_link']; ?>">
                                <h3><?php echo $small_promo['small_promo_title']; ?></h3>
                                <p><?php echo $small_promo['small_promo_body']; ?></p>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

    </div>

    <aside class="home-sidebar group" role="complementary">

        <?php if (is_front_page()): ?>

            <?php get_sidebar(); ?>

        <?php else: ?>

        <?php if (have_rows('sidebar_links_topics')) : ?>
            <div class="sidebar-links menu-in-this-section-container">
                <h4><?php the_field('sidebar_links_title') ?></h4>
                <ul>
                    <?php while (have_rows('sidebar_links_topics')) : the_row(); ?>
                    <li>
                        <a href="<?php the_sub_field('sidebar_links_url')?>"><?php the_sub_field('sidebar_links_title')?></a>
                    </li>
                    <?php wp_reset_postdata();
                    endwhile; ?>
                </ul>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</aside>

</div>

<?php get_footer(); ?>
