<?php

describe(\NHSEngland\GuestAuthors::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->guestAuthors = new \NHSEngland\GuestAuthors();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->guestAuthors)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register', function () {
        it('adds the filter and action', function () {
            WP_Mock::expectFilterAdded('coauthors_auto_apply_template_tags', [$this->guestAuthors, 'coauthorsAutoApplyTemplateTags']);
            WP_Mock::expectActionAdded('init', [$this->guestAuthors, 'removeAuthorFilter'], 100);
            $this->guestAuthors->register();
        });
    });

    describe('->coauthorsAutoApplyTemplateTags', function () {
        it('returns true', function () {
            $result = $this->guestAuthors->coauthorsAutoApplyTemplateTags();
            expect($result)->to->equal(true);
        });
    });

    describe('->removeAuthorFilter', function () {
        it('removes the filter', function () {
            global $coauthors_plus_template_filters;
            $coauthors_plus_template_filters = 'foo';
            WP_Mock::wpFunction('remove_filter', [
                'times' => 1,
                'args' => [
                    'the_author', [
                        'foo',
                        'filter_the_author'
                    ]
                ]
            ]);
            $this->guestAuthors->removeAuthorFilter();
        });
    });
});
