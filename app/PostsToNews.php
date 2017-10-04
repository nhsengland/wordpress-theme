<?php

namespace NHSEngland;

class PostsToNews implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('init', [$this, 'postsToNews']);
    }

    public function postsToNews()
    {
        global $wp_post_types;
        $labels = $wp_post_types['post']->labels;
        $labels->name = 'News';
        $labels->singular_name = 'News';
        $labels->add_new = 'Add News';
        $labels->add_new_item = 'Add News';
        $labels->edit_item = 'Edit News';
        $labels->new_item = 'News';
        $labels->view_item = 'View News';
        $labels->search_items = 'Search News';
        $labels->not_found = 'No News found';
        $labels->not_found_in_trash = 'No News found in Trash';
        $labels->all_items = 'All News';
        $labels->menu_name = 'News';
        $labels->name_admin_bar = 'News';
    }
}
