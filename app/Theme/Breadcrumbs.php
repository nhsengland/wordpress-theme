<?php

namespace NHSEngland\Theme;

class Breadcrumbs
{
    public function __construct($helpers)
    {
        $helpers->registerFunction('Breadcrumbs', [$this, 'write_breadcrumb']);
    }

    public function write_breadcrumb()
    {
        global $post;
        $pid = $post->ID;
        $trail = '<li><a href="/">Home</a></li>';

        if (is_multisite() && defined('BLOG_ID_CURRENT_SITE') && get_current_blog_id() !== BLOG_ID_CURRENT_SITE) {
            $url = site_url();
            $name = get_bloginfo('name');
            $trail .= sprintf('<li><a href="%s">%s</a></li>', esc_attr($url), esc_html($name));
        }

        if (is_front_page()) {
            // do nothing
        } elseif (is_page()) {
            $bcarray = [];
            $pdata = get_post($pid);
            $bcarray[] = '<li>'.$pdata->post_title.'</li>';
            while ($pdata->post_parent) {
                $pdata = get_post($pdata->post_parent);
                $bcarray[] = '<li><a href="'.get_permalink($pdata->ID).'">'.$pdata->post_title.'</a></li>';
            }
            $bcarray = array_reverse($bcarray);
            foreach ($bcarray as $listitem) {
                $trail .= $listitem;
            }
        } elseif (is_single()) {
            $pdata = get_the_category($pid);
            $adata = get_post($pid);
            if (!empty($pdata)) {
                $data = get_category_parents($pdata[0]->cat_ID, true, ' &raquo; ');
                $trail .= ' &raquo; '.substr($data, 0, -8);
            }
            $trail .= ' &raquo; '.$adata->post_title."\n";
        } elseif (is_category()) {
            $pdata = get_the_category($pid);
            $data = get_category_parents($pdata[0]->cat_ID, true, ' &raquo; ');
            if (!empty($pdata)) {
                $trail .= ' &raquo; '.substr($data, 0, -8);
            }
        }
        $trail .= '';

        return $trail;
    }
}
