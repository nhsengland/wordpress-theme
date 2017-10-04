<?php

describe(\NHSEngland\PostsToNews::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->postsToNews = new \NHSEngland\PostsToNews();
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->postsToNews)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the action', function () {
            \WP_Mock::expectActionAdded('init', [$this->postsToNews, 'postsToNews']);
            $this->postsToNews->register();
        });
    });

    describe('->postsToNews()', function () {
        beforeEach(function () {
            global $wp_post_types;
            $wp_post_types['post'] = new stdClass();
            $wp_post_types['post']->labels = new stdClass();
            $labels = $wp_post_types['post']->labels;
            $labels->name = 'Post';
            $labels->singular_name = 'Post';
            $labels->add_new = 'Add Post';
            $labels->add_new_item = 'Add Post';
            $labels->edit_item = 'Edit Post';
            $labels->new_item = 'Post';
            $labels->view_item = 'View Post';
            $labels->search_items = 'Search Posts';
            $labels->not_found = 'No Posts found';
            $labels->not_found_in_trash = 'No Posts found in Trash';
            $labels->all_items = 'All Posts';
            $labels->menu_name = 'Posts';
            $labels->name_admin_bar = 'Posts';
        });
        it('changes the labels', function () {
            $this->postsToNews->postsToNews();
            global $wp_post_types;
            $labels = $wp_post_types['post']->labels;
            expect($labels->name)->to->equal('News');
            expect($labels->singular_name)->to->equal('News');
            expect($labels->add_new)->to->equal('Add News');
            expect($labels->add_new_item)->to->equal('Add News');
            expect($labels->edit_item)->to->equal('Edit News');
            expect($labels->new_item)->to->equal('News');
            expect($labels->view_item)->to->equal('View News');
            expect($labels->search_items)->to->equal('Search News');
            expect($labels->not_found)->to->equal('No News found');
            expect($labels->not_found_in_trash)->to->equal('No News found in Trash');
            expect($labels->all_items)->to->equal('All News');
            expect($labels->menu_name)->to->equal('News');
            expect($labels->name_admin_bar)->to->equal('News');
        });
    });
});
