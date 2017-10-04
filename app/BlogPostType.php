<?php

namespace NHSEngland;

class BlogPostType implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_action('init', [$this, 'registerBlogPostType']);
        add_action('post_submitbox_misc_actions', [$this, 'addStickyBox']);
        add_action('save_post_blog', [$this, 'saveStickyPostStatus']);
    }

    public function registerBlogPostType()
    {
        if (!is_main_site()) {
            return;
        }
        register_post_type('blog', [
            'label' => 'Blog posts',
            'labels' => [
                'name' => 'Blog posts',
                'singular_name' => 'Blog post',
                'add_new_item' => 'Add New Blog post',
                'edit_item' => 'Edit Blog post',
                'new_item' => 'New Blog post',
                'view_item' => 'View Blog post',
                'view_items' => 'View Blog posts',
            ],
            'public' => true,
            'menu_position' => 5,
            'has_archive' => true,
            'supports' => [
                'title',
                'editor',
                'author',
                'thumbnail',
                'revisions',
                'comments'
            ],
            'taxonomies' => [
                'category'
            ]
        ]);
    }

    public function addStickyBox()
    {
        global $post;

        if ($post->post_type !== 'blog') {
            return;
        } else {
            $sticky = get_post_meta($post->ID, '_sticky_blog_post', true);
            wp_nonce_field('_sticky_blog_post_nonce_' . $post->ID, '_sticky_blog_post_nonce'); ?>
            <div class="misc-pub-section misc-pub-section-last"><label><input type="checkbox" value="1" <?php checked($sticky, true, true); ?> name="_sticky_blog_post" />Make this post sticky</label></div>
            <?php
        }
    }

    public function saveStickyPostStatus($postID)
    {
        if (!isset($_POST['_sticky_blog_post_nonce'])) {
            return;
        }
        if (!wp_verify_nonce($_POST['_sticky_blog_post_nonce'], '_sticky_blog_post_nonce_' . $postID)) {
            return;
        }
        if (!current_user_can('edit_post', $postID)) {
            return;
        }
        if (isset($_POST['_sticky_blog_post'])) {
            update_post_meta($postID, '_sticky_blog_post', $_POST['_sticky_blog_post']);
        } else {
            delete_post_meta($postID, '_sticky_blog_post');
        }
    }
}
