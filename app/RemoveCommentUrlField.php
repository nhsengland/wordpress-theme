<?php

namespace NHSEngland;

class RemoveCommentUrlField implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_filter('comment_form_default_fields', [$this, 'removeUrlField'], 10, 1);
    }

    public function removeUrlField($fields)
    {
        if (array_key_exists('url', $fields)) {
            unset($fields['url']);
        }
        return $fields;
    }
}
