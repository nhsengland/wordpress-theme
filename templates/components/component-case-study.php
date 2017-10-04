<?php
//
// Content Block ### Case Study
//
if (get_row_layout() === 'case_study_component') :
    ?>
    <div class="row">
        <?php if (get_sub_field('case_study_section_title')): ?>
            <header>
                <h2><?php the_sub_field('case_study_section_title') ?></h2>
            </header>
        <?php endif; ?>
        <div class="case-study-component">
            <?php if (have_rows('case_studies')) : $count = 1; ?>
            <?php while (have_rows('case_studies')) : the_row(); ?>

            <?php $posts = get_sub_field('case_study_post'); ?>

            <?php foreach ($posts as $post): ?>
                <?php if ($count == 1): ?>

                    <article class="case-study-sticky-post">

                        <header>
                            <h3><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
                            <?php get_template_part('partials/entry-meta') ?>
                        </header>

                        <div class="rich-text content">
                            <p><?php echo wp_trim_words($post->post_content); ?></p>
                        </div>
                    </article>

                <?php else: ?>

                <div class="case-study-recent-posts">
                    <ul>
                        <li class="group">
                            <a href="<?php the_permalink() ?>">
                                <h4><?php the_title() ?></h4>
                                <?php get_template_part('partials/entry-meta') ?>
                            </a>
                        </li>
                    </ul>
                </div>

                <?php endif; $count++;?>
                <?php endforeach; wp_reset_postdata(); ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif;?>
