<?php

describe(\NHSEngland\GoogleSearchResult::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    describe('->__construct()', function () {
        it('stores arguments as public parameters', function () {
            $result = new \NHSEngland\GoogleSearchResult(
                'resolution',
                'q',
                'pagination',
                'results',
                'total_results'
            );

            expect($result->resolution)->to->equal('resolution');
            expect($result->q)->to->equal('q');
            expect($result->pagination)->to->equal('pagination');
            expect($result->results)->to->equal('results');
            expect($result->total_results)->to->equal('total_results');
        });
    });

    it('has resolution constants', function () {
        expect(\NHSEngland\GoogleSearchResult::RESOLUTION_ERROR)->to->equal(1);
        expect(\NHSEngland\GoogleSearchResult::RESOLUTION_SEARCH_PERFORMED)->to->equal(2);
        expect(\NHSEngland\GoogleSearchResult::RESOLUTION_NO_SEARCH_PERFORMED)->to->equal(3);
    });
});
