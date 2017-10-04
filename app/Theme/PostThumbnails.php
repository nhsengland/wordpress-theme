<?php

namespace NHSEngland\Theme;

class PostThumbnails implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        /* Add post thumbnails */
        add_theme_support('post-thumbnails');
    }
}
