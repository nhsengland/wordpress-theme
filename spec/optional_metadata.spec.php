<?php

describe(\NHSEngland\OptionalMetadata::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->optionalMetadata = new \NHSEngland\OptionalMetadata();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->optionalMetadata)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds actions', function () {
            \WP_Mock::expectFilterAdded('manage_post_posts_columns', [$this->optionalMetadata, 'managePostsColumns']);
            \WP_Mock::expectFilterAdded('manage_page_posts_columns', [$this->optionalMetadata, 'managePostsColumns']);
            \WP_Mock::expectFilterAdded('manage_media_columns', [$this->optionalMetadata, 'managePostsColumns']);
            \WP_Mock::expectActionAdded('manage_posts_custom_column', [$this->optionalMetadata, 'managePostsCustomColumn'], 10, 2);
            \WP_Mock::expectActionAdded('manage_pages_custom_column', [$this->optionalMetadata, 'managePostsCustomColumn'], 10, 2);
            \WP_Mock::expectActionAdded('manage_media_custom_column', [$this->optionalMetadata, 'managePostsCustomColumn'], 10, 2);
            \WP_Mock::expectFilterAdded('posts_where', [$this->optionalMetadata, 'postsWhere'], 10, 2);
            $this->optionalMetadata->register();
        });
    });

    describe('->managePostsColumns()', function () {
        it('returns expected output', function () {
            $out = $this->optionalMetadata->managePostsColumns(['meow']);

            expect($out)->to->equal([
                'meow',
                'owner' => 'Owner',
                'description' => 'Description',
                'gateway_ref' => 'Gateway Ref',
                'pcc_reference' => 'PCC Reference',
            ]);
        });
    });

    describe('->managePostsCustomColumn()', function () {
        it('works', function () {
            $matrix = [
                [5, 'owner', 'Tom Tester', true],
                [6, 'description', 'Blah blah blah', true],
                [6, 'gateway_ref', 'Abc', true],
                [6, 'pcc_reference', 'Xyz', true],
                [6, 'other_field', '', false],
            ];

            foreach ($matrix as $row) {
                if ($row[3]) {
                    \WP_Mock::wpFunction('get_post_meta', [
                        'args' => [$row[0], $row[1], true],
                        'return' => $row[2],
                    ]);
                }

                ob_start();
                $this->optionalMetadata->managePostsCustomColumn($row[1], $row[0]);
                $value = ob_get_clean();

                expect($value)->to->equal($row[2]);
            }
        });
    });

    describe('->postsWhere()', function () {
        beforeEach(function () {
            // Set up $wpdb

            $GLOBALS['wpdb'] = \Mockery::mock(\WP_DB::class, function ($mock) {
                $mock->shouldReceive('esc_like')
                ->andReturnUsing(function ($s) {
                    $s = str_replace('%', '\\%', $s);
                    $s = str_replace('_', '\\_', $s);

                    return $s;
                });

                // This is super-hacky
                $mock->shouldReceive('prepare')
                ->andReturnUsing(function () {
                    $args = func_get_args();
                    $fmt = $args[0];
                    $values = [];

                    foreach (array_slice($args, 1) as $val) {
                        if (is_string($val)) {
                            $val = "'".$val."'";
                        }
                        $values[] = $val;
                    }

                    return call_user_func_array('sprintf', array_merge([$fmt], $values));
                });
            });

            $GLOBALS['wpdb']->posts = 'wp_posts';
            $GLOBALS['wpdb']->postmeta = 'wp_postmeta';

            $this->is_search = true;

            $thus = $this;
            $this->getQueryMock = function ($post_type, $s) use ($thus) {
                return \Mockery::mock(\WP_Query::class, function ($mock) use ($post_type, $s, $thus) {
                    $mock->shouldReceive('get')
                    ->withArgs(['post_type'])
                    ->andReturn($post_type);

                    $mock->shouldReceive('get')
                    ->withArgs(['s'])
                    ->andReturn($s);

                    $mock->shouldReceive('is_search')
                    ->andReturn($thus->is_search);
                });
            };
        });

        context('is_admin is false', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('is_admin', [
                    'return' => false,
                ]);
            });

            it('NotAdmin', function () {
                expect(
                    $this->optionalMetadata->postsWhere('1=1 AND foo=bar', $this->getQueryMock('post', 'zzz'))
                )->to->equal(
                    '1=1 AND foo=bar'
                );
            });
        });

        context('is_admin is true', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('is_admin', [
                    'return' => true,
                ]);
            });

            context('with custom table names', function () {
                beforeEach(function () {
                    $GLOBALS['wpdb']->posts = 'cat';
                    $GLOBALS['wpdb']->postmeta = 'dog';
                });

                it('4', function () {
                    expect(
                        $this->optionalMetadata->postsWhere('1=1 AND foo=bar', $this->getQueryMock('attachment', 'meow'))
                    )->to->equal(
                        "1=1 AND foo=bar OR (cat.post_type = 'attachment' AND (cat.post_status = 'publish' OR cat.post_status = 'acf-disabled' OR cat.post_status = 'future' OR cat.post_status = 'draft' OR cat.post_status = 'pending' OR cat.post_status = 'private' OR cat.post_status = 'inherit') AND (cat.ID IN (SELECT post_id FROM dog WHERE meta_key='owner' AND meta_value LIKE '%meow%') OR cat.ID IN (SELECT post_id FROM dog WHERE meta_key='description' AND meta_value LIKE '%meow%') OR cat.ID IN (SELECT post_id FROM dog WHERE meta_key='gateway_ref' AND meta_value LIKE '%meow%') OR cat.ID IN (SELECT post_id FROM dog WHERE meta_key='pcc_reference' AND meta_value LIKE '%meow%')))"
                    );
                });
            });

            context('when ->is_search() returns false', function () {
                beforeEach(function () {
                    $this->is_search = false;
                });

                it('NotSearch', function () {
                    expect(
                        $this->optionalMetadata->postsWhere('1=1 AND foo=bar', $this->getQueryMock('post', 'zzz'))
                    )->to->equal(
                        '1=1 AND foo=bar'
                    );
                });
            });

            it('1', function () {
                expect(
                    $this->optionalMetadata->postsWhere('1=1 AND foo=bar', $this->getQueryMock('post', 'zzz'))
                )->to->equal(
                    "1=1 AND foo=bar OR (wp_posts.post_type = 'post' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'acf-disabled' OR wp_posts.post_status = 'future' OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_status = 'private' OR wp_posts.post_status = 'inherit') AND (wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='owner' AND meta_value LIKE '%zzz%') OR wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='description' AND meta_value LIKE '%zzz%') OR wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='gateway_ref' AND meta_value LIKE '%zzz%') OR wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='pcc_reference' AND meta_value LIKE '%zzz%')))"
                );
            });

            it('2', function () {
                expect(
                    $this->optionalMetadata->postsWhere('1=1 AND foo=bar', $this->getQueryMock('attachment', 'zzz'))
                )->to->equal(
                    "1=1 AND foo=bar OR (wp_posts.post_type = 'attachment' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'acf-disabled' OR wp_posts.post_status = 'future' OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_status = 'private' OR wp_posts.post_status = 'inherit') AND (wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='owner' AND meta_value LIKE '%zzz%') OR wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='description' AND meta_value LIKE '%zzz%') OR wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='gateway_ref' AND meta_value LIKE '%zzz%') OR wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='pcc_reference' AND meta_value LIKE '%zzz%')))"
                );
            });

            it('3', function () {
                expect(
                    $this->optionalMetadata->postsWhere('1=1 AND foo=bar', $this->getQueryMock('attachment', 'meow'))
                )->to->equal(
                    "1=1 AND foo=bar OR (wp_posts.post_type = 'attachment' AND (wp_posts.post_status = 'publish' OR wp_posts.post_status = 'acf-disabled' OR wp_posts.post_status = 'future' OR wp_posts.post_status = 'draft' OR wp_posts.post_status = 'pending' OR wp_posts.post_status = 'private' OR wp_posts.post_status = 'inherit') AND (wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='owner' AND meta_value LIKE '%meow%') OR wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='description' AND meta_value LIKE '%meow%') OR wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='gateway_ref' AND meta_value LIKE '%meow%') OR wp_posts.ID IN (SELECT post_id FROM wp_postmeta WHERE meta_key='pcc_reference' AND meta_value LIKE '%meow%')))"
                );
            });

            it('PostTypePost', function () {
                expect(
                    $this->optionalMetadata->postsWhere('1=1', $this->getQueryMock('post', 'zzz'))
                )->to->not->equal(
                    '1=1'
                );
            });

            it('PostTypeAttachment', function () {
                expect(
                    $this->optionalMetadata->postsWhere('1=1', $this->getQueryMock('attachment', 'zzz'))
                )->to->not->equal(
                    '1=1'
                );
            });

            it('PostTypePage', function () {
                expect(
                    $this->optionalMetadata->postsWhere('1=1', $this->getQueryMock('page', 'zzz'))
                )->to->not->equal(
                    '1=1'
                );
            });

            it('PostTypeOther', function () {
                expect(
                    $this->optionalMetadata->postsWhere('1=1', $this->getQueryMock('cats', 'zzz'))
                )->to->equal(
                    '1=1'
                );
            });

            it('PostTypeMultiple', function () {
                expect(
                    $this->optionalMetadata->postsWhere('1=1', $this->getQueryMock(['post', 'page'], 'zzz'))
                )->to->equal(
                    '1=1'
                );
            });
        });
    });
});
