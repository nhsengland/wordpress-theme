<?php

describe(\NHSEngland\DocumentContainer::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        $this->helpers = \Mockery::mock(\Dxw\Iguana\Theme\Helpers::class, function ($mock) {
            $mock->shouldReceive('registerFunction');
        });
        $this->documentContainer = new \NHSEngland\DocumentContainer($this->helpers);
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    describe('->__construct()', function () {
        it('registers a helper function', function () {
            $helpers = \Mockery::mock(\Dxw\Iguana\Theme\Helpers::class, function ($mock) {
                $mock->shouldReceive('registerFunction')
                ->with('mimeToEnglish', \Mockery::type('callable'))
                ->once();
            });
            new \NHSEngland\DocumentContainer($helpers);
        });
    });

    describe('->mimeToEnglish()', function () {
        it('can handle a list of known types', function () {
            $matrix = [
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
                'application/vnd.ms-excel.sheet.macroEnabled.12' => 'Microsoft Excel',
                'application/vnd.ms-excel.sheet.binary.macroEnabled.12' => 'Microsoft Excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Microsoft Excel',

                'application/vnd.ms-powerpoint' => 'Microsoft PowerPoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'Microsoft PowerPoint',
            ];

            foreach ($matrix as $mime => $expected) {
                $output = $this->documentContainer->mimeToEnglish($mime);
                expect($output)->to->equal($expected);
            }
        });

        it('returns MIME type if unknown', function () {
            $matrix = [
                'application/dog',
                'emoji/paw-prints',
            ];

            foreach ($matrix as $mime) {
                $output = $this->documentContainer->mimeToEnglish($mime);
                expect($output)->to->equal($mime);
            }
        });

        it('returns a partial response for a partially-known MIME type', function () {
            $matrix = [
                'image/cat' => 'image',
                'image/dog' => 'image',
                'audio/cat' => 'audio',
                'audio/dog' => 'audio',
                'video/cat' => 'video',
                'video/dog' => 'video',
            ];

            foreach ($matrix as $mime => $english) {
                $output = $this->documentContainer->mimeToEnglish($mime);
                expect($output)->to->equal($english);
            }
        });
    });

    it('is registerable', function () {
        expect($this->documentContainer)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('registers things', function () {
            \WP_Mock::expectActionAdded('init', [$this->documentContainer, 'init']);
            \WP_Mock::expectActionAdded('manage_document_posts_custom_column', [$this->documentContainer, 'documentCustomColumn'], 10, 2);
            // The arguments are untested
            \WP_Mock::wpFunction('acf_add_local_field_group', [
                'times' => 1,
            ]);

            $this->documentContainer->register();
        });
    });

    describe('->init()', function () {
        it('registers a new post type and taxonomy', function () {
            \WP_Mock::wpFunction('register_post_type', [
                'args' => [
                    'document',
                    [
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
                    ],
                ],
                'times' => 1,
            ]);

            \WP_Mock::wpFunction('register_taxonomy', [
                'args' => [
                    'publication-type',
                    ['document'],
                    [
                        'label' => 'Publication type',
                        'labels' => [
                            'add_new_item' => 'Add new type'
                        ],
                        'hierarchical' => false,
                    ],
                ],
                'times' => 1,
            ]);

            $this->documentContainer->init();
        });
    });

    describe('->documentCustomColumn()', function () {
        context('column is not coauthors', function () {
            it('does nothing', function () {
                ob_start();
                $this->documentContainer->documentCustomColumn('notcoauthors', 123);
                $result = ob_get_clean();
                expect($result)->to->equal('');
            });
        });
        context('column is coauthors', function () {
            it('echoes the author nickname', function () {
                $this->post = new stdClass();
                $this->post->post_author = 456;
                \WP_Mock::wpFunction('get_post', [
                    'times' => 1,
                    'args' => 123,
                    'return' => $this->post
                ]);
                \WP_Mock::wpFunction('get_the_author_meta', [
                    'times' => 1,
                    'args' => ['nickname', 456],
                    'return' => 'nick'
                ]);
                ob_start();
                $this->documentContainer->documentCustomColumn('coauthors', 123);
                $result = ob_get_clean();
                expect($result)->to->equal('(nick)');
            });
        });
    });
});
