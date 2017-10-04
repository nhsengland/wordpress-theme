<?php

namespace NHSEngland\Theme;

class Author implements \Dxw\Iguana\Registerable
{
    private $helpers;

    public function __construct(\Dxw\Iguana\Theme\Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function register()
    {
        $this->helpers->registerFunction('nhs_avatar', [$this, 'nhs_avatar']);
        $this->helpers->registerFunction('nhs_byline', [$this, 'nhs_byline']);
    }

    /**
    * Avatar
    *
    */
    public function nhs_avatar($author = null)
    {
        global $coauthors_plus;

        if (function_exists('get_coauthors')) {
            if ($author === null) {
                $author_id = (int)get_the_author_meta('ID');
                $author_name = get_the_author_meta('display_name');
                $author_email = get_the_author_meta('email');
            } else {
                $author_id = (int)$author->ID;
                $author_name = $author->display_name;
                $author_email = $author->user_email;
            }

            $guest_authors = get_posts([
                'post_type' => 'guest-author',
                'post_status' => 'any',
                'posts_per_page' => -1,
            ]);

            foreach ($guest_authors as $guest_author) {
                // We can't just match on ID because there could be a WP user with the same ID
                if ((int)$guest_author->ID === $author_id && $guest_author->post_title === $author_name) {
                    $author_object = $coauthors_plus->get_coauthor_by('id', $author_id);
                    if (coauthors_get_avatar($author_object, 140) !== false) {
                        echo coauthors_get_avatar($author_object, 140);
                    } else {
                        echo '<img alt="" src="' . get_avatar_url($author_object, ['default' => 'mystery', 'size' => '140', 'scheme' => 'https']) . '" srcset="' . get_avatar_url($author_object, ['default' => 'mystery', 'size' => '280', 'scheme' => 'https']) . ' 2x" class="avatar avatar-140 photo avatar-default" height="140" width="140">';
                    }
                    return;
                }
            }
        }

        echo get_avatar($author_email, 140);
    }


    /**
    * By Line
    * Display the author for a post, using coauthors if available and falling back to the WordPress user if not.
    */
    public function nhs_byline()
    {
        if (function_exists('coauthors_posts_links')) {
            coauthors_posts_links(', ');
        } else {
            ?> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')) ?>" rel="author"><?php echo get_the_author() ?></a> <?php
        }
    }
}
