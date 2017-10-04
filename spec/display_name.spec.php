<?php

describe(\NHSEngland\DisplayName::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->displayName = new \NHSEngland\DisplayName();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->displayName)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds filters', function () {
            \WP_Mock::expectFilterAdded('the_author', [$this->displayName, 'theAuthor']);
            \WP_Mock::expectFilterAdded('get_the_author_display_name', [$this->displayName, 'getTheAuthorDisplayName'], 10, 2);
            $this->displayName->register();
        });
    });

    describe('->theAuthor()', function () {
        context('is not in admin pages', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('is_admin', [
                    'return' => false,
                ]);
            });

            it('returns input', function () {
                expect($this->displayName->theAuthor('bob'))->to->equal('bob');
            });
        });

        context('is in admin pages', function () {
            beforeEach(function () {
                \WP_Mock::wpFunction('is_admin', [
                    'return' => true,
                ]);
            });


            it('returns user_login value (1)', function () {
                \WP_Mock::wpFunction('esc_html', [
                    'args' => ['alice'],
                    'return_arg' => 0,
                ]);

                global $authordata;

                $authordata = (object) [
                    'user_login' => 'alice',
                ];

                expect($this->displayName->theAuthor('bob'))->to->equal('alice');
            });

            it('returns user_login value (2)', function () {
                \WP_Mock::wpFunction('esc_html', [
                    'args' => ['eve'],
                    'return_arg' => 0,
                ]);

                global $authordata;

                $authordata = (object) [
                    'user_login' => 'eve',
                ];

                expect($this->displayName->theAuthor('bob'))->to->equal('eve');
            });

            // Some functions (such as the_author()) echo the value returned by this, so let's make sure it's safe to do that
            it('escapes its output', function () {
                \WP_Mock::wpFunction('esc_html', [
                    'args' => ['<script>'],
                    'return' => '&lt;script&gt;',
                ]);

                global $authordata;

                $authordata = (object) [
                    'user_login' => '<script>',
                ];

                expect($this->displayName->theAuthor('bob'))->to->equal('&lt;script&gt;');
            });
        });
    });

    describe('->getTheAuthorDisplayName()', function () {
        context('is not in admin pages', function () {
            it('returns input', function () {
                \WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => false
                ]);
                $username = "Arthur";
                $user_id = 12;
                $result = $this->displayName->getTheAuthorDisplayName($username, $user_id);
                expect($result)->to->equal($username);
            });
        });
        context('is in admin pages', function () {
            it('appends the nickname to the input value', function () {
                \WP_Mock::wpFunction('is_admin', [
                    'times' => 1,
                    'return' => true
                ]);
                \WP_Mock::wpFunction('get_user_meta', [
                    'times' => 1,
                    'args' => [
                        123,
                        'nickname',
                        true
                    ],
                    'return' => 'Artie'
                ]);
                $username = "Arthur Conan Doyle";
                $user_id = 123;
                $result = $this->displayName->getTheAuthorDisplayName($username, $user_id);
                expect($result)->to->equal("Arthur Conan Doyle (Artie)");
            });
        });
    });
});
