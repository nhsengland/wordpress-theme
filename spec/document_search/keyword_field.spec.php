<?php

describe(\NHSEngland\DocumentSearch\KeywordField::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        \WP_Mock::wpFunction('esc_attr', [
            'return' => function ($string) {
                if ($string === '') {
                    return '';
                }
                return '_'.$string.'_';
            },
        ]);

        $this->initField = function (array $array) {
            $get = new \Dxw\Iguana\Value\Get($array);
            $this->field = new \NHSEngland\DocumentSearch\KeywordField($get);
        };
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is a SearchField', function () {
        $this->initField([]);
        expect($this->field)->to->be->instanceof(\NHSEngland\DocumentSearch\SearchField::class);
    });

    describe('renderHTML', function () {
        context('when there is no value', function () {
            beforeEach(function () {
                $this->initField([]);
            });

            it('returns HTML', function () {
                expect($this->field->renderHTML())->to->equal(
                    '<label for="filter-keyword">Keyword</label>'.
                    '<input type="search" id="filter-keyword" name="filter-keyword" value="">'
                );
            });
        });

        context('when there is a value set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-keyword' => 'meow',
                ]);
            });

            it('returns HTML with persisted value', function () {
                expect($this->field->renderHTML())->to->equal(
                    '<label for="filter-keyword">Keyword</label>'.
                    '<input type="search" id="filter-keyword" name="filter-keyword" value="_meow_">'
                );
            });
        });
    });

    describe('->modifyQuery()', function () {
        beforeEach(function () {
            $this->wpQuery = \Mockery::mock(\WP_Query::class, function ($mock) {
            });
        });

        context('when there is no value set', function () {
            beforeEach(function () {
                $this->initField([
                ]);
            });

            it('sets s parameter to blank', function () {
                $this->wpQuery->shouldReceive('set')->with('s', '')->once();
                $this->field->modifyQuery($this->wpQuery);
            });
        });
        context('when there is a value set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-keyword' => 'meow',
                ]);
            });

            it('sets s parameter', function () {
                $this->wpQuery->shouldReceive('set')->with('s', 'meow')->once();
                $this->field->modifyQuery($this->wpQuery);
            });
        });
    });
});
