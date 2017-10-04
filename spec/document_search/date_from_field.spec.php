<?php

describe(\NHSEngland\DocumentSearch\DateFromField::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        \WP_Mock::wpFunction('esc_html', [
            'return' => function ($string) {
                return '-'.$string.'-';
            },
        ]);
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
            $this->field = new \NHSEngland\DocumentSearch\DateFromField($get);
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
                    '<label for="_filter-date-from_">-From-</label>'.
                    '<input type="date" id="_filter-date-from_" name="_filter-date-from_" value="">'
                );
            });
        });

        context('when there is an invalid value set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-date-from' => 'meow',
                ]);
            });

            it('returns plain HTML', function () {
                expect($this->field->renderHTML())->to->equal(
                    '<label for="_filter-date-from_">-From-</label>'.
                    '<input type="date" id="_filter-date-from_" name="_filter-date-from_" value="">'
                );
            });
        });

        context('when there is a sneaky value set', function () {
            it('returns plain HTML (a)', function () {
                $this->initField([
                    'filter-date-from' => 'meow2017-01-01',
                ]);

                expect($this->field->renderHTML())->to->equal(
                    '<label for="_filter-date-from_">-From-</label>'.
                    '<input type="date" id="_filter-date-from_" name="_filter-date-from_" value="">'
                );
            });

            it('returns plain HTML (b)', function () {
                $this->initField([
                    'filter-date-from' => '2017-01-01meow',
                ]);

                expect($this->field->renderHTML())->to->equal(
                    '<label for="_filter-date-from_">-From-</label>'.
                    '<input type="date" id="_filter-date-from_" name="_filter-date-from_" value="">'
                );
            });
        });

        context('when there is a vaild value set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-date-from' => '2017-01-01',
                ]);
            });

            it('returns HTML with persisted value', function () {
                expect($this->field->renderHTML())->to->equal(
                    '<label for="_filter-date-from_">-From-</label>'.
                    '<input type="date" id="_filter-date-from_" name="_filter-date-from_" value="_2017-01-01_">'
                );
            });
        });
    });

    describe('->modifyQuery()', function () {
        beforeEach(function () {
            $this->wpQuery = \Mockery::mock(\WP_Query::class, function ($mock) {
                $mock->shouldReceive('get')->with('date_query')->andReturn('');
            });
        });

        context('when there is no value set', function () {
            beforeEach(function () {
                $this->initField([
                ]);
            });

            it('does not set s parameter', function () {
                $this->wpQuery->shouldReceive('set')->never();
                $this->field->modifyQuery($this->wpQuery);
            });
        });

        context('when there is an invalid value set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-date-from' => 'meow',
                ]);
            });

            it('does not set s parameter', function () {
                $this->wpQuery->shouldReceive('set')->never();
                $this->field->modifyQuery($this->wpQuery);
            });
        });

        context('when there is a value set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-date-from' => '2017-01-01',
                ]);
            });

            it('sets s parameter', function () {
                $this->wpQuery->shouldReceive('set')->with('date_query', [
                    'inclusive' => true,
                    'after' => '2017-01-01',
                ])->once();
                $this->field->modifyQuery($this->wpQuery);
            });
        });
    });
});
