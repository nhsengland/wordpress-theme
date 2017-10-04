<?php

namespace NHSEngland;

class NewsCategoryLinks implements \Dxw\Iguana\Registerable
{
    public function __construct(\Dxw\Iguana\Theme\Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function register()
    {
        add_filter('term_link', [$this, 'pointToNewsArchive'], 10, 3);
        $this->helpers->registerFunction('getNewsArchiveLink', [$this, 'getNewsArchiveLink']);
    }

    public function pointToNewsArchive(string $termlink /*string*/, \WP_Term $term /*term object*/, string $taxonomy /*taxonomy slug*/) : string
    {
        global $post;
        if ($post->post_type !== 'post') {
            return $termlink;
        }
        if ($taxonomy!=='category') {
            return $termlink;
        }
        return $this->getLinkFromTermObject($term);
    }

    private function getLinkFromTermObject(\WP_Term $term, string $url='') : string
    {
        if ($url === '') {
            $archiveLink = get_permalink(get_option('page_for_posts'));
        } else {
            $archiveLink = $url;
        }
        $termSlug = urlencode($term->slug);
        $termlink = $archiveLink . "?filter-keyword=&filter-category=" . $termSlug;
        return $termlink;
    }

    public function getNewsArchiveLink(int $termId, string $url='') : string
    {
        $term = get_term($termId, 'category');
        return $this->getLinkFromTermObject($term, $url);
    }
}
