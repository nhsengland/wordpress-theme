<?php

describe(\NHSEngland\NewsHomepage::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->newsHomepage = new \NHSEngland\NewsHomepage();
        $this->wpQuery = Mockery::mock(WP_Query::class);
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->newsHomepage)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the action', function () {
            WP_Mock::expectActionAdded('pre_get_posts', [$this->newsHomepage, 'dontListFeaturedPosts']);
            WP_Mock::expectActionAdded('pre_get_posts', [$this->newsHomepage, 'onlyListNewsPosts']);
            $this->newsHomepage->register();
        });
    });

    describe('->dontListFeaturedPosts', function () {
        context('doing a search filter', function () {
            it('Ignores sticky posts', function () {
                $_GET['filter-keyword'] = 'test';
                $this->wpQuery->shouldReceive('set')
                    ->once()
                    ->with('ignore_sticky_posts', 1);
                $this->newsHomepage->dontListFeaturedPosts($this->wpQuery);
            });
        });
        context('not on home', function () {
            it('Does nothing', function () {
                $_GET['filter-keyword'] = null;
                $this->wpQuery->shouldReceive('is_home')
                    ->once()
                    ->andReturn(false);
                $this->newsHomepage->dontListFeaturedPosts($this->wpQuery);
            });
        });
        context('on home but not main query', function () {
            it('Does nothing', function () {
                $_GET['filter-keyword'] = null;
                $this->wpQuery->shouldReceive('is_home')
                    ->once()
                    ->andReturn(true);
                $this->wpQuery->shouldReceive('is_main_query')
                    ->once()
                    ->andReturn(false);
                $this->newsHomepage->dontListFeaturedPosts($this->wpQuery);
            });
        });
        context('on home and main query', function () {
            it('Excludes sticky post', function () {
                $this->wpQuery->shouldReceive('is_home')
                    ->once()
                    ->andReturn(true);
                $this->wpQuery->shouldReceive('is_main_query')
                    ->once()
                    ->andReturn(true);
                WP_Mock::wpFunction('get_option', [
                    'times' => 1,
                    'args' => ['sticky_posts'],
                    'return' => [1]
                ]);
                $this->wpQuery->shouldReceive('set')
                    ->once()
                    ->with(
                        'post__not_in',
                        [1]
                    );
                $this->newsHomepage->dontListFeaturedPosts($this->wpQuery);
            });
            context('more than one sticky post', function () {
                it('only excludes the most recent sticky', function () {
                    $this->wpQuery->shouldReceive('is_home')
                        ->once()
                        ->andReturn(true);
                    $this->wpQuery->shouldReceive('is_main_query')
                        ->once()
                        ->andReturn(true);
                    WP_Mock::wpFunction('get_option', [
                        'times' => 1,
                        'args' => ['sticky_posts'],
                        'return' => [1, 4, 5]
                    ]);
                    $this->wpQuery->shouldReceive('set')
                        ->once()
                        ->with(
                            'post__not_in',
                            [1]
                        );
                    $this->newsHomepage->dontListFeaturedPosts($this->wpQuery);
                });
            });
            context('no sticky posts (returns false)', function () {
                it('Excludes most recent post', function () {
                    $this->wpQuery->shouldReceive('is_home')
                        ->once()
                        ->andReturn(true);
                    $this->wpQuery->shouldReceive('is_main_query')
                        ->once()
                        ->andReturn(true);
                    WP_Mock::wpFunction('get_option', [
                        'times' => 1,
                        'args' => ['sticky_posts'],
                        'return' => false
                    ]);
                    $this->post2 = new stdClass();
                    $this->post2->ID = 2;
                    WP_Mock::wpFunction('wp_get_recent_posts', [
                        'times' => 1,
                        'args' => [
                            [
                                'numberposts' => 1,
                            ]
                        ],
                        'return' => [$this->post2]
                    ]);
                    WP_Mock::wpFunction('wp_list_pluck', [
                        'times' => 1,
                        'args' => [
                            [$this->post2],
                            'ID'
                        ],
                        'return' => [2]
                    ]);
                    $this->wpQuery->shouldReceive('set')
                        ->once()
                        ->with(
                            'post__not_in',
                            [2]
                        );
                    $this->newsHomepage->dontListFeaturedPosts($this->wpQuery);
                });
            });
            context('no sticky posts (returns empty string)', function () {
                it('Excludes most recent 3 posts', function () {
                    $this->wpQuery->shouldReceive('is_home')
                        ->once()
                        ->andReturn(true);
                    $this->wpQuery->shouldReceive('is_main_query')
                        ->once()
                        ->andReturn(true);
                    WP_Mock::wpFunction('get_option', [
                        'times' => 1,
                        'args' => ['sticky_posts'],
                        'return' => ''
                    ]);
                    $this->post2 = new stdClass();
                    $this->post2->ID = 2;
                    WP_Mock::wpFunction('wp_get_recent_posts', [
                        'times' => 1,
                        'args' => [
                            [
                                'numberposts' => 1,
                            ]
                        ],
                        'return' => [$this->post2]
                    ]);
                    WP_Mock::wpFunction('wp_list_pluck', [
                        'times' => 1,
                        'args' => [
                            [$this->post2],
                            'ID'
                        ],
                        'return' => [2]
                    ]);
                    $this->wpQuery->shouldReceive('set')
                        ->once()
                        ->with(
                            'post__not_in',
                            [2]
                        );
                    $this->newsHomepage->dontListFeaturedPosts($this->wpQuery);
                });
            });
        });
    });

    describe('onlyListNewsPosts', function () {
        context('is not home', function () {
            it('does nothing', function () {
                $this->wpQuery->shouldReceive('is_home')
                    ->once()
                    ->andReturn(false);
                $this->wpQuery->shouldNotReceive('set');
                $this->newsHomepage->onlyListNewsPosts($this->wpQuery);
            });
        });
        context('is home', function () {
            context('is not main query', function () {
                it('does nothing', function () {
                    $this->wpQuery->shouldReceive('is_home')
                        ->once()
                        ->andReturn(true);
                    $this->wpQuery->shouldReceive('is_main_query')
                        ->once()
                        ->andReturn(false);
                    $this->wpQuery->shouldNotReceive('set');
                    $this->newsHomepage->onlyListNewsPosts($this->wpQuery);
                });
            });
            context('is main query', function () {
                it('sets post_type to post', function () {
                    $this->wpQuery->shouldReceive('is_home')
                        ->once()
                        ->andReturn(true);
                    $this->wpQuery->shouldReceive('is_main_query')
                        ->once()
                        ->andReturn(true);
                    $this->wpQuery->shouldReceive('set')
                        ->once()
                        ->with(
                            'post_type',
                            'post'
                        );
                    $this->newsHomepage->onlyListNewsPosts($this->wpQuery);
                });
            });
        });
    });
});
