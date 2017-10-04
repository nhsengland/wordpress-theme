<?php

describe(\NHSEngland\RemoveCommentAuthorLink::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->removeCommentAuthorLink = new \NHSEngland\RemoveCommentAuthorLink();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->removeCommentAuthorLink)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('registers a filter', function () {
            \WP_Mock::expectFilterAdded('get_comment_author_url', [$this->removeCommentAuthorLink, 'getCommentAuthorUrl']);
            $this->removeCommentAuthorLink->register();
        });
    });

    describe('->getCommentAuthorUrl()', function () {
        it('returns $author', function () {
            $return = $this->removeCommentAuthorLink->getCommentAuthorUrl();
            expect($return)->to->equal(null);
        });
    });
});
