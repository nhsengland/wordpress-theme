<?php
//
// Component ### Blog
//
if (get_row_layout() === 'recent_case_study_component') :

    $publication_type = get_sub_field('recent_case_study_select_publication_type');
    $topic = get_sub_field('recent_case_study_select_topic');
    $number_of_posts = get_sub_field('recent_case_study_number_of_posts');

    $args = [
        'post_type' => 'document',
        'numberposts' => $number_of_posts,
        'orderby' => 'date',
        'order' => 'DESC',
        'tax_query' => [
            'relation' => 'AND',
            [
                'taxonomy' => 'publication-type',
                'field' => 'term_id',
                'terms' => $publication_type,
            ],
        ],
    ];

    if (count($topic) > 0) {
        array_push($args['tax_query'], [
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => $topic
        ]);
    }

    $posts = get_posts($args);

    ?>
    <div class="row">
        <div class="blog-component">
            <?php if (get_sub_field('recent_case_study_section_title')): ?>
                <header>
                    <h2><?php the_sub_field('recent_case_study_section_title') ?></h2>
                </header>
            <?php endif; ?>
            <?php $post = $posts[0];
            setup_postdata($post);
            ?>
            <article class="blog-component-sticky-post">

                <header>
                    <h3><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
                    <time class="post-meta" datetime="<?php echo get_the_date('Y-m-d') ?>" pubdate="<?php echo get_the_date('Y-m-d') ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path class="icon" d="M15.47 3v11.61a.8.8 0 0 1-.63.78A36 36 0 0 1 8 16a36 36 0 0 1-6.83-.6.8.8 0 0 1-.63-.78V3a.8.8 0 0 1 .63-.78c.05 0 .6-.12 1.5-.23v.72A1.35 1.35 0 0 0 4.09 4a1.08 1.08 0 0 1-.35-.8V1.07a1.07 1.07 0 1 1 2.13 0v.58h3.2v1.02A1.35 1.35 0 0 0 10.49 4a1.08 1.08 0 0 1-.35-.8V1.07a1.07 1.07 0 1 1 2.13 0v.75c1.53.17 2.48.35 2.57.37a.8.8 0 0 1 .63.81zm-1.6 11V6.4H2.14V14a36.28 36.28 0 0 0 5.86.48 36.28 36.28 0 0 0 5.87-.48zM3.74 12.8v-2.14h2.13v2.14zm5.33-3.2H6.94V7.46h2.13zm-2.13 3.2v-2.14h2.13v2.14zm5.33-3.2h-2.13V7.46h2.13zm-2.13 3.2v-2.14h2.13v2.14z"/></svg><?php echo get_the_date('jS F Y') ?></time>
                </header>

                <div class="rich-text content">
                    <p><?php echo wp_trim_words(get_field('introduction')); ?></p>
                </div>

            </article>

            <div class="blog-component-recent-posts">

                <ul>
                    <?php
                    for ($i=1; $i < $number_of_posts; $i++) :
                        $post = $posts[$i];
                        setup_postdata($post);
                        ?>
                        <li class="group">
                            <a href="<?php the_permalink() ?>">
                                <h4><?php the_title() ?></h4>
                                <time class="post-meta" datetime="<?php echo get_the_date('Y-m-d') ?>" pubdate="<?php echo get_the_date('Y-m-d') ?>"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path class="icon" d="M15.47 3v11.61a.8.8 0 0 1-.63.78A36 36 0 0 1 8 16a36 36 0 0 1-6.83-.6.8.8 0 0 1-.63-.78V3a.8.8 0 0 1 .63-.78c.05 0 .6-.12 1.5-.23v.72A1.35 1.35 0 0 0 4.09 4a1.08 1.08 0 0 1-.35-.8V1.07a1.07 1.07 0 1 1 2.13 0v.58h3.2v1.02A1.35 1.35 0 0 0 10.49 4a1.08 1.08 0 0 1-.35-.8V1.07a1.07 1.07 0 1 1 2.13 0v.75c1.53.17 2.48.35 2.57.37a.8.8 0 0 1 .63.81zm-1.6 11V6.4H2.14V14a36.28 36.28 0 0 0 5.86.48 36.28 36.28 0 0 0 5.87-.48zM3.74 12.8v-2.14h2.13v2.14zm5.33-3.2H6.94V7.46h2.13zm-2.13 3.2v-2.14h2.13v2.14zm5.33-3.2h-2.13V7.46h2.13zm-2.13 3.2v-2.14h2.13v2.14z"/></svg><?php echo get_the_date('jS F Y') ?></time>
                            </a>
                        </li>
                        <?php
                        wp_reset_postdata();
                        endfor; ?>
                    </ul>

                </div>

            </div>
        </div>
    <?php endif; ?>
