<?php

namespace NHSEngland\DocumentSearch;

class Filtering implements \Dxw\Iguana\Registerable
{
    public function __construct(
        KeywordField $keywordField,
        CategoryField $categoryField,
        PublicationField $publicationField,
        DateFromField $dateFromField,
        DateToField $dateToField
    ) {
        $this->fields = [
            $keywordField,
            $categoryField,
            $publicationField,
            $dateFromField,
            $dateToField,
        ];
    }

    public function register()
    {
        add_action('pre_get_posts', [$this, 'preGetPosts']);
    }

    public function preGetPosts(\WP_Query $query)
    {
        if (is_admin()) {
            return;
        }
        if ($query->get('post_type') == 'document' || $query->get('post_type') == 'blog' || ($query->is_home() && $query->is_main_query())) {
            foreach ($this->fields as $field) {
                $field->modifyQuery($query);
            }
        } else {
            return;
        }
    }
}
