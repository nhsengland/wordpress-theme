<?php

namespace NHSEngland;

class AuthorArchive implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('pre_get_posts', [$this, 'showBlogPostsOnly']);
    }

    public function showBlogPostsOnly($query)
    {
        if (is_admin()) {
            return;
        }

        if (!$query->is_author()) {
            return;
        }

        $query->set('post_type', 'blog');
    }
}
