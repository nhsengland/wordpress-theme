<?php

namespace NHSEngland;

class DocumentContainerTaxonomyLinks implements \Dxw\Iguana\Registerable
{
    const FILTER_PARAMS = [
        'publication-type' => 'filter-publication',
        'category' => 'filter-category'
    ];

    public function register()
    {
        add_filter('term_link', [$this, 'pointToDocumentSearch'], 10, 3);
    }

    public function pointToDocumentSearch($termlink /*string*/, $term /*term object*/, $taxonomy /*taxonomy slug*/)
    {
        global $post;
        if ($post->post_type !== 'document') {
            return $termlink;
        }
        if (!is_single()) {
            return $termlink;
        }
        if ($taxonomy === 'publication-type' || $taxonomy === 'category') {
            $archiveLink = get_post_type_archive_link('document');
            $termSlug = urlencode($term->slug);
            $termlink = $archiveLink . "?" . self::FILTER_PARAMS[$taxonomy] . "=" . $termSlug;
            return $termlink;
        }
        return $termlink;
    }
}
