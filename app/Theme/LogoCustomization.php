<?php

namespace NHSEngland\Theme;

class LogoCustomization implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('customize_register', [$this, 'addLogoSection']);
        add_action('customize_register', [$this, 'addLogoSetting']);
        add_action('customize_register', [$this, 'addLogoControl']);
    }

    public function addLogoSection($wp_customize)
    {
        $wp_customize->add_section('logo_section', [
            'title' => 'Header Logo',
            'priority' => 30
        ]);
    }

    public function addLogoSetting($wp_customize)
    {
        $wp_customize->add_setting('custom_logo', [
            'default' => '',
            'transport' => 'refresh'
        ]);
    }

    public function addLogoControl($wp_customize)
    {
        $wp_customize->add_control(
            new \WP_Customize_Image_Control(
                $wp_customize,
                'custom_logo',
                [
                    'label'      => 'Upload a logo',
                    'section'    => 'logo_section',
                    'settings'   => 'custom_logo',
                ]
            )
        );
    }
}
