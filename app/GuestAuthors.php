<?php

namespace NHSEngland;

class GuestAuthors implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_filter('coauthors_auto_apply_template_tags', [$this, 'coauthorsAutoApplyTemplateTags']);
        add_action('init', [$this, 'removeAuthorFilter'], 100);
    }

    public function coauthorsAutoApplyTemplateTags()
    {
        return true;
    }

    public function removeAuthorFilter()
    {
        remove_filter('the_author', [$GLOBALS['coauthors_plus_template_filters'], 'filter_the_author']);
    }
}
