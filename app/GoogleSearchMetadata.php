<?php

namespace NHSEngland;

class GoogleSearchMetadata implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('wp_head', [$this, 'addGoogleSearchMetadata']);
    }

    public function addGoogleSearchMetadata()
    {
        if (!is_singular()) {
            return;
        }

        $date = get_the_date('Ymd');

        global $post;

        $id = $post->ID;

        $categories = get_the_category($id);

        $post_type = $post->post_type;

        foreach ($categories as $category) {
            echo "<meta name='category' content='" . $category->slug . "'>\r\n";
        }

        echo "<meta name='post-type' content='" . $post_type . "'>\r\n";

        echo "<meta name='pubdate' content='" . $date . "'>\r\n";
    }
}
