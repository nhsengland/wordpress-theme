<?php

describe(\NHSEngland\AuthorArchive::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->authorArchive = new \NHSEngland\AuthorArchive();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->authorArchive)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the action', function () {
            WP_Mock::expectActionAdded('pre_get_posts', [$this->authorArchive, 'showBlogPostsOnly']);
            $this->authorArchive->register();
        });
    });

    describe('->showBlogPostsOnly', function () {
        beforeEach(function () {
            $this->query = \Mockery::mock('stdClass');
        });

        context('is in wp-admin', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('is_admin', [
                    'args' => [],
                    'return' => true,
                ]);
            });

            it('does nothing', function () {
                $this->query->shouldReceive('set')
                ->never();
                $this->authorArchive->showBlogPostsOnly($this->query);
            });
        });

        context('is not in wp-admin', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('is_admin', [
                    'args' => [],
                    'return' => false,
                ]);
            });

            context('is not an author archive', function () {
                beforeEach(function () {
                    $this->query->shouldReceive('is_author')
                    ->once()
                    ->andReturn(false);
                });

                it('does nothing', function () {
                    $this->authorArchive->showBlogPostsOnly($this->query);
                });
            });

            context('is an author archive', function () {
                beforeEach(function () {
                    $this->query->shouldReceive('is_author')
                    ->once()
                    ->andReturn(true);
                });

                it('sets post_type to blog', function () {
                    $this->query->shouldReceive('set')
                    ->once()
                    ->with('post_type', 'blog');
                    $this->authorArchive->showBlogPostsOnly($this->query);
                });
            });
        });
    });
});
