<?php

namespace NHSEngland;

class OptionalMetadata implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_filter('manage_post_posts_columns', [$this, 'managePostsColumns']);
        add_filter('manage_page_posts_columns', [$this, 'managePostsColumns']);
        add_filter('manage_media_columns', [$this, 'managePostsColumns']);

        add_action('manage_posts_custom_column', [$this, 'managePostsCustomColumn'], 10, 2);
        add_action('manage_pages_custom_column', [$this, 'managePostsCustomColumn'], 10, 2);
        add_action('manage_media_custom_column', [$this, 'managePostsCustomColumn'], 10, 2);

        add_filter('posts_where', [$this, 'postsWhere'], 10, 2);
    }

    public function managePostsColumns($columns)
    {
        return array_merge($columns, [
            'owner' => 'Owner',
            'description' => 'Description',
            'gateway_ref' => 'Gateway Ref',
            'pcc_reference' => 'PCC Reference',
        ]);
    }

    public function managePostsCustomColumn($column, $post_id)
    {
        if (in_array($column, ['owner', 'description', 'gateway_ref', 'pcc_reference'], true)) {
            echo get_post_meta($post_id, $column, true);
        }
    }

    public function postsWhere($where, $query)
    {
        global $wpdb;

        if (!is_admin() || !$query->is_search()) {
            return $where;
        }

        $s = $query->get('s');

        if (!in_array($query->get('post_type'), ['post', 'page', 'attachment'], true) || is_null($s) || $s === '') {
            return $where;
        }

        $inner_query = [];
        foreach (['owner', 'description', 'gateway_ref', 'pcc_reference'] as $field) {
            $inner_query[] = $wpdb->prepare("{$wpdb->posts}.ID IN (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key=%s AND meta_value LIKE %s)", $field, '%'.$wpdb->esc_like($s).'%');
        }

        $post_type = $wpdb->prepare("{$wpdb->posts}.post_type = %s", $query->get('post_type'));

        return $where." OR ($post_type AND ({$wpdb->posts}.post_status = 'publish' OR {$wpdb->posts}.post_status = 'acf-disabled' OR {$wpdb->posts}.post_status = 'future' OR {$wpdb->posts}.post_status = 'draft' OR {$wpdb->posts}.post_status = 'pending' OR {$wpdb->posts}.post_status = 'private' OR {$wpdb->posts}.post_status = 'inherit') AND (".implode(' OR ', $inner_query).'))';
    }
}
