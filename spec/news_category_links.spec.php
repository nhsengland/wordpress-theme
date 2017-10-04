<?php

describe(\NHSEngland\NewsCategoryLinks::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->helpers = \Mockery::mock(\Dxw\Iguana\Theme\Helpers::class);
        $this->newsCategoryLinks = new \NHSEngland\NewsCategoryLinks($this->helpers);
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->newsCategoryLinks)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the action', function () {
            $this->helpers->shouldReceive('registerFunction');
            WP_Mock::expectFilterAdded('term_link', [$this->newsCategoryLinks, 'pointToNewsArchive'], 10, 3);
            $this->newsCategoryLinks->register();
        });

        it('registers function', function () {
            $this->helpers->shouldReceive('registerFunction')->with('getNewsArchiveLink', [$this->newsCategoryLinks, 'getNewsArchiveLink'])->once();
            $this->newsCategoryLinks->register();
        });
    });

    describe('->pointToNewsArchive()', function () {
        context('is not post type of post', function () {
            it('returns termlink', function () {
                global $post;
                $post = new stdClass();
                $post->post_type = 'notPost';
                $this->term = \Mockery::mock(\WP_Term::class);
                $result = $this->newsCategoryLinks->pointToNewsArchive('foo', $this->term, 'category');
                expect($result)->to->equal('foo');
            });
        });
        context('is not a category link', function () {
            it('returns termlink', function () {
                global $post;
                $post = new stdClass();
                $post->post_type = 'post';
                $this->term = \Mockery::mock(\WP_Term::class);
                $result = $this->newsCategoryLinks->pointToNewsArchive('foo', $this->term, 'bar');
                expect($result)->to->equal('foo');
            });
        });
        context('is category link for post type of post', function () {
            it('returns a link to prefiltered archive', function () {
                global $post;
                $post = new stdClass();
                $post->post_type = 'post';
                $this->term = \Mockery::mock(\WP_Term::class);
                $this->term->slug = 'winter things';
                WP_Mock::wpFunction('get_option', [
                    'times' => 1,
                    'args' => 'page_for_posts',
                    'return' => 'thePageOption'
                ]);
                WP_Mock::wpFunction('get_permalink', [
                    'times' => 1,
                    'args' => 'thePageOption',
                    'return' => 'http://england.nhs.uk/news-archive/'
                ]);
                $result = $this->newsCategoryLinks->pointToNewsArchive('foo', $this->term, 'category');
                expect($result)->to->equal('http://england.nhs.uk/news-archive/?filter-keyword=&filter-category=winter+things');
            });
        });
    });

    describe('->getNewsArchiveLink()', function () {
        it('returns something', function () {
            $term = \Mockery::mock(\WP_Term::class, function ($t) {
                $t->slug = 'xyz';
            });
            \WP_Mock::wpFunction('get_term', [
                'args' => [4, 'category'],
                'return' => $term,
            ]);
            \WP_Mock::wpFunction('get_option', [
                'args' => ['page_for_posts'],
                'return' => 8,
            ]);
            \WP_Mock::wpFunction('get_permalink', [
                'args' => [8],
                'return' => '/blawg/',
            ]);

            $result = $this->newsCategoryLinks->getNewsArchiveLink(4);
            expect($result)->to->be->a('string');
            expect($result)->to->equal('/blawg/?filter-keyword=&filter-category=xyz');
        });

        context('with a second argument', function () {
            it('returns something else', function () {
                $term = \Mockery::mock(\WP_Term::class, function ($t) {
                    $t->slug = 'xyz';
                });
                \WP_Mock::wpFunction('get_term', [
                    'args' => [4, 'category'],
                    'return' => $term,
                ]);

                $result = $this->newsCategoryLinks->getNewsArchiveLink(4, '/blog/');
                expect($result)->to->be->a('string');
                expect($result)->to->equal('/blog/?filter-keyword=&filter-category=xyz');
            });
        });
    });
});
