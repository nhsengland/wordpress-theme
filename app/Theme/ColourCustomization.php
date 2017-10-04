<?php

namespace NHSEngland\Theme;

class ColourCustomization implements \Dxw\Iguana\Registerable
{
    private static $defaultHeaderColor = '#005eb8';
    private static $defaultHeaderHighlightColor = '#0072ce';

    public function register()
    {
        add_action('customize_register', [$this, 'addSettings']);
        add_action('customize_register', [$this, 'addControls']);
        add_action('wp_head', [$this, 'outputCss']);
    }

    public function addSettings($wp_customize)
    {
        $wp_customize->add_setting('header_color', [
            'default' => self::$defaultHeaderColor,
            'transport' => 'refresh'
        ]);
        $wp_customize->add_setting('header_highlight_color', [
            'default' => self::$defaultHeaderHighlightColor,
            'transport' => 'refresh'
        ]);
    }

    public function addControls($wp_customize)
    {
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'header_color',
                [
                    'label'      => 'Header Color',
                    'section'    => 'colors',
                    'settings'   => 'header_color',
                ]
            )
        );
        $wp_customize->add_control(
            new \WP_Customize_Color_Control(
                $wp_customize,
                'header_highlight_color',
                [
                    'label'      => 'Header Highlight Color',
                    'section'    => 'colors',
                    'settings'   => 'header_highlight_color',
                ]
            )
        );
    }

    public function outputCss()
    {
        ?>
            <style type="text/css">
                .header { background: <?php echo get_theme_mod('header_color', self::$defaultHeaderColor); ?>; }
                .top-nav-container { background: <?php echo get_theme_mod('header_highlight_color', self::$defaultHeaderHighlightColor); ?>; }
                .navigation li > a:hover, .navigation li > a.open, .navigation li.current-menu-item > a, .navigation li.current-menu-parent > a, .navigation li.current_page_item > a, .navigation li.current-page-ancestor > a { background: <?php echo get_theme_mod('header_highlight_color', self::$defaultHeaderHighlightColor); ?>; }
            </style>
        <?php
    }
}
