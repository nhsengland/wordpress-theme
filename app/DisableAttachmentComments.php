<?php

namespace NHSEngland;

class DisableAttachmentComments implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('init', [$this, 'removeCommentSupport']);
        //The above only works for attachments added from now on
        //So we need to turn off comments for existing attachments as well
        add_filter('comments_open', [$this, 'commentsOpen'], 10, 2);
    }

    public function removeCommentSupport()
    {
        remove_post_type_support('attachment', 'comments');
    }

    public function commentsOpen($open, $post_id)
    {
        $post_type = get_post_type($post_id);
        if ($post_type !== 'attachment') {
            return $open;
        }
        return false;
    }
}
