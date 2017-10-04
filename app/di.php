<?php

$registrar->addInstance(new \Dxw\Iguana\Theme\Helpers());
$registrar->addInstance(new \NHSEngland\CategoryTaxonomyModification());
$registrar->addInstance(new \NHSEngland\OptionalMetadata());
$registrar->addInstance(new \NHSEngland\AuthorArchive());
$registrar->addInstance(new \NHSEngland\DisplayName());
$registrar->addInstance(new \NHSEngland\GuestAuthors());
$registrar->addInstance(new \NHSEngland\GoogleSearch(
    $registrar->getInstance(\Dxw\Iguana\Theme\Helpers::class)
));
$registrar->addInstance(new \NHSEngland\GoogleSearchMetadata());
$registrar->addInstance(new \NHSEngland\GoogleSearchSnippet(
    $registrar->getInstance(\Dxw\Iguana\Theme\Helpers::class)
));
$registrar->addInstance(new \NHSEngland\CategoryFields());
$registrar->addInstance(new \NHSEngland\LandingPageFields());
$registrar->addInstance(new \NHSEngland\FlexibleComponentFields());
$registrar->addInstance(new \NHSEngland\DisableTagsArchive());
$registrar->addInstance(new \NHSEngland\DocumentContainer(
    $registrar->getInstance(\Dxw\Iguana\Theme\Helpers::class)
));
$registrar->addInstance(new \NHSEngland\DocumentContainerIcons(
    $registrar->getInstance(\Dxw\Iguana\Theme\Helpers::class)
));
$registrar->addInstance(new \NHSEngland\DocumentContainerTaxonomyLinks());

$registrar->addInstance(new \NHSEngland\BlogPostType());
$registrar->addInstance(new \NHSEngland\BlogLandingPage());

$registrar->addInstance(new \NHSEngland\NewsCategoryLinks(
    $registrar->getInstance(\Dxw\Iguana\Theme\Helpers::class)
));

$registrar->addInstance(new \NHSEngland\HideCategories());

$registrar->addInstance(new \NHSEngland\SubNav());
$registrar->addInstance(new \NHSEngland\FilterMediaByFileType());
$registrar->addInstance(new \NHSEngland\RelevanssiStopDisplayCommonWords());
$registrar->addInstance(new \NHSEngland\PostsToNews());
$registrar->addInstance(new \NHSEngland\CoAuthorCountPostTypes());
$registrar->addInstance(new \NHSEngland\DisableAttachmentComments());
$registrar->addInstance(new \NHSEngland\RemoveCommentUrlField());
$registrar->addInstance(new \NHSEngland\RemoveCommentAuthorLink());
$registrar->addInstance(new \NHSEngland\DxwContentReviewPostTypes());
$registrar->addInstance(new \NHSEngland\SiteSearchOption());
$registrar->addInstance(new \NHSEngland\AcfFields());

// Document search field types

$registrar->addInstance(new \NHSEngland\DocumentSearch\KeywordField(
    $registrar->getInstance(\Dxw\Iguana\Value\Get::class)
));
$registrar->addInstance(new \NHSEngland\DocumentSearch\CategoryField(
    $registrar->getInstance(\Dxw\Iguana\Value\Get::class)
));
$registrar->addInstance(new \NHSEngland\DocumentSearch\PublicationField(
    $registrar->getInstance(\Dxw\Iguana\Value\Get::class)
));
$registrar->addInstance(new \NHSEngland\DocumentSearch\DateFromField(
    $registrar->getInstance(\Dxw\Iguana\Value\Get::class)
));
$registrar->addInstance(new \NHSEngland\DocumentSearch\DateToField(
    $registrar->getInstance(\Dxw\Iguana\Value\Get::class)
));
$registrar->addInstance(new \NHSEngland\NewsHomepage());

// Document search etc

$registrar->addInstance(new \NHSEngland\DocumentSearch\FieldHelper(
    $registrar->getInstance(\Dxw\Iguana\Theme\Helpers::class),
    $registrar->getInstance(\NHSEngland\DocumentSearch\KeywordField::class),
    $registrar->getInstance(\NHSEngland\DocumentSearch\CategoryField::class),
    $registrar->getInstance(\NHSEngland\DocumentSearch\PublicationField::class),
    $registrar->getInstance(\NHSEngland\DocumentSearch\DateFromField::class),
    $registrar->getInstance(\NHSEngland\DocumentSearch\DateToField::class)
));
$registrar->addInstance(new \NHSEngland\DocumentSearch\Filtering(
    $registrar->getInstance(\NHSEngland\DocumentSearch\KeywordField::class),
    $registrar->getInstance(\NHSEngland\DocumentSearch\CategoryField::class),
    $registrar->getInstance(\NHSEngland\DocumentSearch\PublicationField::class),
    $registrar->getInstance(\NHSEngland\DocumentSearch\DateFromField::class),
    $registrar->getInstance(\NHSEngland\DocumentSearch\DateToField::class)
));

// Theme behaviour, media, assets and template tags
$registrar->addInstance(\NHSEngland\Theme\WpHead::class, new \NHSEngland\Theme\WpHead());
$registrar->addInstance(\NHSEngland\Theme\Widgets::class, new \NHSEngland\Theme\Widgets());
$registrar->addInstance(\NHSEngland\Theme\Scripts::class, new \NHSEngland\Theme\Scripts(
    $registrar->getInstance(\Dxw\Iguana\Theme\Helpers::class)
));
$registrar->addInstance(\NHSEngland\Theme\Breadcrumbs::class, new \NHSEngland\Theme\Breadcrumbs(
    $registrar->getInstance(\Dxw\Iguana\Theme\Helpers::class)
));
$registrar->addInstance(\NHSEngland\Theme\Author::class, new \NHSEngland\Theme\Author(
    $registrar->getInstance(\Dxw\Iguana\Theme\Helpers::class)
));
$registrar->addInstance(\NHSEngland\Theme\Pagination::class, new \NHSEngland\Theme\Pagination(
    $registrar->getInstance(\Dxw\Iguana\Theme\Helpers::class)
));
$registrar->addInstance(\NHSEngland\Theme\CommentModerationNotification::class, new \NHSEngland\Theme\CommentModerationNotification());
$registrar->addInstance(\NHSEngland\Theme\Menus::class, new \NHSEngland\Theme\Menus());
$registrar->addInstance(\NHSEngland\Theme\PostThumbnails::class, new \NHSEngland\Theme\PostThumbnails());
$registrar->addInstance(\NHSEngland\Theme\Ratings::class, new \NHSEngland\Theme\Ratings());
$registrar->addInstance(\NHSEngland\Theme\ColourCustomization::class, new \NHSEngland\Theme\ColourCustomization());
