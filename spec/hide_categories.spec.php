<?php

describe(\NHSEngland\DocumentSearch\HideCategories::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->hideCategories = new NHSEngland\HideCategories();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->hideCategories)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the filters', function () {
            WP_Mock::expectFilterAdded('get_the_terms', [$this->hideCategories, 'get_the_terms'], 10, 3);
            WP_Mock::expectFilterAdded('get_terms', [$this->hideCategories, 'get_terms'], 10, 2);
            $this->hideCategories->register();
        });
    });

    describe('->get_the_terms()', function () {
        context('on an admin page', function () {
            it('does nothing', function () {
                $this->term1 = new stdClass();
                $this->term2 = new stdClass();
                $this->terms = [
                    $this->term1,
                    $this->term2
                ];
                $this->postID = 123;
                $this->taxonomy = 'category';
                WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => true
                ]);
                $result = $this->hideCategories->get_the_terms($this->terms, $this->postID, $this->taxonomy);
                expect($result)->to->equal($this->terms);
            });
        });
        context('not on admin page', function () {
            context('taxonomy is not category', function () {
                it('returns the terms', function () {
                    $this->term1 = new stdClass();
                    $this->term2 = new stdClass();
                    $this->terms = [
                        $this->term1,
                        $this->term2
                    ];
                    $this->postID = 123;
                    $this->taxonomy = 'notcategory';
                    WP_Mock::wpFunction('is_admin', [
                        'times' => 1,
                        'return' => false
                    ]);
                    $result = $this->hideCategories->get_the_terms($this->terms, $this->postID, $this->taxonomy);
                    expect($result)->to->equal($this->terms);
                });
            });
            context('taxonomy is category', function () {
                context('terms do not include excluded terms', function () {
                    it('returns the terms', function () {
                        $this->term1 = new stdClass();
                        $this->term1->name = 'foo';
                        $this->term2 = new stdClass();
                        $this->term2->name = 'bar';
                        $this->terms = [
                            $this->term1,
                            $this->term2
                        ];
                        $this->postID = 123;
                        $this->taxonomy = 'category';
                        WP_Mock::wpFunction('is_admin', [
                            'times' => 1,
                            'return' => false
                        ]);
                        $result = $this->hideCategories->get_the_terms($this->terms, $this->postID, $this->taxonomy);
                        expect($result)->to->equal($this->terms);
                    });
                });
                context('terms include one of the excluded terms', function () {
                    it('returns the terms without excluded ones', function () {
                        $this->term1 = new stdClass();
                        $this->term1->name = 'foo';
                        $this->term2 = new stdClass();
                        $this->term2->name = 'Home';
                        $this->terms = [
                            $this->term1,
                            $this->term2
                        ];
                        $this->postID = 123;
                        $this->taxonomy = 'category';
                        WP_Mock::wpFunction('is_admin', [
                            'times' => 1,
                            'return' => false
                        ]);
                        $result = $this->hideCategories->get_the_terms($this->terms, $this->postID, $this->taxonomy);
                        expect($result)->to->equal([$this->term1]);
                    });
                });
            });
        });
    });

    describe('->get_terms()', function () {
        context('on admin page', function () {
            it('returns the terms', function () {
                $this->term1 = new stdClass();
                $this->term2 = new stdClass();
                $this->terms = [
                    $this->term1,
                    $this->term2
                ];
                $this->taxonomies = [
                    'category',
                    'foo'
                ];
                WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => true,
                ]);
                $result = $this->hideCategories->get_terms($this->terms, $this->taxonomies);
                expect($result)->to->equal($this->terms);
            });
        });
        context('taxonomies do not include category', function () {
            it('returns the terms', function () {
                $this->term1 = new stdClass();
                $this->term2 = new stdClass();
                $this->terms = [
                    $this->term1,
                    $this->term2
                ];
                $this->taxonomies = [
                    'foo'
                ];
                WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false,
                ]);
                $result = $this->hideCategories->get_terms($this->terms, $this->taxonomies);
                expect($result)->to->equal($this->terms);
            });
        });
        context('taxonomies do include category', function () {
            context('terms do not include excluded terms', function () {
                it('returns the terms', function () {
                    $this->term1 = new stdClass();
                    $this->term1->name = 'foo';
                    $this->term1->taxonomy = 'foo';
                    $this->term2 = new stdClass();
                    $this->term2->name = 'bar';
                    $this->term2->taxonomy = 'category';
                    $this->terms = [
                        $this->term1,
                        $this->term2
                    ];
                    $this->taxonomies = [
                        'foo',
                        'category'
                    ];
                    WP_Mock::wpFunction('is_admin', [
                        'times' => 1,
                        'return' => false,
                    ]);
                    $result = $this->hideCategories->get_terms($this->terms, $this->taxonomies);
                    expect($result)->to->equal($this->terms);
                });
            });
            context('terms do include excluded terms', function () {
                it('returns the terms without the excluded terms', function () {
                    $this->term1 = new stdClass();
                    $this->term1->name = 'foo';
                    $this->term1->taxonomy = 'foo';
                    $this->term2 = new stdClass();
                    $this->term2->name = 'Home';
                    $this->term2->taxonomy = 'category';
                    $this->terms = [
                        $this->term1,
                        $this->term2
                    ];
                    $this->taxonomies = [
                        'foo',
                        'category'
                    ];
                    WP_Mock::wpFunction('is_admin', [
                        'times' => 1,
                        'return' => false,
                    ]);
                    $result = $this->hideCategories->get_terms($this->terms, $this->taxonomies);
                    expect($result)->to->equal([$this->term1]);
                });
            });
            context('terms do include excluded terms, but from a different taxonomy', function () {
                it('returns the terms', function () {
                    $this->term1 = new stdClass();
                    $this->term1->name = 'foo';
                    $this->term1->taxonomy = 'foo';
                    $this->term2 = new stdClass();
                    $this->term2->name = 'Home';
                    $this->term2->taxonomy = 'bar';
                    $this->terms = [
                        $this->term1,
                        $this->term2
                    ];
                    $this->taxonomies = [
                        'foo',
                        'category'
                    ];
                    WP_Mock::wpFunction('is_admin', [
                        'times' => 1,
                        'return' => false,
                    ]);
                    $result = $this->hideCategories->get_terms($this->terms, $this->taxonomies);
                    expect($result)->to->equal($this->terms);
                });
            });
        });
    });
});
