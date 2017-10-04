<?php

describe(\NHSEngland\CategoryFields::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->categoryFields = new \NHSEngland\CategoryFields();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->categoryFields)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds an action', function () {
            WP_Mock::expectActionAdded('init', [$this->categoryFields, 'addFields']);
            $this->categoryFields->register();
        });
    });

    describe('->addFields()', function () {
        it('adds the ACF fields', function () {
            WP_Mock::wpFunction('acf_add_local_field_group', [
                'times' => 2,
                'args' => [
                    \WP_Mock\Functions::type('array')
                ]
            ]);
            $this->categoryFields->addFields();
        });
    });
});
