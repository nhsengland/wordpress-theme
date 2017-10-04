<?php

namespace NHSEngland;

class HideCategories implements \Dxw\Iguana\Registerable
{
    protected static $exclude = [
        'Home',
        'Uncategorised'
    ];

    public function register()
    {
        add_filter('get_the_terms', [$this, 'get_the_terms'], 10, 3);
        add_filter('get_terms', [$this, 'get_terms'], 10, 2);
    }

    public function get_the_terms($terms, $postID, $taxonomy)
    {
        if (is_admin()) {
            return $terms;
        }
        if ($taxonomy !== 'category') {
            return $terms;
        }
        $cleanTerms = [];
        foreach ($terms as $term) {
            if (!in_array($term->name, self::$exclude)) {
                $cleanTerms[] = $term;
            }
        }
        return $cleanTerms;
    }

    public function get_terms($terms, $taxonomies)
    {
        if (is_admin()) {
            return $terms;
        }
        if (!in_array('category', $taxonomies)) {
            return $terms;
        }
        $cleanTerms = [];
        foreach ($terms as $term) {
            if (!in_array($term->name, self::$exclude) || $term->taxonomy !== 'category') {
                $cleanTerms[] = $term;
            }
        }
        return $cleanTerms;
    }
}
