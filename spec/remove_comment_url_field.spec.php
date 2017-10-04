<?php

describe(\NHSEngland\RemoveCommentUrlField::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->removeCommentUrlField = new \NHSEngland\RemoveCommentUrlField();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->removeCommentUrlField)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds a filter', function () {
            WP_Mock::expectFilterAdded('comment_form_default_fields', [$this->removeCommentUrlField, 'removeUrlField'], 10, 1);
            $this->removeCommentUrlField->register();
        });
    });

    describe('->removeUrlField()', function () {
        context('url key not set', function () {
            it('returns input', function () {
                $input = [
                    'email' => 'foo',
                    'name' => 'bar'
                ];
                $result = $this->removeCommentUrlField->removeUrlField($input);
                expect($result)->to->equal($input);
            });
        });
        context('url key is set', function () {
            it('removes it', function () {
                $input = [
                    'email' => 'foo',
                    'name' => 'bar',
                    'url' => 'test'
                ];
                $expected = [
                    'email' => 'foo',
                    'name' => 'bar'
                ];
                $result = $this->removeCommentUrlField->removeUrlField($input);
                expect($result)->to->equal($expected);
            });
        });
    });
});
