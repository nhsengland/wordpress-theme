<?php

describe(\NHSEngland\DocumentContainerIcons::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();

        $this->helpers = \Mockery::mock(\Dxw\Iguana\Theme\Helpers::class, function ($mock) {
            $mock->shouldReceive('registerFunction');
        });
        $this->icons = new \NHSEngland\DocumentContainerIcons($this->helpers);
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->icons)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->__construct()', function () {
        it('registers a helper function', function () {
            $helpers = \Mockery::mock(\Dxw\Iguana\Theme\Helpers::class, function ($mock) {
                $mock->shouldReceive('registerFunction')
                ->with('thumbnailSrc', \Mockery::type('callable'))
                ->once();
            });
            new \NHSEngland\DocumentContainerIcons($helpers);
        });
    });

    describe('->register()', function () {
        it('adds post-update hooks etc', function () {
            \WP_Mock::expectActionAdded('wp_insert_post', [$this->icons, 'wpInsertPost']);

            \WP_Mock::expectActionAdded('admin_print_scripts', [$this->icons, 'adminPrintScripts']);

            $this->icons->register();
        });
    });

    describe('->wpInsertPost()', function () {
        it('updates icon field for several documents', function () {
            \WP_Mock::wpFunction('have_rows', [
                'args' => ['documents', 4],
                'return_in_order' => [true, true, true, true, true, false],
            ]);
            \WP_Mock::wpFunction('the_row', [
                'args' => [],
                'times' => 5,
            ]);

            \WP_Mock::wpFunction('get_sub_field', [
                'args' => ['document'],
                'return_in_order' => [
                    ['mime_type' => 'application/pdf'],
                    ['mime_type' => 'text/csv'],
                    false,
                    ['mime_type' => 'application/msword'],
                    ['mime_type' => 'application/vnd.ms-excel'],
                ],
            ]);

            \WP_Mock::wpFunction('update_sub_field', [
                'args' => ['icon', 'pdf'],
                'times' => 1,
            ]);
            \WP_Mock::wpFunction('update_sub_field', [
                'args' => ['icon', 'csv'],
                'times' => 1,
            ]);
            \WP_Mock::wpFunction('update_sub_field', [
                'args' => ['icon', ''],
                'times' => 1,
            ]);
            \WP_Mock::wpFunction('update_sub_field', [
                'args' => ['icon', 'doc'],
                'times' => 1,
            ]);
            \WP_Mock::wpFunction('update_sub_field', [
                'args' => ['icon', 'xls'],
                'times' => 1,
            ]);

            $this->icons->wpInsertPost(4);
        });
    });

    describe('->thumbnailSrc()', function () {
        it('returns thumbnail src', function () {
            \WP_Mock::wpFunction('get_sub_field', [
                'args' => ['thumbnail'],
                'return' => ['url' => '/path/to/thumbnail.jpg'],
            ]);
            $output = $this->icons->thumbnailSrc();
            expect($output)->to->equal('/path/to/thumbnail.jpg');
        });

        it('returns path to a default icon (empty string)', function () {
            \WP_Mock::wpFunction('get_sub_field', [
                    'args' => ['thumbnail'],
                    'return' => false,
                ]);

            \WP_Mock::wpFunction('get_stylesheet_directory_uri', [
                    'args' => [],
                    'return' => '/path/to/theme/templates/',
                ]);

            \WP_Mock::wpFunction('get_sub_field', [
                    'args' => ['icon'],
                    'return' => '',
                ]);

            $output = $this->icons->thumbnailSrc();
            expect($output)->to->equal('/path/to/theme/assets/img/document-icons/file.png');
        });

        it('returns path to a default icon (false)', function () {
            \WP_Mock::wpFunction('get_sub_field', [
                'args' => ['thumbnail'],
                'return' => false,
            ]);

            \WP_Mock::wpFunction('get_stylesheet_directory_uri', [
                'args' => [],
                'return' => '/path/to/theme/templates/',
            ]);

            \WP_Mock::wpFunction('get_sub_field', [
                'args' => ['icon'],
                'return' => false,
            ]);

            $output = $this->icons->thumbnailSrc();
            expect($output)->to->equal('/path/to/theme/assets/img/document-icons/file.png');
        });

        it('returns icon if set', function () {
            \WP_Mock::wpFunction('get_sub_field', [
                'args' => ['thumbnail'],
                'return' => false,
            ]);

            \WP_Mock::wpFunction('get_sub_field', [
                'args' => ['icon'],
                'return' => 'pdf',
            ]);

            \WP_Mock::wpFunction('get_stylesheet_directory_uri', [
                'args' => [],
                'return' => '/path/to/theme/templates/',
            ]);

            $output = $this->icons->thumbnailSrc();
            expect($output)->to->equal('/path/to/theme/assets/img/document-icons/pdf.png');
        });
    });

    describe('->adminPrintScripts()', function () {
        it('prints CSS', function () {
            ob_start();
            $this->icons->adminPrintScripts();
            $output = ob_get_clean();
            expect($output)->to->equal('<style>.acf-field-r12uajnnew{display:none}</style>');
        });
    });
});
