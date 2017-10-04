<?php

describe(\NHSEngland\DisableTagsArchive::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->disableTagsArchive = new \NHSEngland\DisableTagsArchive();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->disableTagsArchive)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });
    describe('->register()', function () {
        it('adds an action', function () {
            \WP_Mock::expectActionAdded('pre_get_posts', [$this->disableTagsArchive, 'blockTagsArchive']);
            $this->disableTagsArchive->register();
        });
    });
    describe('->blockTagsArchive()', function () {
        context('not a Tags archive', function () {
            it('does nothing', function () {
                $this->wpQuery = \Mockery::mock(\WP_Query::class, function ($mock) {
                    $mock->shouldReceive('set_404')->times(0);
                });
                \WP_Mock::wpFunction('is_tag', [
                    'times' => 1,
                    'return' => false,
                ]);
                $this->disableTagsArchive->blockTagsArchive($this->wpQuery);
            });
        });
        context('is a Tags archive', function () {
            it('sets 404', function () {
                $this->wpQuery = \Mockery::mock(\WP_Query::class, function ($mock) {
                    $mock->shouldReceive('set_404')->times(1);
                });
                \WP_Mock::wpFunction('is_tag', [
                    'times' => 1,
                    'return' => true,
                ]);
                \WP_Mock::wpFunction('status_header', [
                    'times' => 1,
                    'args' => '404',
                ]);
                $this->disableTagsArchive->blockTagsArchive($this->wpQuery);
            });
        });
    });
});
