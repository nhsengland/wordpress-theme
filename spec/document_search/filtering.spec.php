<?php

describe(\NHSEngland\DocumentSearch\Filtering::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        $this->keywordField = \Mockery::mock(\NHSEngland\DocumentSearch\KeywordField::class);
        $this->categoryField = \Mockery::mock(\NHSEngland\DocumentSearch\CategoryField::class);
        $this->publicationField = \Mockery::mock(\NHSEngland\DocumentSearch\PublicationField::class);
        $this->dateFromField = \Mockery::mock(\NHSEngland\DocumentSearch\DateFromField::class);
        $this->dateToField = \Mockery::mock(\NHSEngland\DocumentSearch\DateToField::class);
        $this->filtering = new \NHSEngland\DocumentSearch\Filtering(
            $this->keywordField,
            $this->categoryField,
            $this->publicationField,
            $this->dateFromField,
            $this->dateToField
        );
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->filtering)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds pre_get_posts action', function () {
            \WP_Mock::expectActionAdded('pre_get_posts', [$this->filtering, 'preGetPosts']);
            $this->filtering->register();
        });
    });

    describe('->preGetPosts()', function () {
        beforeEach(function () {
            $this->query = \Mockery::mock(\WP_Query::class);
        });

        context('is on a WP admin screen', function () {
            it('does nothing', function () {
                WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => true
                ]);
                $this->filtering->preGetPosts($this->query);
            });
        });

        context('post_type is not document or blog', function () {
            beforeEach(function () {
                WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false
                ]);
                $this->query->shouldReceive('get')->with('post_type')->andReturn(null);
                $this->query->shouldReceive('is_home')
                    ->once()
                    ->andReturn(false);
            });

            it('does nothing', function () {
                $this->filtering->preGetPosts($this->query);
            });
        });

        context('query is not home', function () {
            it('does nothing', function () {
                WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false
                ]);
                $this->query->shouldReceive('get')->with('post_type')->andReturn(null);
                $this->query->shouldReceive('is_home')
                    ->once()
                    ->andReturn(false);
                $this->filtering->preGetPosts($this->query);
            });
        });

        context('query is not main', function () {
            it('does nothing', function () {
                WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false
                ]);
                $this->query->shouldReceive('get')->with('post_type')->andReturn('page');
                $this->query->shouldReceive('is_home')
                    ->once()
                    ->andReturn(true);
                $this->query->shouldReceive('is_main_query')
                    ->once()
                    ->andReturn(false);
                $this->filtering->preGetPosts($this->query);
            });
        });

        context('post_type is document', function () {
            beforeEach(function () {
                WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false
                ]);
                $this->query->shouldReceive('get')->with('post_type')->andReturn('document');
            });

            it('calls modifyQuery on each filter', function () {
                $this->keywordField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->categoryField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->publicationField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->dateFromField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->dateToField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->filtering->preGetPosts($this->query);
            });
        });

        context('post_type is blog', function () {
            beforeEach(function () {
                WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false
                ]);
                $this->query->shouldReceive('get')->with('post_type')->andReturn('blog');
            });

            it('calls modifyQuery on each filter', function () {
                $this->keywordField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->categoryField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->publicationField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->dateFromField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->dateToField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->filtering->preGetPosts($this->query);
            });
        });

        context('query is home and main query', function () {
            beforeEach(function () {
                WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false
                ]);
                $this->query->shouldReceive('get')->with('post_type')->andReturn(null);
                $this->query->shouldReceive('is_home')
                    ->once()
                    ->andReturn(true);
                $this->query->shouldReceive('is_main_query')
                    ->once()
                    ->andReturn(true);
            });

            it('calls modifyQuery on each filter', function () {
                $this->keywordField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->categoryField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->publicationField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->dateFromField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->dateToField->shouldReceive('modifyQuery')->with($this->query)->once();
                $this->filtering->preGetPosts($this->query);
            });
        });
    });
});
