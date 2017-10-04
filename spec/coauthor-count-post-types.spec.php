<?php

describe(\NHSEngland\CoAuthorCountPostTypes::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->coAuthorCountPostTypes = new \NHSEngland\CoAuthorCountPostTypes();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->coAuthorCountPostTypes)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the action', function () {
            WP_Mock::expectActionAdded('coauthors_count_published_post_types', [$this->coAuthorCountPostTypes, 'coauthors_count_published_post_types']);
            $this->coAuthorCountPostTypes->register();
        });
    });

    describe('->coauthors_count_published_post_types', function () {
        it('returns array of blog and post', function () {
            $result = $this->coAuthorCountPostTypes->coauthors_count_published_post_types();
            expect($result)->to->equal(['blog', 'post']);
        });
    });
});
