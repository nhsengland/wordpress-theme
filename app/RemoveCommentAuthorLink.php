<?php

namespace NHSEngland;

class RemoveCommentAuthorLink implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_filter('get_comment_author_url', [$this, 'getCommentAuthorUrl']);
    }

    public function getCommentAuthorUrl()
    {
    }
}
