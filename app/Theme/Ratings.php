<?php

namespace NHSEngland\Theme;

class Ratings implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('init', function () {
            if (function_exists('register_rating')) {
                register_rating('product-format');
                register_rating('content-usefulness');
                register_rating('beneficial-impact');
            }
        });
    }
}
