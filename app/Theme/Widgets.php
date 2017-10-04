<?php

namespace NHSEngland\Theme;

class Widgets implements \Dxw\Iguana\Registerable
{
    /**
     * Register our sidebars and widgetised areas.
     *
     */

    public function register()
    {
        add_action('widgets_init', [$this, 'nhse_widgets_init']);
        add_action('widgets_init', [$this, 'landing_widgets_init']);
        add_action('widgets_init', [$this, 'news_widgets_init']);
    }

    public function nhse_widgets_init()
    {
        register_sidebar([
             'name' => 'Sidebar Widgets',
             'id' => 'nhse_sidebar',
             'before_widget' => '<div class="sidebar-block %2$s">',
             'after_widget' => '</div>',
             'before_title' => '<h4>',
             'after_title' => '</h4>',
         ]);
    }


    public function landing_widgets_init()
    {
        register_sidebar([
             'name' => 'Landing Sidebar Widgets',
             'id' => 'landing_sidebar',
             'before_widget' => '<div class="sidebar-block %2$s">',
             'after_widget' => '</div>',
             'before_title' => '<h4>',
             'after_title' => '</h4>',
         ]);
    }


    public function news_widgets_init()
    {
        register_sidebar([
             'name' => 'News Widgets',
             'id' => 'news_archives',
             'before_widget' => '<div class="sidebar-block">',
             'after_widget' => '</div>',
             'before_title' => '<h4>',
             'after_title' => '</h4>',
         ]);
    }
}
