<?php

describe(\NHSEngland\DocumentSearch\PublicationField::class, function () {
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

        \WP_Mock::wpFunction('esc_html', [
            'return' => function ($string) {
                if ($string === '') {
                    return '';
                }
                return '-'.$string.'-';
            },
        ]);

        $this->initField = function (array $array) {
            $get = new \Dxw\Iguana\Value\Get($array);
            $this->field = new \NHSEngland\DocumentSearch\PublicationField($get);
        };
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is a SearchField', function () {
        $this->initField([]);
        expect($this->field)->to->be->instanceof(\NHSEngland\DocumentSearch\SearchField::class);
    });

    describe('->renderHTML()', function () {
        beforeEach(function () {
            \WP_Mock::wpFunction('get_terms', [
                'args' => [[
                    'taxonomy' => 'publication-type',
                    'hide_empty' => true,
                ]],
                'return' => [
                    (object)[
                        'term_id' => 3,
                        'name' => 'Topic 1',
                        'slug' => 'topic-1',
                        'parent' => 0,
                    ],
                    (object)[
                        'term_id' => 4,
                        'name' => 'Topic 2',
                        'slug' => 'topic-2',
                        'parent' => 0,
                    ],
                ],
            ]);
        });

        context('when value is unset', function () {
            beforeEach(function () {
                $this->initField([]);
            });

            it('returns HTML', function () {
                expect($this->field->renderHTML())->to->equal(
                    '<label for="_filter-publication_">-Publication type-</label>'.
                    '<select id="_filter-publication_" name="_filter-publication_">'.
                    '<option value="">Select publication type</option>'.
                    '<option value="_topic-1_">-Topic 1-</option>'.
                    '<option value="_topic-2_">-Topic 2-</option>'.
                    '</select>'
                );
            });
        });

        context('when value is defined', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-publication' => 'topic-2',
                ]);
            });

            it('returns HTML with value pre-selected', function () {
                expect($this->field->renderHTML())->to->equal(
                    '<label for="_filter-publication_">-Publication type-</label>'.
                    '<select id="_filter-publication_" name="_filter-publication_">'.
                    '<option value="">Select publication type</option>'.
                    '<option value="_topic-1_">-Topic 1-</option>'.
                    '<option value="_topic-2_" selected>-Topic 2-</option>'.
                    '</select>'
                );
            });
        });
    });

    describe('->modifyQuery()', function () {
        beforeEach(function () {
            \WP_Mock::wpFunction('get_terms', [
                'args' => [[
                    'taxonomy' => 'publication-type',
                    'hide_empty' => true,
                ]],
                'return' => [
                    (object)[
                        'term_id' => 3,
                        'name' => 'Topic 1',
                        'slug' => 'topic-1',
                        'parent' => 0,
                    ],
                    (object)[
                        'term_id' => 4,
                        'name' => 'Topic 2',
                        'slug' => 'topic-2',
                        'parent' => 0,
                    ],
                ],
            ]);

            $this->query = \Mockery::mock(\WP_Query::class);
        });

        context('value is unset', function () {
            beforeEach(function () {
                $this->initField([
                ]);
            });

            it('does nothing', function () {
                $this->query->shouldReceive('set')->never();
                $this->field->modifyQuery($this->query);
            });
        });

        context('value is blank', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-publication' => '',
                ]);
            });

            it('does nothing', function () {
                $this->query->shouldReceive('set')->never();
                $this->field->modifyQuery($this->query);
            });
        });

        context('value is set to an unknown value', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-publication' => 'not-a-topic',
                ]);

                \WP_Mock::wpFunction('get_terms', [
                    'args' => [[
                        'taxonomy' => 'publication-type',
                        'hide_empty' => true,
                        'slug' => 'not-a-topic',
                    ]],
                    'return' => [],
                ]);
            });

            it('does nothing', function () {
                $this->query->shouldReceive('set')->never();
                $this->field->modifyQuery($this->query);
            });
        });

        context('value is set', function () {
            beforeEach(function () {
                $this->initField([
                    'filter-publication' => 'topic-2',
                ]);

                \WP_Mock::wpFunction('get_terms', [
                    'args' => [[
                        'taxonomy' => 'publication-type',
                        'hide_empty' => true,
                        'slug' => 'topic-2',
                    ]],
                    'return' => [true],
                ]);
            });

            context('with no current taxonomy query', function () {
                beforeEach(function () {
                    $this->query->shouldReceive('get')->with('tax_query')->andReturn('');
                });

                it('sets the query', function () {
                    $this->query->shouldReceive('set')->with('tax_query', [
                        'relation' => 'AND',
                        [
                            'taxonomy' => 'publication-type',
                            'field' => 'slug',
                            'terms' => ['topic-2'],
                        ],
                    ])->once();
                    $this->field->modifyQuery($this->query);
                });
            });

            context('with existing taxonomy query', function () {
                beforeEach(function () {
                    $this->query->shouldReceive('get')->with('tax_query')->andReturn([
                        'relation' => 'AND',
                        [
                            'taxonomy' => 'topic',
                            'field' => 'slug',
                            'terms' => ['topic-2'],
                        ],
                    ]);
                });

                it('amends the query', function () {
                    $this->query->shouldReceive('set')->with('tax_query', [
                        'relation' => 'AND',
                        [
                            'taxonomy' => 'topic',
                            'field' => 'slug',
                            'terms' => ['topic-2'],
                        ],
                        [
                            'taxonomy' => 'publication-type',
                            'field' => 'slug',
                            'terms' => ['topic-2'],
                        ],
                    ])->once();
                    $this->field->modifyQuery($this->query);
                });
            });
        });
    });
});
