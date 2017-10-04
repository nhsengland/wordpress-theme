<?php

describe(\NHSEngland\DocumentSearch\DateToField::class, function () {
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
            $this->field = new \NHSEngland\DocumentSearch\DateToField($get);
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
                    '<label for="_filter-date-to_">-To-</label>'.
                    '<input type="date" id="_filter-date-to_" name="_filter-date-to_" value="">'
                );
            });
        });

        context('when there is an invalid value set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-date-to' => 'meow',
                ]);
            });

            it('returns plain HTML', function () {
                expect($this->field->renderHTML())->to->equal(
                    '<label for="_filter-date-to_">-To-</label>'.
                    '<input type="date" id="_filter-date-to_" name="_filter-date-to_" value="">'
                );
            });
        });

        context('when there is a sneaky value set', function () {
            it('returns plain HTML (a)', function () {
                $this->initField([
                    'filter-date-to' => 'meow2017-01-01',
                ]);

                expect($this->field->renderHTML())->to->equal(
                    '<label for="_filter-date-to_">-To-</label>'.
                    '<input type="date" id="_filter-date-to_" name="_filter-date-to_" value="">'
                );
            });

            it('returns plain HTML (b)', function () {
                $this->initField([
                    'filter-date-to' => '2017-01-01meow',
                ]);

                expect($this->field->renderHTML())->to->equal(
                    '<label for="_filter-date-to_">-To-</label>'.
                    '<input type="date" id="_filter-date-to_" name="_filter-date-to_" value="">'
                );
            });
        });

        context('when there is a vaild value set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-date-to' => '2017-01-01',
                ]);
            });

            it('returns HTML with persisted value', function () {
                expect($this->field->renderHTML())->to->equal(
                    '<label for="_filter-date-to_">-To-</label>'.
                    '<input type="date" id="_filter-date-to_" name="_filter-date-to_" value="_2017-01-01_">'
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

            it('does not set date_query parameter', function () {
                $this->wpQuery->shouldReceive('set')->never();
                $this->field->modifyQuery($this->wpQuery);
            });
        });

        context('when there is an invalid value set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-date-to' => 'meow',
                ]);
            });

            it('does not set date_query parameter', function () {
                $this->wpQuery->shouldReceive('set')->never();
                $this->field->modifyQuery($this->wpQuery);
            });
        });

        context('when there is a value set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-date-to' => '2017-01-01',
                ]);
            });

            context('when there is no pre-existing date query', function () {
                beforeEach(function () {
                    $this->wpQuery->shouldReceive('get')->with('date_query')->andReturn('');
                });

                it('sets date_query parameter', function () {
                    $this->wpQuery->shouldReceive('set')->with('date_query', [
                        'inclusive' => true,
                        'before' => '2017-01-01',
                    ])->once();
                    $this->field->modifyQuery($this->wpQuery);
                });
            });

            context('when there is a pre-existing date query', function () {
                beforeEach(function () {
                    $this->wpQuery->shouldReceive('get')->with('date_query')->andReturn([
                        'inclusive' => true,
                        'after' => '2007-01-01',
                    ]);
                });

                it('amends date_query parameter', function () {
                    $this->wpQuery->shouldReceive('set')->with('date_query', [
                        'inclusive' => true,
                        'after' => '2007-01-01',
                        'before' => '2017-01-01',
                    ])->once();
                    $this->field->modifyQuery($this->wpQuery);
                });
            });
        });
    });
});
