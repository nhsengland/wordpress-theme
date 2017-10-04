<?php

namespace NHSEngland;

class DisplayName implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_filter('the_author', [$this, 'theAuthor']);
        add_filter('get_the_author_display_name', [$this, 'getTheAuthorDisplayName'], 10, 2);
    }

    public function theAuthor($author)
    {
        global $authordata;

        if (is_admin()) {
            return esc_html($authordata->user_login);
        }

        return $author;
    }

    public function getTheAuthorDisplayName($value, $user_id)
    {
        if (!is_admin()) {
            return $value;
        }

        $nickname = get_user_meta($user_id, 'nickname', true);

        return $value . " (" . $nickname . ")";
    }
}
