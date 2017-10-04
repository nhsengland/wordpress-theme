<?php

describe(\NHSEngland\LandingPageFields::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->landingPageFields = new \NHSEngland\LandingPageFields();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->landingPageFields)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds an action', function () {
            WP_Mock::expectActionAdded('init', [$this->landingPageFields, 'addFields']);
            $this->landingPageFields->register();
        });
    });

    describe('->addFields()', function () {
        it('adds the ACF fields', function () {
            WP_Mock::wpFunction('acf_add_local_field_group', [
                'times' => 4,
                'args' => [
                    \WP_Mock\Functions::type('array')
                ]
            ]);
            $this->landingPageFields->addFields();
        });
    });
});
