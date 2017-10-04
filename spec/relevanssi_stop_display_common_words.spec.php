<?php

describe(\NHSEngland\RelevanssiStopDisplayCommonWords::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->relevanssiStopDisplayCommonWords = new \NHSEngland\RelevanssiStopDisplayCommonWords();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->relevanssiStopDisplayCommonWords)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the filter', function () {
            WP_Mock::expectFilterAdded('relevanssi_display_common_words', [$this->relevanssiStopDisplayCommonWords, 'stopDisplayCommonWords'], 10, 1);
            $this->relevanssiStopDisplayCommonWords->register();
        });
    });

    describe('->stopDisplayCommonWords', function () {
        it('returns false', function () {
            $result = $this->relevanssiStopDisplayCommonWords->stopDisplayCommonWords();
            expect($result)->to->equal(false);
        });
    });
});
