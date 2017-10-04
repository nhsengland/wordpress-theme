<?php

namespace NHSEngland;

class CoAuthorCountPostTypes implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('coauthors_count_published_post_types', [$this, 'coauthors_count_published_post_types']);
    }

    public function coauthors_count_published_post_types()
    {
        return ['blog', 'post'];
    }
}
