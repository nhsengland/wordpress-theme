<aside class="sidebar sidebar-filters">
    <p>You can use the filters to show only blog posts that match your interests</p>
    <form class="filters" action="<?php echo esc_url(get_post_type_archive_link('blog'))?>" method="GET">

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
        <input type="reset" value="Reset" onclick="document.location.href=('<?php echo esc_url(get_post_type_archive_link('blog'))?>')">

    </form>
</aside>
