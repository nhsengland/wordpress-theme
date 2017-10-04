<!DOCTYPE HTML>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
    <script type="text/javascript">
        var addthis_config =
        {
            ui_tabindex:0
        }
    </script>
    <meta charset="utf-8">
    <meta http-equiv="cleartype" content="on">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="author" content="<?php bloginfo('name'); ?>">

    <link rel="alternate" type="application/rss+xml" title="RSS2.0" href="<?php bloginfo('rss2_url'); ?>" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <?php wp_head(); ?>

    <?php
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    ?>
</head>
<body <?php body_class("group"); ?>>
    <div class="skip-link">
        <a href="#main-content" tabindex="1">Skip to main content</a>
    </div>
    <div class="cookie-message hide" id="js-cookie-message">
        <div class="row">
            <p>NHS England uses cookies to make the site simpler. <a href="https://www.england.nhs.uk/privacy-policy/">Find out more about cookies</a> <a href="#" class="cookie-accept" id="js-cookie-accept">Accept</a></p>
        </div>
    </div>

    <div class="top-nav-container group">
        <div class="row group">
            <nav class="secondary-navigation" role="navigation">
                <?php if (has_nav_menu('secondary')) : ?>
                    <?php wp_nav_menu([
                        'theme_location' => 'secondary',
                        'container' => '',
                        'menu_class' => 'nav-menu',
                        'depth' => '1'
                    ]);
                    ?>
                <?php endif; ?>
            </nav>
        </div>
    </div>

    <header class="header group" role="banner">
        <div class="row group">

            <div class="logo">

                <?php $newMainLogo = get_field('replace_header_logo', 'options'); if ($newMainLogo) : ?>
                <a href="<?php bloginfo('url'); ?>">
                    <img src="<?php echo $newMainLogo; ?>" alt="<?php bloginfo('name'); ?>" />
                </a>
            <?php else : ?>
                <a href="/">
                    <img src="<?php h()->assetPath('img/nhs-england-logo-rev.svg') ?>" onerror="this.src='<?php h()->assetPath('img/nhs-england-logo-rev.png') ?>'; this.onerror=null;" alt="<?php bloginfo('name'); ?>" />
                </a>
            <?php endif; ?>

        </div>

        <div class="header-search">
            <?php get_template_part('searchform'); ?>
        </div>

    </div>

    <div class="nav-container group">
        <button type="button" href="#" class="nav-toggle" id="js-nav-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18"><path class="menu-icon" fill="#ffffff" d="M.1 3.6C0 2.7 0 1.7.1.7.1.3.5 0 1 0h16.1c.5 0 .9.3.9.7.1 1 .1 1.9 0 2.9 0 .4-.4.7-.9.7H1c-.5 0-.9-.3-.9-.7zm.9 7.6c-.5 0-.9-.3-.9-.7-.1-1-.1-2 0-2.9 0-.4.4-.7.9-.7h16.1c.5 0 .9.3.9.7.1 1 .1 1.9 0 2.9 0 .4-.4.7-.9.7H1zM1 18c-.5 0-.9-.3-.9-.7-.1-1-.1-1.9 0-2.9 0-.4.4-.7.9-.7h16.1c.5 0 .9.3.9.7.1 1 .1 1.9 0 2.9 0 .4-.4.7-.9.7H1z"/></svg>
            <span>Menu</span>
        </button>
        <nav class="navigation group" role="navigation">
            <?php if (has_nav_menu('primary')) : ?>
                <?php wp_nav_menu([
                    'theme_location' => 'primary',
                    'container' => '',
                    'menu_class' => 'nav-menu',
                    'depth' => '1'
                ]);
                ?>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="main group" role="main">
