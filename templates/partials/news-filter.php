<aside class="sidebar sidebar-filters">

    <?php if (is_single()) : ?>
        <header>
            <h2>Search news</h2>
        </header>
    <?php endif; ?>

    <p>You can use the filters to show only news items that match your interests</p>
    <form class="filters" action="<?php echo esc_url(get_permalink(get_option('page_for_posts')))?>" method="GET">

        <div class="filter-group">
            <?php h()->renderDocumentSearchField('keyword') ?>
        </div>

        <div class="filter-group">
            <?php h()->renderDocumentSearchField('category') ?>
        </div>

        <fieldset>
            <legend>Date range</legend>

            <div class="filter-group">
                <?php h()->renderDocumentSearchField('dateFrom') ?>
            </div>

            <div class="filter-group">
                <?php h()->renderDocumentSearchField('dateTo') ?>
            </div>

        </fieldset>

        <input type="submit" value="Search">
        <input type="reset" value="Reset" onclick="document.location.href=('<?php echo esc_url(get_permalink(get_option('page_for_posts'))) ?>')">

    </form>
</aside>
