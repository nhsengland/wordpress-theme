<?php

describe(\NHSEngland\DocumentContainerTaxonomyLinks::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        $this->documentContainerTaxonomyLinks = new \NHSEngland\DocumentContainerTaxonomyLinks();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('implements Dxw\Iguana\Registerable', function () {
        expect($this->documentContainerTaxonomyLinks)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    it('has FILTER_PARAMS constant', function () {
        expect(\NHSEngland\DocumentContainerTaxonomyLinks::FILTER_PARAMS)->to->equal([
            'publication-type' => 'filter-publication',
            'category' => 'filter-category'
        ]);
    });

    describe('->register()', function () {
        it('adds the filter()', function () {
            WP_Mock::expectFilterAdded('term_link', [$this->documentContainerTaxonomyLinks, 'pointToDocumentSearch'], 10, 3);
            $this->documentContainerTaxonomyLinks->register();
        });
    });

    describe('->pointToDocumentSearch', function () {
        beforeEach(function () {
            $this->termlink = "<a href='http://www.bbc.co.uk/'>Foo</a>";
            $this->term = new stdClass();
            $this->term->slug = 'term slug';
            $this->taxonomy = 'slug';
        });
        context('is not post type document', function () {
            it('returns standard termlink', function () {
                global $post;
                $post = new stdClass();
                $post->post_type = 'post';
                $result = $this->documentContainerTaxonomyLinks->pointToDocumentSearch($this->termlink, $this->term, $this->taxonomy);
                expect($result)->to->equal($this->termlink);
            });
        });
        context('is not a single', function () {
            it('returns standard termlink', function () {
                global $post;
                $post = new stdClass();
                $post->post_type = 'document';
                WP_Mock::wpFunction('is_single', [
                    'times' => 1,
                    'return' => false
                ]);
                $result = $this->documentContainerTaxonomyLinks->pointToDocumentSearch($this->termlink, $this->term, $this->taxonomy);
                expect($result)->to->equal($this->termlink);
            });
        });
        context('is single of post type document', function () {
            beforeEach(function () {
                global $post;
                $post = new stdClass();
                $post->post_type = 'document';
                WP_Mock::wpFunction('is_single', [
                    'times' => 1,
                    'return' => true
                ]);
            });
            context('taxonomy is not category or publication type', function () {
                it('returns standard termlink', function () {
                    $result = $this->documentContainerTaxonomyLinks->pointToDocumentSearch($this->termlink, $this->term, $this->taxonomy);
                    expect($result)->to->equal($this->termlink);
                });
            });
            context('taxonomy is publication type', function () {
                it('returns a url-encoded link', function () {
                    WP_Mock::wpFunction('get_post_type_archive_link', [
                        'times' => 1,
                        'args' => 'document',
                        'return' => 'https://england.nhs.uk/publication/'
                    ]);
                    $this->taxonomy = 'publication-type';
                    $result = $this->documentContainerTaxonomyLinks->pointToDocumentSearch($this->termlink, $this->term, $this->taxonomy);
                    expect($result)->to->equal('https://england.nhs.uk/publication/?filter-publication=term+slug');
                });
            });
            context('taxonomy is category', function () {
                it('returns a url-encoded link', function () {
                    WP_Mock::wpFunction('get_post_type_archive_link', [
                        'times' => 1,
                        'args' => 'document',
                        'return' => 'https://england.nhs.uk/publication/'
                    ]);
                    $this->taxonomy = 'category';
                    $result = $this->documentContainerTaxonomyLinks->pointToDocumentSearch($this->termlink, $this->term, $this->taxonomy);
                    expect($result)->to->equal('https://england.nhs.uk/publication/?filter-category=term+slug');
                });
            });
        });
    });
});
