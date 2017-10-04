<?php

namespace NHSEngland;

class DocumentContainer implements \Dxw\Iguana\Registerable
{
    public function __construct(\Dxw\Iguana\Theme\Helpers $helpers)
    {
        $helpers->registerFunction('mimeToEnglish', [$this, 'mimeToEnglish']);
    }

    public function mimeToEnglish(/* string */ $mime)
    {
        $mimes = [
            'image/jpeg' => 'JPEG image',
            'image/gif' => 'GIF image',
            'application/pdf' => 'PDF',
            'image/png' => 'PNG image',
            'text/csv' => 'CSV spreadsheet',
            'application/zip' => 'zip file',
            'audio/mpeg' => 'MPEG audio',
            'application/msword' => 'Microsoft Word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Microsoft Word',
            'application/vnd.ms-excel' => 'Microsoft Excel',
            'application/vnd.ms-excel.%' => 'Microsoft Excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Microsoft Excel',
            'application/vnd.ms-powerpoint' => 'Microsoft PowerPoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'Microsoft PowerPoint',
            'image/%' => 'image',
            'audio/%' => 'audio',
            'video/%' => 'video',
        ];

        foreach ($mimes as $m => $english) {
            if (\Missing\Strings::endsWith($m, '%')) {
                $withoutThePercent = substr($m, 0, -1);
                if (\Missing\Strings::startsWith($mime, $withoutThePercent)) {
                    return $english;
                }
            }
            if ($m === $mime) {
                return $english;
            }
        }

        return $mime;
    }

