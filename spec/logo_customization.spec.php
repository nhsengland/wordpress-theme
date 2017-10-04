<?php

//Helper class for testing
class WP_Customize_Image_Control
{
    public static $timesCalled = 0;
    public static $args = [];

    public function __construct()
    {
        self::$timesCalled ++;
        self::$args[] = func_get_args();
    }
}

describe(\NHSEngland\Theme\LogoCustomization::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->logoCustomization = new \NHSEngland\Theme\LogoCustomization();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->logoCustomization)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the actions', function () {
            WP_Mock::expectActionAdded('customize_register', [$this->logoCustomization, 'addLogoSection']);
            $this->logoCustomization->register();
            WP_Mock::expectActionAdded('customize_register', [$this->logoCustomization, 'addLogoSetting']);
            $this->logoCustomization->register();
            WP_Mock::expectActionAdded('customize_register', [$this->logoCustomization, 'addLogoControl']);
            $this->logoCustomization->register();
        });
    });

    describe('addLogoSection()', function () {
        it('adds the section', function () {
            $this->wpCustomize = Mockery::mock(stdClass::class);
            $this->wpCustomize->shouldReceive('add_section')
                ->once()
                ->with(
                    'logo_section',
                    [
                        'title' => 'Header Logo',
                        'priority' => 30
                    ]
                );
            $this->logoCustomization->addLogoSection($this->wpCustomize);
        });
    });

    describe('addLogoSetting()', function () {
        it('adds the setting', function () {
            $this->wpCustomize = Mockery::mock(stdClass::class);
            $this->wpCustomize->shouldReceive('add_setting')
                ->once()
                ->with(
                    'custom_logo',
                    [
                        'default' => '',
                        'transport' => 'refresh'
                    ]
                );
            $this->logoCustomization->addLogoSetting($this->wpCustomize);
        });
    });

    describe('addLogoControl()', function () {
        it('adds the control', function () {
            $this->wpCustomize = Mockery::mock(stdClass::class);
            $this->wpCustomize->shouldReceive('add_control')
                ->once();
            $this->logoCustomization->addLogoControl($this->wpCustomize);
            expect(WP_Customize_Image_Control::$timesCalled)->to->equal(1);
            expect(WP_Customize_Image_Control::$args[0])->to->equal([
                $this->wpCustomize,
                'custom_logo',
                [
                    'label' => 'Upload a logo',
                    'section' => 'logo_section',
                    'settings' => 'custom_logo',
                ]
            ]);
        });
    });
});
