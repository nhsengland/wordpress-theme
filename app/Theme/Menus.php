<?php

namespace NHSEngland\Theme;

class Menus implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('init', [$this, 'register_my_menus']);
    }

    /* Add Menu support */
    public function register_my_menus()
    {
        if (function_exists('add_theme_support')) {
            add_theme_support('menus');
        }
        register_nav_menus(
            [
                'primary' => __('Primary header navigation'),
                'secondary' => __('Top header navigation'),
                'footer' => __('Footer navigation')
            ]
        );
    }
}
