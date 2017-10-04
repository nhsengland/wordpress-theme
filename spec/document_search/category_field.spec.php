<?php

describe(\NHSEngland\DocumentSearch\CategoryField::class, function () {
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
            $this->field = new \NHSEngland\DocumentSearch\CategoryField($get);
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
        context('priority Topics is empty, get_terms returns an array', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('get_field', [
                    'times' => 1,
                    'args' => [
                        'priority_topics',
                        'options'
                    ],
                    'return' => [
                    ]
                ]);
                \WP_Mock::wpFunction('get_terms', [
                    'args' => [[
                        'taxonomy' => 'category',
                        'hide_empty' => true,
                        'exclude' => []
                    ]],
                    'return' => [
                        (object)[
                            'term_id' => 3,
                            'name' => 'Category 1',
                            'slug' => 'category-1',
                            'parent' => 0,
                        ],
                        (object)[
                            'term_id' => 4,
                            'name' => 'Category 2',
                            'slug' => 'category-2',
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
                        '<label for="_filter-category_">-Topic-</label>'.
                        '<select id="_filter-category_" name="_filter-category_">'.
                        '<option value="">Select topic</option>'.
                        '<option value="_category-1_">-Category 1-</option>'.
                        '<option value="_category-2_">-Category 2-</option>'.
                        '</select>'
                    );
                });
            });

            context('when value is defined', function () {
                beforeEach(function () {
                    $this->initField([
                        'filter-category' => 'category-2',
                    ]);
                });

                it('returns HTML with value pre-selected', function () {
                    expect($this->field->renderHTML())->to->equal(
                        '<label for="_filter-category_">-Topic-</label>'.
                        '<select id="_filter-category_" name="_filter-category_">'.
                        '<option value="">Select topic</option>'.
                        '<option value="_category-1_">-Category 1-</option>'.
                        '<option value="_category-2_" selected>-Category 2-</option>'.
                        '</select>'
                    );
                });
            });
        });
        context('priority topics and get_terms return arrays', function () {
            beforeEach(function () {
                $this->priorityTopic1 = new stdClass();
                $this->priorityTopic2 = new stdClass();
                $this->priorityTopic1->slug = 'slug1';
                $this->priorityTopic1->name = 'name1';
                $this->priorityTopic1->term_id = 1;
                $this->priorityTopic2->slug = 'slug2';
                $this->priorityTopic2->name = 'name2';
                $this->priorityTopic2->term_id = 2;
                \WP_Mock::wpFunction('get_field', [
                    'times' => 1,
                    'args' => [
                        'priority_topics',
                        'options'
                    ],
                    'return' => [
                        $this->priorityTopic1,
                        $this->priorityTopic2
                    ]
                ]);
                \WP_Mock::wpFunction('get_terms', [
                    'args' => [[
                        'taxonomy' => 'category',
                        'hide_empty' => true,
                        'exclude' => [1, 2]
                    ]],
                    'return' => [
                        (object)[
                            'term_id' => 3,
                            'name' => 'Category 1',
                            'slug' => 'category-1',
                            'parent' => 0,
                        ],
                        (object)[
                            'term_id' => 4,
                            'name' => 'Category 2',
                            'slug' => 'category-2',
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
                        '<label for="_filter-category_">-Topic-</label>'.
                        '<select id="_filter-category_" name="_filter-category_">'.
                        '<option value="">Select topic</option>'.
                        '<optgroup label="Priority areas">' .
                        '<option value="_slug1_">-name1-</option>'.
                        '<option value="_slug2_">-name2-</option>'.
                        '</optgroup>' .
                        '<optgroup label="All topics">' .
                        '<option value="_category-1_">-Category 1-</option>'.
                        '<option value="_category-2_">-Category 2-</option>'.
                        '</optgroup>' .
                        '</select>'
                    );
                });
            });

            context('when value is defined', function () {
                beforeEach(function () {
                    $this->initField([
                        'filter-category' => 'category-2',
                    ]);
                });

                it('returns HTML with value pre-selected', function () {
                    expect($this->field->renderHTML())->to->equal(
                        '<label for="_filter-category_">-Topic-</label>'.
                        '<select id="_filter-category_" name="_filter-category_">'.
                        '<option value="">Select topic</option>'.
                        '<optgroup label="Priority areas">' .
                        '<option value="_slug1_">-name1-</option>'.
                        '<option value="_slug2_">-name2-</option>'.
                        '</optgroup>' .
                        '<optgroup label="All topics">' .
                        '<option value="_category-1_">-Category 1-</option>'.
                        '<option value="_category-2_" selected>-Category 2-</option>'.
                        '</optgroup>' .
                        '</select>'
                    );
                });
            });
        });

        context('get_terms returns an error', function () {
            beforeEach(function () {
                $wpError = \Mockery::mock(\WP_Error::class);
                \WP_Mock::wpFunction('get_terms', [
                    'return' => $wpError,
                ]);
            });

            it('raises a fatal error', function () {
                \WP_Mock::wpFunction('get_field', [
                    'times' => 1,
                    'args' => [
                        'priority_topics',
                        'options'
                    ]
                ]);
                expect(function () {
                    $this->field->renderHTML();
                })->to->throw(\ErrorException::class, 'get_terms returned an error');
            });
        });
    });

    describe('->modifyQuery()', function () {
        beforeEach(function () {
            \WP_Mock::wpFunction('get_terms', [
                'args' => [[
                    'taxonomy' => 'category',
                    'hide_empty' => true,
                ]],
                'return' => [
                    (object)[
                        'term_id' => 3,
                        'name' => 'Category 1',
                        'slug' => 'category-1',
                        'parent' => 0,
                    ],
                    (object)[
                        'term_id' => 4,
                        'name' => 'Category 2',
                        'slug' => 'category-2',
                        'parent' => 0,
                    ],
                ],
            ]);

            $this->query = \Mockery::mock(\WP_Query::class, function ($mock) {
                $mock->shouldReceive('get')->with('tax_query')->andReturn('');
            });
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
                    'filter-category' => '',
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
                    'filter-category' => 'not-a-category',
                ]);

                \WP_Mock::wpFunction('get_terms', [
                    'args' => [[
                        'taxonomy' => 'category',
                        'hide_empty' => true,
                        'slug' => 'not-a-category',
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
                    'filter-category' => 'category-2',
                ]);

                \WP_Mock::wpFunction('get_terms', [
                    'args' => [[
                        'taxonomy' => 'category',
                        'hide_empty' => true,
                        'slug' => 'category-2',
                    ]],
                    'return' => [true],
                ]);
            });

            it('sets the query', function () {
                $this->query->shouldReceive('set')->with('tax_query', [
                    'relation' => 'AND',
                    [
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => ['category-2'],
                    ],
                ])->once();
                $this->field->modifyQuery($this->query);
            });
        });
    });
});
