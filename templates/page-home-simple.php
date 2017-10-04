<?php
/*
Template Name: Sub Site Alternative Homepage
*/

get_header(); ?>

<?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

    <div class="row" id="main-content">

        <div class="simple-homepage group">

            <header>
                <h1><?php the_title(); ?></h1>
            </header>

            <article class="rich-text">
                <?php the_content(); ?>
            </article>
        </div>

        <aside class="sidebar group" role="complementary">
            <?php get_sidebar(); ?>
        </aside>

    </div>

<?php endwhile; ?>

<?php endif; ?>

<?php get_footer(); ?>
