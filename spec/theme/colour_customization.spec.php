<?php

//Helper class for testing
class WP_Customize_Color_Control
{
    public static $timesCalled = 0;
    public static $args = [];

    public function __construct()
    {
        self::$timesCalled ++;
        self::$args[] = func_get_args();
    }
}

describe(\NHSEngland\Theme\ColourCustomization::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->colourCustomization = new \NHSEngland\Theme\ColourCustomization();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->colourCustomization)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the actions', function () {
            WP_Mock::expectActionAdded('customize_register', [$this->colourCustomization, 'addSettings']);
            WP_Mock::expectActionAdded('customize_register', [$this->colourCustomization, 'addControls']);
            WP_Mock::expectActionAdded('wp_head', [$this->colourCustomization, 'outputCss']);

            $this->colourCustomization->register();
        });
    });

    describe('->addSettings()', function () {
        it('adds the settings', function () {
            $wpCustomize = Mockery::mock(stdClass::class);
            $wpCustomize->shouldReceive('add_setting')
                ->once()
                ->with(
                    'header_color',
                    [
                        'default' => '#005eb8',
                        'transport' => 'refresh'
                    ]
                );
            $wpCustomize->shouldReceive('add_setting')
                ->once()
                ->with(
                    'header_highlight_color',
                    [
                        'default' => '#0072ce',
                        'transport' => 'refresh'
                    ]
                );
            $this->colourCustomization->addSettings($wpCustomize);
        });
    });

    describe('->addControls()', function () {
        it('adds the controls', function () {
            $wpCustomize = Mockery::mock(stdClass::class);
            $wpCustomize->shouldReceive('add_control')
                ->twice();
            $this->colourCustomization->addControls($wpCustomize);
            expect(WP_Customize_Color_Control::$timesCalled)->to->equal(2);
            expect(count(WP_Customize_Color_Control::$args))->to->equal(2);
            expect(WP_Customize_Color_Control::$args[0])->to->equal([
                $wpCustomize,
                'header_color',
                [
                    'label'      => 'Header Color',
                    'section'    => 'colors',
                    'settings'   => 'header_color',
                ]
            ]);
            expect(WP_Customize_Color_Control::$args[1])->to->equal([
                $wpCustomize,
                'header_highlight_color',
                [
                    'label'      => 'Header Highlight Color',
                    'section'    => 'colors',
                    'settings'   => 'header_highlight_color',
                ]
            ]);
        });
    });

    describe('->outputCss()', function () {
        it('adds the Css', function () {
            WP_Mock::wpFunction('get_theme_mod', [
                'times' => 1,
                'args' => [
                    'header_color',
                    '#005eb8'
                ],
                'return' => 'aCustomColor'
            ]);
            WP_Mock::wpFunction('get_theme_mod', [
                'times' => 2,
                'args' => [
                    'header_highlight_color',
                    '#0072ce'
                ],
                'return' => 'aCustomHighlightColor'
            ]);
            ob_start();
            $this->colourCustomization->outputCss();
            $output = ob_get_clean();
            expect($output)->to->include('.header { background: aCustomColor; }');
            expect($output)->to->include('.top-nav-container { background: aCustomHighlightColor; }');
            expect($output)->to->include('.navigation li > a:hover, .navigation li > a.open, .navigation li.current-menu-item > a, .navigation li.current-menu-parent > a, .navigation li.current_page_item > a, .navigation li.current-page-ancestor > a { background: aCustomHighlightColor; }');
        });
    });
});
