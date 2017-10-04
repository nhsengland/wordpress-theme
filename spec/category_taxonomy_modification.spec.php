<?php

describe(\NHSEngland\CategoryTaxonomyModification::class, function () {
    beforeEach(function () {
        \WP_Mock::setUp();
        $this->categoryTaxonomyModification = new NHSEngland\CategoryTaxonomyModification();
        global $wp_taxonomies;
        $wp_taxonomies['category'] = new stdClass();
        $wp_taxonomies['category']->cap = new stdClass();
        $wp_taxonomies['category']->cap->manage_terms = 'manage_categories';
        $wp_taxonomies['category']->cap->edit_terms = 'edit_categories';
        $wp_taxonomies['category']->cap->delete_terms ='delete_categories';
        $wp_taxonomies['category']->cap->assign_terms = 'assign_categories';
    });

    afterEach(function () {
        \WP_Mock::tearDown();
    });

    it('is registerable', function () {
        expect($this->categoryTaxonomyModification)->to->be->instanceof(\Dxw\Iguana\Registerable::class);
    });

    describe('->register()', function () {
        it('adds the action', function () {
            \WP_Mock::expectActionAdded('init', [$this->categoryTaxonomyModification, 'modifyCategoryTaxonomy'], 11);
            $this->categoryTaxonomyModification->register();
        });
    });

    describe('->modifyCategoryTaxonomy', function () {
        it('sets capabilities so only super admins can modify terms', function () {
            $this->categoryTaxonomyModification->modifyCategoryTaxonomy();
            global $wp_taxonomies;
            expect($wp_taxonomies['category']->cap->manage_terms)->to->equal('delete_users');
            expect($wp_taxonomies['category']->cap->edit_terms)->to->equal('delete_users');
            expect($wp_taxonomies['category']->cap->delete_terms)->to->equal('delete_users');
            expect($wp_taxonomies['category']->cap->assign_terms)->to->equal('assign_categories');
        });
    });
});
