<?php

namespace NHSEngland;

class NewsHomepage implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('pre_get_posts', [$this, 'dontListFeaturedPosts']);
        add_action('pre_get_posts', [$this, 'onlyListNewsPosts']);
    }

    public function dontListFeaturedPosts(\WP_Query $query)
    {
        if (isset($_GET['filter-keyword']) || isset($_GET['filter-category']) || isset($_GET['filter-date-from']) || isset($_GET['filter-date-to'])) {
            $query->set('ignore_sticky_posts', 1);
            return;
        }
        if ($query->is_home() && $query->is_main_query()) {
            $stickyPosts = get_option('sticky_posts');
            if (!$stickyPosts || $stickyPosts === '') {
                $recentPosts = wp_get_recent_posts([
                    'numberposts' => 1,
                ]);
                $postsToExclude = wp_list_pluck($recentPosts, 'ID');
            } else {
                $postsToExclude = [$stickyPosts[0]];
            }
            $query->set('post__not_in', $postsToExclude);
        }
    }

    public function onlyListNewsPosts(\WP_Query $query)
    {
        if ($query->is_home() && $query->is_main_query()) {
            $query->set('post_type', 'post');
        }
    }
}
