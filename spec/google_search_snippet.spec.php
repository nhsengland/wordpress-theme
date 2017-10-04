<?php

describe(\NHSEngland\GoogleSearchSnippet::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->helpers = \Mockery::mock(\Dxw\Iguana\Theme\Helpers::class);
        $this->googleSearchSnippet = new \NHSEngland\GoogleSearchSnippet($this->helpers);
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->googleSearchSnippet)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('registers helper function', function () {
            $this->helpers->shouldReceive('registerFunction')
                ->once()
                ->with('prependDate', [$this->googleSearchSnippet, 'prependDate']);
            $this->googleSearchSnippet->register();
        });
    });

    describe('->prependDate()', function () {
        $this->item = [];
        it('returns string if begins with date (single digit day)', function () {
            $result = $this->googleSearchSnippet->prependDate('Feb 1, 2016 something happened', $this->item);
            expect($result)->to->equal('Feb 1, 2016 something happened');
        });
        it('returns string if begins with date (double digit day)', function () {
            $result = $this->googleSearchSnippet->prependDate('Feb 21, 2016 something happened', $this->item);
            expect($result)->to->equal('Feb 21, 2016 something happened');
        });
        context('snippet does not begin with date', function () {
            context('metatag pubdate not available', function () {
                it('just returns the snippet', function () {
                    $this->item = [
                        'pagemap' => [
                            'metatags' => [
                                [
                                    'viewport' => 'foo',
                                    'category' => 'bar',
                                ]
                            ]
                        ]
                    ];
                    $result = $this->googleSearchSnippet->prependDate('dfoo', $this->item);
                    expect($result)->to->equal('dfoo');
                });
            });

            context('there is a date later in the snippet', function () {
                it('still prepends the pubdate', function () {
                    $this->item = [
                        'pagemap' => [
                            'metatags' => [
                                [
                                    'viewport' => 'foo',
                                    'category' => 'bar',
                                    'pubdate' => '20170201'
                                ]
                            ]
                        ]
                    ];
                    $result = $this->googleSearchSnippet->prependDate('how Feb 1, 2016', $this->item);
                    expect($result)->to->equal('Feb 1, 2017 ... how Feb 1, 2016');
                });
            });
            context('snippet already starts with ellipsis', function () {
                it('prepends the pubdate with space', function () {
                    $this->item = [
                        'pagemap' => [
                            'metatags' => [
                                [
                                    'viewport' => 'foo',
                                    'category' => 'bar',
                                    'pubdate' => '20170201'
                                ]
                            ]
                        ]
                    ];
                    $result = $this->googleSearchSnippet->prependDate('... dfoo', $this->item);
                    expect($result)->to->equal('Feb 1, 2017 ... dfoo');
                });
            });
            context('snippet contains ellipsis later in content', function () {
                it('prepends the pubdate with ellipsis', function () {
                    $this->item = [
                        'pagemap' => [
                            'metatags' => [
                                [
                                    'viewport' => 'foo',
                                    'category' => 'bar',
                                    'pubdate' => '20170201'
                                ]
                            ]
                        ]
                    ];
                    $result = $this->googleSearchSnippet->prependDate('dfoo ...', $this->item);
                    expect($result)->to->equal('Feb 1, 2017 ... dfoo ...');
                });
            });
            it('prepends the pubdate with ellipsis', function () {
                $this->item = [
                    'pagemap' => [
                        'metatags' => [
                            [
                                'viewport' => 'foo',
                                'category' => 'bar',
                                'pubdate' => '20170201'
                            ]
                        ]
                    ]
                ];
                $result = $this->googleSearchSnippet->prependDate('dfoo', $this->item);
                expect($result)->to->equal('Feb 1, 2017 ... dfoo');
            });
        });
    });

    describe('->getPubDate()', function () {
        context('no pagemap', function () {
            it('returns null', function () {
                $this->item = [
                    'a' => 1,
                    'b' => 2
                ];
                $result = $this->googleSearchSnippet->getPubDate($this->item);
                expect($result)->to->equal(null);
            });
        });
        context('no pubdate', function () {
            it('returns null', function () {
                $this->item = [
                    'pagemap' => [
                        'metatags' => [
                            [
                                'viewport' => 'foo',
                                'category' => 'bar',
                            ]
                        ]
                    ]
                ];
                $result = $this->googleSearchSnippet->getPubDate($this->item);
                expect($result)->to->equal(null);
            });
        });
        context('pubdate exists in first metatags array', function () {
            it('returns the pubdate', function () {
                $this->item = [
                    'pagemap' => [
                        'metatags' => [
                            [
                                'viewport' => 'foo',
                                'category' => 'bar',
                                'pubdate' => 'foobar'
                            ]
                        ]
                    ]
                ];
                $result = $this->googleSearchSnippet->getPubDate($this->item);
                expect($result)->to->equal('foobar');
            });
        });
        context('pubdate exists in second metatags array', function () {
            it('returns the pubdate', function () {
                $this->item = [
                    'pagemap' => [
                        'metatags' => [
                            [
                                'viewport' => 'foo',
                                'category' => 'bar',
                            ],
                            [
                                'viewport' => 'foo',
                                'category' => 'bar',
                                'pubdate' => 'foobar'
                            ]
                        ]
                    ]
                ];
                $result = $this->googleSearchSnippet->getPubDate($this->item);
                expect($result)->to->equal('foobar');
            });
        });
    });

    it('has Date Regex Constant', function () {
        expect(\NHSEngland\GoogleSearchSnippet::DATE_FORMAT)->to->equal('/^[A-Z][a-z]{2} \d{1,2}, \d{4}/');
    });
});
