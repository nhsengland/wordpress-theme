<?php

describe(\NHSEngland\BlogPostType::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->blogPostType = new \NHSEngland\BlogPostType();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->blogPostType)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds actions', function () {
            WP_Mock::expectActionAdded('init', [$this->blogPostType, 'registerBlogPostType']);
            WP_Mock::expectActionAdded('post_submitbox_misc_actions', [$this->blogPostType, 'addStickyBox']);
            WP_Mock::expectActionAdded('save_post_blog', [$this->blogPostType, 'saveStickyPostStatus']);
            $this->blogPostType->register();
        });
    });

    describe('->registerBlogPostType', function () {
        context('is not main site', function () {
            it('does nothing', function () {
                WP_Mock::wpFunction('is_main_site', [
                    'times' => 1,
                    'return' => false
                ]);
                WP_Mock::wpFunction('register_post_type', [
                    'times' => 0
                ]);
                $this->blogPostType->registerBlogPostType();
            });
        });
        context('is main site', function () {
            it('registers post type blog', function () {
                WP_Mock::wpFunction('is_main_site', [
                    'times' => 1,
                    'return' => true
                ]);
                \WP_Mock::wpFunction('register_post_type', [
                    'times' => 1,
                    'args' => [
                        'blog',
                        [
                            'label' => 'Blog posts',
                            'labels' => [
                                'name' => 'Blog posts',
                                'singular_name' => 'Blog post',
                                'add_new_item' => 'Add New Blog post',
                                'edit_item' => 'Edit Blog post',
                                'new_item' => 'New Blog post',
                                'view_item' => 'View Blog post',
                                'view_items' => 'View Blog posts'
                            ],
                            'public' => true,
                            'menu_position' => 5,
                            'has_archive' => true,
                            'supports' => [
                                'title',
                                'editor',
                                'author',
                                'thumbnail',
                                'revisions',
                                'comments'
                            ],
                            'taxonomies' => [
                                'category'
                            ],
                        ]
                    ],
                ]);
                $this->blogPostType->registerBlogPostType();
            });
        });
    });

    describe('addStickyBox', function () {
        context('it is not a blog post', function () {
            it('does nothing', function () {
                $this->post = Mockery::mock(\WP_Post::class);
                $this->post->post_type = 'notablog';
                $this->post->ID = 123;
                global $post;
                $post = $this->post;
                $result = $this->blogPostType->addStickyBox();
                expect($result)->to->equal(null);
            });
        });
        context('is a blog post', function () {
            it('adds a checkbox, using checked function', function () {
                $this->post = Mockery::mock(\WP_Post::class);
                $this->post->post_type = 'blog';
                $this->post->ID = 123;
                global $post;
                $post = $this->post;
                WP_Mock::wpFunction('get_post_meta', [
                    'times' => 1,
                    'args' => [
                        123,
                        '_sticky_blog_post',
                        true
                    ],
                    'return' => false,
                ]);
                WP_Mock::wpFunction('wp_nonce_field', [
                    'times' => 1,
                    'args' => [
                        '_sticky_blog_post_nonce_123',
                        '_sticky_blog_post_nonce'
                    ]
                ]);
                WP_Mock::wpFunction('checked', [
                    'times' => 1,
                    'args' => [
                        false,
                        true,
                        true
                    ]
                ]);
                ob_start();
                $this->blogPostType->addStickyBox();
                $result = ob_get_clean();
                expect($result)->to->include('<div class="misc-pub-section misc-pub-section-last"><label><input type="checkbox" value="1"  name="_sticky_blog_post" />Make this post sticky</label></div>');
            });
        });
    });

    describe('->saveStickyPostStatus()', function () {
        beforeEach(function () {
            $this->postID = 123;
        });
        context('_sticky_blog_post_nonce not set', function () {
            it('does nothing', function () {
                $result = $this->blogPostType->saveStickyPostStatus($this->postID);
                expect($result)->to->equal(null);
            });
        });
        context('_sticky_blog_post_nonce does not verify', function () {
            it('does nothing', function () {
                $_POST['_sticky_blog_post_nonce'] = "foo";
                WP_Mock::wpFunction('wp_verify_nonce', [
                    'times' => 1,
                    'args' => [
                        "foo",
                        "_sticky_blog_post_nonce_123"
                    ],
                    'return' => false
                ]);
                $result = $this->blogPostType->saveStickyPostStatus($this->postID);
                expect($result)->to->equal(null);
            });
        });
        context('current user cannot edit post', function () {
            it('does nothing', function () {
                $_POST['_sticky_blog_post_nonce'] = "foo";
                WP_Mock::wpFunction('wp_verify_nonce', [
                    'times' => 1,
                    'args' => [
                        "foo",
                        "_sticky_blog_post_nonce_123"
                    ],
                    'return' => true
                ]);
                WP_Mock::wpFunction('current_user_can', [
                    'times' => 1,
                    'args' => [
                        'edit_post',
                        $this->postID
                    ],
                    'return' => false
                ]);
                $result = $this->blogPostType->saveStickyPostStatus($this->postID);
                expect($result)->to->equal(null);
            });
        });
        context('current user an edit post', function () {
            context('$_POST[\'_sticky_blog_post\'] is set', function () {
                it('updates the post meta', function () {
                    $_POST['_sticky_blog_post'] = true;
                    $_POST['_sticky_blog_post_nonce'] = "foo";
                    WP_Mock::wpFunction('wp_verify_nonce', [
                        'times' => 1,
                        'args' => [
                            "foo",
                            "_sticky_blog_post_nonce_123"
                        ],
                        'return' => true
                    ]);
                    WP_Mock::wpFunction('current_user_can', [
                        'times' => 1,
                        'args' => [
                            'edit_post',
                            $this->postID
                        ],
                        'return' => true
                    ]);
                    WP_Mock::wpFunction('update_post_meta', [
                        'times' => 1,
                        'args' => [
                            $this->postID,
                            '_sticky_blog_post',
                            $_POST['_sticky_blog_post']
                        ]
                    ]);
                    $this->blogPostType->saveStickyPostStatus($this->postID);
                });
                context('$_POST[\'_sticky_blog_post\'] is not set', function () {
                    it('deletes the post meta', function () {
                        unset($_POST['_sticky_blog_post']);
                        $_POST['_sticky_blog_post_nonce'] = "foo";
                        WP_Mock::wpFunction('wp_verify_nonce', [
                            'times' => 1,
                            'args' => [
                                "foo",
                                "_sticky_blog_post_nonce_123"
                            ],
                            'return' => true
                        ]);
                        WP_Mock::wpFunction('current_user_can', [
                            'times' => 1,
                            'args' => [
                                'edit_post',
                                $this->postID
                            ],
                            'return' => true
                        ]);
                        WP_Mock::wpFunction('delete_post_meta', [
                            'times' => 1,
                            'args' => [
                                $this->postID,
                                '_sticky_blog_post'
                            ]
                        ]);
                        $this->blogPostType->saveStickyPostStatus($this->postID);
                    });
                });
            });
        });
    });
});
