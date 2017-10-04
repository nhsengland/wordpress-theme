<?php

namespace NHSEngland;

class CategoryTaxonomyModification implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('init', [$this, 'modifyCategoryTaxonomy'], 11);
    }

    public function modifyCategoryTaxonomy()
    {
        global $wp_taxonomies;
        $cat = $wp_taxonomies['category'];

        $capabilities = $cat->cap;
        //only allow super admins to manage, edit and delete terms
        $capabilities->manage_terms = 'delete_users';
        $capabilities->edit_terms = 'delete_users';
        $capabilities->delete_terms = 'delete_users';
    }
}
