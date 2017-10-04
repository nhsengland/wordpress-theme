<?php

namespace NHSEngland;

class BlogLandingPage implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('init', [$this, 'addFields']);
        add_filter('term_link', [$this, 'pointToBlogArchive'], 10, 3);
    }

    public function addFields()
    {
        if (function_exists('acf_add_local_field_group')):
            acf_add_local_field_group([
                'key' => 'field_58aa4535b107a',
                'title' => 'Blog landing page',
                'fields' => [
                    [
                        'key' => 'field_58dd4535b107c',
                        'label' => 'Featured Categories',
                        'name' => 'blog_categories',
                        'type' => 'taxonomy',
                        'instructions' => 'Up to a maximum of 8',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'taxonomy' => 'category',
                        'field_type' => 'multi_select',
                        'allow_null' => 0,
                        'add_term' => 0,
                        'save_terms' => 0,
                        'load_terms' => 0,
                        'return_format' => 'id',
                        'multiple' => 0,
                    ],
                    [
                        'key' => 'field_58aecfd10f1d4',
                        'label' => 'Authors',
                        'name' => 'authors',
                        'type' => 'repeater',
                        'instructions' => 'Up to a maximum of 10',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                        'collapsed' => 'field_58aecc403d9e2',
                        'min' => '',
                        'max' => '10',
                        'layout' => 'block',
                        'button_label' => 'Add author',
                        'sub_fields' => [
                            [
                                'default_value' => '',
                                'maxlength' => '',
                                'placeholder' => '',
                                'prepend' => '',
                                'append' => '',
                                'key' => 'field_58aecc403d9e2',
                                'label' => 'Author name',
                                'name' => 'author_name',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                            ],
                            [
                                'return_format' => 'array',
                                'preview_size' => 'thumbnail',
                                'library' => 'all',
                                'min_width' => '',
                                'min_height' => '',
                                'min_size' => '',
                                'max_width' => '',
                                'max_height' => '',
                                'max_size' => '',
                                'mime_types' => '',
                                'key' => 'field_58aecc523d9e3',
                                'label' => 'Author picture',
                                'name' => 'author_picture',
                                'type' => 'image',
                                'instructions' => 'Should be square',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                            ],
                            [
                                'default_value' => '',
                                'placeholder' => '',
                                'key' => 'field_58aecc7a3d9e4',
                                'label' => 'Author archive link',
                                'name' => 'author_archive_link',
                                'type' => 'url',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => [
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ],
                            ],
                        ],
                    ],
                    [
                        'post_type' => [
                            0 => 'page',
                        ],
                        'taxonomy' => [
                        ],
                        'allow_null' => 0,
                        'multiple' => 0,
                        'allow_archives' => 1,
                        'key' => 'field_58c02b47dc619',
                        'label' => 'All authors page',
                        'name' => 'all_authors_page',
                        'type' => 'page_link',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => [
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ],
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'page_template',
                            'operator' => '==',
                            'value' => 'page-blog-landing.php',
                        ],
                    ],
                ],
                'menu_order' => 1,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => [
                    0 => 'the_content',
                    1 => 'excerpt',
                ],
                'active' => 1,
                'description' => '',
            ]);
        endif;
    }

    public function pointToBlogArchive($termlink /*string*/, $term /*term object*/, $taxonomy /*taxonomy slug*/)
    {
        global $post;
        if ($post->post_type !== 'blog') {
            return $termlink;
        }
        if ($taxonomy !== 'category') {
            return $termlink;
        }
        $archiveLink = get_post_type_archive_link('blog');
        $termSlug = urlencode($term->slug);
        $termlink = $archiveLink . "?filter-category=" . $termSlug;
        return $termlink;
    }
}
