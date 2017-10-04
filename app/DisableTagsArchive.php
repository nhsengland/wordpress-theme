<?php

namespace NHSEngland;

class DisableTagsArchive implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('pre_get_posts', [$this, 'blockTagsArchive']);
    }

    public function blockTagsArchive($query)
    {
        if (is_tag()) {
            $query->set_404();
            status_header(404);
        }
        return $query;
    }
}
