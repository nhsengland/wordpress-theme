<?php

namespace NHSEngland\Theme;

class CommentModerationNotification implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('init', [$this, 'check_for_moderation_cookie']);
        add_filter('comment_post_redirect', [$this, 'anchor_to_moderation_notification'], 10, 2);
        add_action('comment_form_after', [$this, 'add_comment_moderation_notification']);
        add_action('set_comment_cookies', [$this, 'add_cookie_awaiting_moderation']);
    }

    public function anchor_to_moderation_notification($location, $comment)
    {
        if ($comment->comment_approved==0) {
            $comment_id = $comment->comment_ID;
            $location = str_replace("comment-" . $comment_id, "awaiting-moderation-notification", $location);
        }
        return $location;
    }

    public function add_comment_moderation_notification()
    {
        if (isset($GLOBALS['dxw_awaiting_moderation'])) {
            $awaiting_moderation = $GLOBALS['dxw_awaiting_moderation'];
            if ($awaiting_moderation == 1) {
                ?>
                <div id="awaiting-moderation-notification" class="moderation-notification">
                    <h4>Your comment has been successfully submitted for moderation.</h4>
                    <p>
                        For more information please see the <a href="https://www.england.nhs.uk/comment-policy/">NHS England comment policy</a>.
                    </p>
                </div>
                <?php
            }
        }
    }

    public function add_cookie_awaiting_moderation($comment)
    {
        if ($comment->comment_approved==0) {
            setcookie('dxw_awaiting_moderation', 1, time() + 3600*24, COOKIEPATH, COOKIE_DOMAIN, false);
        }
    }

    public function check_for_moderation_cookie()
    {
        if (isset($_COOKIE['dxw_awaiting_moderation'])) {
            $GLOBALS[ 'dxw_awaiting_moderation' ] = 1;
            setcookie('dxw_awaiting_moderation', '', time() - 3600*24, COOKIEPATH, COOKIE_DOMAIN);
        }
    }
}
