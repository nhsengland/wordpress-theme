<?php

namespace NHSEngland;

use Dxw\Iguana\Registerable;

class CategoryFields implements Registerable
{
    public function register()
    {
        add_action('init', [$this, 'addFields']);
    }

    public function addFields()
    {
        acf_add_local_field_group([
            'key' => 'group_58246bee235f3',
            'title' => 'Category description',
            'fields' => [
                [
                    'key' => 'field_58246bff6e323',
                    'label' => 'Rich description',
                    'name' => 'description',
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
            ],
            'location' => [
                [
                    [
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'category',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ]);

        acf_add_local_field_group([
            'key' => 'group_58c680694ce46',
            'title' => 'Topic dropdowns',
            'fields' => [
                [
                    'key' => 'field_58c6807f4aa42',
                    'label' => 'Priority topics',
                    'name' => 'priority_topics',
                    'type' => 'taxonomy',
                    'instructions' => '',
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
                    'return_format' => 'object',
                    'multiple' => 0,
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'acf-options-extra-options',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => 1,
            'description' => '',
        ]);
    }
}
