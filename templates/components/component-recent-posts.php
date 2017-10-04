<?php
//
// Component ### Recent posts
//

if (get_row_layout() === 'recent_posts_component') :

    $category = get_sub_field('select_category');
    $category_obj = get_term_by('term_id', (int)$category[0], 'category');
    $number_of_posts = get_sub_field('number_of_posts');
    $show_see_all = get_sub_field('show_see_all');
    $post_type = get_sub_field('post_type');
    $background = get_sub_field('background');
    $background_colour = get_sub_field('background_colour');
    $container_style = 'margin: 30px 0;';
    if ($background === true && preg_match('/^#[0-9a-f]{6}$/', $background_colour)) {
        $container_style = 'margin: 30px 0; background-color: '.$background_colour.';';
    }
    $args = [
        'post_type' => $post_type,
        'numberposts' => $number_of_posts
    ];
    if (!empty($category)) {
        $args['category'] = $category;
    }
    $posts = get_posts($args);
    $postCount = count($posts);

    ?>

    <div class="full-width-background" style="<?php echo $container_style ?>">
        <div class="row">
            <div class="blog-component">
                <?php if (get_sub_field('section_title')): ?>
                    <header>
                        <h2><?php the_sub_field('section_title') ?></h2>
                    </header>
                <?php endif; ?>
                <?php
                global $post;
                $post = $posts[0];
                setup_postdata($post);
                ?>
                <article class="blog-component-sticky-post">

                    <header>
                        <h3><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
                        <?php get_template_part('partials/entry-meta') ?>
                    </header>

                    <div class="rich-text content">
                        <?php the_excerpt() ?>
                    </div>

                </article>

                <div class="blog-component-recent-posts">

                    <ul>
                        <?php
                        wp_reset_postdata();
                        for ($i=1; $i < $postCount; $i++) :
                            global $post;
                            $post = $posts[$i];
                            setup_postdata($post);
                            ?>
                            <li class="group">
                                <h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>
                                <?php get_template_part('partials/entry-meta') ?>
                            </li>
                            <?php
                            wp_reset_postdata();
                            endfor; ?>
                        </ul>

                    </div>

                    <?php
                    if ($show_see_all) : ?>
                    <div class="see-all-links">
                        <?php foreach ($post_type as $type) :
                        $obj = get_post_type_object($type);
                        $name = $type === 'post' ? 'news' : $type;
                        ?>
                        <p><a href="<?php echo site_url("/{$name}/?filter-category={$category_obj->slug}"); ?>">See
                        all <?php echo esc_html(strtolower($category_obj->name)) ?> <?php echo esc_html(strtolower($obj->labels->name)) ?></a>
                    </p>
                <?php endforeach ?>
            </div>
        <?php endif ?>
    </div>

</div>


  </div>
</div>
<?php endif; ?>