    public function register()
    {
        add_action('init', [$this, 'init']);
        add_action('manage_document_posts_custom_column', [$this, 'documentCustomColumn'], 10, 2);

        acf_add_local_field_group([
            'key' => 'group_57ed40fa81642',
            'title' => 'Publication Containers',
            'fields' => [
                [
                    'key' => 'field_57ed4101bec1c',
                    'label' => 'Introduction',
                    'name' => 'introduction',
                    'type' => 'wysiwyg',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'default_value' => '',
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 1,
                ],
                [
                    'key' => 'field_57ed4146bec1d',
                    'label' => 'Documents',
                    'name' => 'documents',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => [
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ],
                    'collapsed' => 'field_57ed4155bec1e',
                    'min' => '',
                    'max' => '',
                    'layout' => 'block',
                    'button_label' => 'Add document',
                    'sub_fields' => [
                        [
                            'layout' => 'horizontal',
                            'choices' => [
                                'document' => 'Document',
                                'audiovideo' => 'Audio or Video',
                                'documentlink' => 'Link',
                            ],
                            'default_value' => 'document : Document',
                            'other_choice' => 0,
                            'save_other_choice' => 0,
                            'allow_null' => 0,
                            'return_format' => 'value',
                            'key' => 'field_5888d39537411',
                            'label' => 'Type of publication',
                            'name' => 'type_of_publication',
                            'type' => 'radio',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                        ],
                        [
                            'key' => 'field_57ed43bcbe124',
                            'label' => 'Document',
                            'name' => 'document',
                            'type' => 'file',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => [
                                [
                                    [
                                        'field' => 'field_5888d39537411',
                                        'operator' => '==',
                                        'value' => 'document',
                                    ],
                                ],
                            ],
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'return_format' => 'array',
                            'library' => 'all',
                            'min_size' => '',
                            'max_size' => '',
                            'mime_types' => '',
                        ],
                        [
                            'key' => 'field_5888d3fb37413',
                            'label' => 'Audio or Video Code',
                            'name' => 'audio_or_video',
                            'type' => 'wysiwyg',
                            'instructions' => 'Paste in a URL from either:<br> YouTube E.G https://www.youtube.com/watch?v=IIGsy93ai94 or <br>SoundCloud E.G https://soundcloud.com/nhs-england-823010086',
                            'required' => 0,
                            'default_value' => '',
                            'tabs' => 'all',
                            'toolbar' => 'basic',
                            'media_upload' => 0,
                            'conditional_logic' => [
                                [
                                    [
                                        'field' => 'field_5888d39537411',
                                        'operator' => '==',
                                        'value' => 'audiovideo',
                                    ],
                                ],
                            ],
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                        ],
                        [
                            'default_value' => '',
                            'placeholder' => '',
                            'key' => 'field_57ed4145bec2a',
                            'label' => 'Link',
                            'name' => 'link_url',
                            'type' => 'url',
                            'instructions' => 'A link to a web page or document',
                            'required' => 0,
                            'conditional_logic' => [
                                [
                                    [
                                        'field' => 'field_5888d39537411',
                                        'operator' => '==',
                                        'value' => 'documentlink',
                                    ],
                                ],
                            ],
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                        ],
                        [
                            'key' => 'field_57ed4155bec1e',
                            'label' => 'Title',
                            'name' => 'title',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ],
                        [
                            'key' => 'field_5829eba7a4017',
                            'label' => 'Publication type',
                            'name' => 'publication_type',
                            'type' => 'taxonomy',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'taxonomy' => 'publication-type',
                            'field_type' => 'select',
                            'allow_null' => 0,
                            'add_term' => 1,
                            'save_terms' => 1,
                            'load_terms' => 1,
                            'return_format' => 'object',
                            'multiple' => 0,
                        ],
                        [
                            'key' => 'field_57ed43fabe126',
                            'label' => 'Snapshot',
                            'name' => 'snapshot',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'default_value' => '',
                            'tabs' => 'all',
                            'toolbar' => 'full',
                            'media_upload' => 1,
                        ],
                        [
                            'key' => 'field_57ed421abe122',
                            'label' => 'Number of pages',
                            'name' => 'number_of_pages',
                            'type' => 'number',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => [
                                [
                                    [
                                        'field' => 'field_5888d39537411',
                                        'operator' => '==',
                                        'value' => 'document',
                                    ],
                                ],
                            ],
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'min' => 0,
                            'max' => '',
                            'step' => 1,
                        ],
                        [
                            'key' => 'field_57ed421abe129',
                            'label' => 'Length',
                            'name' => 'length_of_file',
                            'type' => 'time_picker',
                            'display_format' => 'H:i:s',
                            'return_format' => 'H:i:s',
                            'instructions' => 'Length of video/audio file in minutes',
                            'required' => 0,
                            'conditional_logic' => [
                                [
                                    [
                                        'field' => 'field_5888d39537411',
                                        'operator' => '==',
                                        'value' => 'audiovideo',
                                    ],
                                ],
                            ],
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'min' => 0,
                            'max' => '',
                            'step' => 1,
                        ],
                        [
                            'key' => 'field_57ed43a1be123',
                            'label' => 'Thumbnail',
                            'name' => 'thumbnail',
                            'type' => 'image',
                            'instructions' => 'Size the image so it\'s similar to a sheet of A4 (595px in  width by 842px in height).',
                            'required' => 0,
                            'conditional_logic' => [
                                [
                                    [
                                        'field' => 'field_5888d39537411',
                                        'operator' => '==',
                                        'value' => 'document',
                                    ],
                                ],
                            ],
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'return_format' => 'array',
                            'preview_size' => 'thumbnail',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '595',
                            'max_height' => '842',
                            'max_size' => '',
                            'mime_types' => 'jpg,png',
                        ],
                        [
                            'key' => 'field_r12uajnnew',
                            'label' => 'Icon',
                            'name' => 'icon',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => [
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ],
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ],
                    ],
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'document',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ]);
    }

    public function documentCustomColumn($column, $post_id)
    {
        switch ($column) {
            case 'coauthors':
            $post = get_post($post_id);
            $author_id = $post->post_author;
            echo '(' . get_the_author_meta('nickname', $author_id) . ')';
        }
    }

    public function init()
    {
        register_post_type('document', [
            'labels' => [
                'name' => 'Publication Containers',
                'singular_name' => 'Publication Container',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Publication Container',
                'edit_item' => 'Edit Publication Container',
                'new_item' => 'New Publication Container',
                'view_item' => 'View Publication Container',
                'search_items' => 'Search Publication Containers',
                'not_found' => 'No publication containers found',
                'not_found_in_trash' => 'No publication containers found in Trash',
                'parent_item_colon' => 'Parent Publication Container:',
                'all_items' => 'All Publication Containers',
                'archives' => 'Publication Container Archives',
                'insert_into_item' => 'Insert into publication container',
                'uploaded_to_this_item' => 'Uploaded to this publication container',
                'featured_image' => 'Featured Image',
                'set_featured_image' => 'Set featured image',
                'remove_featured_image' => 'Remove featured image',
                'use_featured_image' => 'Use as featured image',
                'filter_items_list' => 'Filter publication containers list',
                'items_list_navigation' => 'Publication Containers list navigation',
                'items_list' => 'Publication Containers list',
            ],
            'supports' => ['title', 'author', 'revisions'],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-analytics',
            'rewrite' => ['slug' => 'publication'],
            'taxonomies' => [
                'category'
            ]
        ]);

        register_taxonomy(
            'publication-type',
            ['document'],
            [
                'label' => 'Publication type',
                'labels' => [
                    'add_new_item' => 'Add new type'
                ],
                'hierarchical' => false,
            ]
        );
    }
}
