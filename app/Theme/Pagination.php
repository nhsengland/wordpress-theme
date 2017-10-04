<?php

namespace NHSEngland\Theme;

class Pagination implements \Dxw\Iguana\Registerable
{
    private $helpers;

    public function __construct(\Dxw\Iguana\Theme\Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function register()
    {
        $this->helpers->registerFunction('pagination', [$this, 'pagination']);
    }

    public function pagination($q = null, $mode = null)
    {
        global $wp_query;
        global $paged;

        if ($q === null) {
            $q = $wp_query;
        }

        if ($q->is_singular()) {
            return;
        }

        /** Stop execution if there's only 1 page */
        if ($q->max_num_pages <= 1) {
            return;
        }
        $args = $q->query;
        $max   = intval($q->max_num_pages);
        $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;

        /** Add current page to the array */
        if ($paged >= 1) {
            $links[] = $paged;
        }

        /** Add the pages around the current page to the array */
        if ($paged >= 3) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if (($paged + 2) <= $max) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        // Store $wp_query (ugly hack for ugly get_previous/next_posts_link() functions
        $old_wq = $wp_query;
        $wp_query = $q;

        echo "<div class='pager group'><ul class='pagination'>";

        // previous page
        if (get_previous_posts_link()) {
            $uri = previous_posts(false);
            if ($mode) {
                $uri = add_query_arg(['mode' => $mode], $uri);
            }
            printf('<li><a href="%s" class="previous-page">Previous</a></li>', $uri);
        }

        // Restore $wp_query
        $wp_query = $old_wq;

        /** Link to first page, plus ellipses if necessary */
        if (! in_array(1, $links)) {
            $class = 1 == $paged ? ' class="inactive"' : '';

            $uri = get_pagenum_link(1);
            if ($mode) {
                $uri = add_query_arg(['mode' => $mode], $uri);
            }
            printf('<li><a%s href="%s">%s</a></li>' . "\n", $class, esc_url($uri), '1');

            if (! in_array(2, $links)) {
                echo '<li><span class="ellipsis">…</span></li>';
            }
        }

        /** Link to current page, plus 2 pages in either direction if necessary */
        sort($links);
        foreach ((array) $links as $link) {
            $class = $paged == $link ? ' class="current"' : '';
            $uri = get_pagenum_link($link);
            if ($mode) {
                $uri = add_query_arg(['mode' => $mode], $uri);
            }
            printf('<li><a%s href="%s">%s</a></li>' . "\n", $class, esc_url($uri), $link);
        }

        /** Link to last page, plus ellipses if necessary */
        if (! in_array($max, $links)) {
            if (! in_array($max - 1, $links)) {
                echo '<li><span class="ellipsis">…</span></li>' . "\n";
            }

            $class = $paged == $max ? ' class="inactive"' : '';
            $uri = get_pagenum_link($max);
            if ($mode) {
                $uri = add_query_arg(['mode' => $mode], $uri);
            }
            printf('<li><a%s href="%s">%s</a></li>' . "\n", $class, esc_url($uri), $max);
        }

        // next page
        if (get_next_posts_link()) {
            $uri = next_posts($q->max_num_pages, false);
            if ($mode) {
                $uri = add_query_arg(['mode' => $mode], $uri);
            }
            printf('<li><a href="%s" class="next-page">Next</a></li>', $uri);
        }

        echo "</ul></div>\n";
    }
}
