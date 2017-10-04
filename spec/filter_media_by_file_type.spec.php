<?php

describe(\NHSEngland\FilterMediaByFileType::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->filterMediaByFileType = new \NHSEngland\FilterMediaByFileType();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->filterMediaByFileType)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds an action', function () {
            WP_Mock::expectFilterAdded('post_mime_types', [$this->filterMediaByFileType, 'modifyPostMimeTypes']);
            $this->filterMediaByFileType->register();
        });
    });

    describe('->modifyPostMimeTypes()', function () {
        it('adds extra Mime Types', function () {
            $testInput = [];
            \WP_Mock::wpFunction('__', [
                'args' => 'PDFs',
                'times' => 1,
                'return' => 'PDFs'
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'Manage PDFs',
                'times' => 1,
                'return' => 'Manage PDFs'
            ]);
            \WP_Mock::wpFunction('_n_noop', [
                'args' => ['PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>'],
                'times' => 1,
                'return' => ['PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>']
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'Spreadsheets',
                'times' => 1,
                'return' => 'Spreadsheets'
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'Manage Spreadsheets',
                'times' => 1,
                'return' => 'Manage Spreadsheets'
            ]);
            \WP_Mock::wpFunction('_n_noop', [
                'args' => ['Spreadsheet <span class="count">(%s)</span>', 'Spreadsheets <span class="count">(%s)</span>'],
                'times' => 1,
                'return' => ['Spreadsheet <span class="count">(%s)</span>', 'Spreadsheets <span class="count">(%s)</span>']
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'Word docs',
                'times' => 1,
                'return' => 'Word docs'
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'Manage Word docs',
                'times' => 1,
                'return' => 'Manage Word docs'
            ]);
            \WP_Mock::wpFunction('_n_noop', [
                'args' => ['Word doc <span class="count">(%s)</span>', 'Word docs <span class="count">(%s)</span>'],
                'times' => 1,
                'return' => ['Word doc <span class="count">(%s)</span>', 'Word docs <span class="count">(%s)</span>']
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'CSVs',
                'times' => 1,
                'return' => 'CSVs'
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'Manage CSVs',
                'times' => 1,
                'return' => 'Manage CSVs'
            ]);
            \WP_Mock::wpFunction('_n_noop', [
                'args' => ['CSV <span class="count">(%s)</span>', 'CSVs <span class="count">(%s)</span>'],
                'times' => 1,
                'return' => ['CSV <span class="count">(%s)</span>', 'CSVs <span class="count">(%s)</span>']
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'PowerPoint presentations',
                'times' => 1,
                'return' => 'PowerPoint presentations'
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'Manage PowerPoint presentations',
                'times' => 1,
                'return' => 'Manage PowerPoint presentations'
            ]);
            \WP_Mock::wpFunction('_n_noop', [
                'args' => ['PowerPoint presentation <span class="count">(%s)</span>', 'PowerPoint presentations <span class="count">(%s)</span>'],
                'times' => 1,
                'return' => ['PowerPoint presentation <span class="count">(%s)</span>', 'PowerPoint presentations <span class="count">(%s)</span>']
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'Zip files',
                'times' => 1,
                'return' => 'Zip files'
            ]);
            \WP_Mock::wpFunction('__', [
                'args' => 'Manage Zip files',
                'times' => 1,
                'return' => 'Manage Zip files'
            ]);
            \WP_Mock::wpFunction('_n_noop', [
                'args' => ['Zip file <span class="count">(%s)</span>', 'Zip files <span class="count">(%s)</span>'],
                'times' => 1,
                'return' => ['Zip file <span class="count">(%s)</span>', 'Zip files <span class="count">(%s)</span>']
            ]);
            $result = $this->filterMediaByFileType->modifyPostMimeTypes($testInput);
            expect($result['application/pdf'])->to->equal(['PDFs', 'Manage PDFs', ['PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>'] ]);
            expect($result['*ms-excel*,*spreadsheetml*'])->to->equal(['Spreadsheets', 'Manage Spreadsheets', ['Spreadsheet <span class="count">(%s)</span>', 'Spreadsheets <span class="count">(%s)</span>'] ]);
            expect($result['*msword*,*ms-word*,*wordprocessingml*'])->to->equal(['Word docs', 'Manage Word docs', ['Word doc <span class="count">(%s)</span>', 'Word docs <span class="count">(%s)</span>'] ]);
            expect($result['text/csv'])->to->equal(['CSVs', 'Manage CSVs', ['CSV <span class="count">(%s)</span>', 'CSVs <span class="count">(%s)</span>'] ]);
            expect($result['*ms-powerpoint*,*presentationml*'])->to->equal(['PowerPoint presentations', 'Manage PowerPoint presentations', ['PowerPoint presentation <span class="count">(%s)</span>', 'PowerPoint presentations <span class="count">(%s)</span>'] ]);
            expect($result['application/zip'])->to->equal(['Zip files', 'Manage Zip files', ['Zip file <span class="count">(%s)</span>', 'Zip files <span class="count">(%s)</span>'] ]);
        });
    });
});
