<?php

describe(\NHSEngland\BlogLandingPage::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->blogLandingPage = new \NHSEngland\BlogLandingPage();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->blogLandingPage)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the action and filter', function () {
            WP_Mock::expectActionAdded('init', [$this->blogLandingPage, 'addFields']);
            WP_Mock::expectFilterAdded('term_link', [$this->blogLandingPage, 'pointToBlogArchive'], 10, 3);
            $this->blogLandingPage->register();
        });
    });

    describe('->addFields()', function () {
        it('adds the ACF fields', function () {
            WP_Mock::wpFunction('acf_add_local_field_group', [
                'times' => 1,
                'args' => [
                    \WP_Mock\Functions::type('array')
                ]
            ]);
            $this->blogLandingPage->addFields();
        });
    });

    describe('->pointToBlogArchive()', function () {
        context('post type is not blog', function () {
            it('returns termlink', function () {
                global $post;
                $post = new stdClass();
                $post->post_type = 'post';
                $this->term = new stdClass();
                $result = $this->blogLandingPage->pointToBlogArchive('foo', $this->term, 'category');
                expect($result)->to->equal('foo');
            });
        });
        context('is not a category link', function () {
            it('returns termlink', function () {
                global $post;
                $post = new stdClass();
                $post->post_type = 'blog';
                $this->term = new stdClass();
                $result = $this->blogLandingPage->pointToBlogArchive('foo', $this->term, 'bar');
                expect($result)->to->equal('foo');
            });
        });
        context('is category link for post type blog', function () {
            it('returns a link to prefiltered archive', function () {
                global $post;
                $post = new stdClass();
                $post->post_type = 'blog';
                $this->term = new stdClass();
                $this->term->slug = 'winter things';
                WP_Mock::wpFunction('get_post_type_archive_link', [
                    'times' => 1,
                    'args' => 'blog',
                    'return' => 'http://england.nhs.uk/blog-archive/'
                ]);
                $result = $this->blogLandingPage->pointToBlogArchive('foo', $this->term, 'category');
                expect($result)->to->equal('http://england.nhs.uk/blog-archive/?filter-category=winter+things');
            });
        });
    });
});
