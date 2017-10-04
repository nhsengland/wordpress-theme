<?php

describe(\NHSEngland\DisableAttachmentComments::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->disableAttachmentComments = new \NHSEngland\DisableAttachmentComments();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->disableAttachmentComments)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the action and filter', function () {
            WP_Mock::expectActionAdded('init', [$this->disableAttachmentComments, 'removeCommentSupport']);
            WP_Mock::expectFilterAdded('comments_open', [$this->disableAttachmentComments, 'commentsOpen'], 10, 2);
            $this->disableAttachmentComments->register();
        });
    });

    describe('->removeCommentSupport()', function () {
        it('removes attachment support for comments', function () {
            WP_Mock::wpFunction('remove_post_type_support', [
                'times' => 1,
                'args' => [
                    'attachment',
                    'comments'
                ]
            ]);
            $this->disableAttachmentComments->removeCommentSupport();
        });
    });

    describe('->commentsOpen()', function () {
        context('post is not of type attachment', function () {
            it('returns input value', function () {
                WP_Mock::wpFunction('get_post_type', [
                    'times' => 1,
                    'args' => 123,
                    'return' => 'post'
                ]);
                $result = $this->disableAttachmentComments->commentsOpen('theResult', 123);
                expect($result)->to->equal('theResult');
            });
        });
        context('post is of type attachment', function () {
            it('returns false', function () {
                WP_Mock::wpFunction('get_post_type', [
                    'times' => 1,
                    'args' => 1234,
                    'return' => 'attachment'
                ]);
                $result = $this->disableAttachmentComments->commentsOpen('theResult', 1234);
                expect($result)->to->equal(false);
            });
        });
    });
});
