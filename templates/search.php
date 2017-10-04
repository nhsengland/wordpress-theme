<?php $searchResults = h()->searchDoIt() ?>

<?php if ($searchResults->resolution === \NHSEngland\GoogleSearchResult::RESOLUTION_ERROR) : ?>

    <?php get_header() ?>

    <div class="row" id="main-content">

        <header>
            <h1> An error occurred while searching </h1>
        </header>

        <div class="search-content group">

            <p>Please try again later.</p>

        </div>

    </div>
    <?php get_footer() ?>

<?php elseif ($searchResults->resolution === \NHSEngland\GoogleSearchResult::RESOLUTION_SEARCH_PERFORMED) : ?>

    <?php get_header() ?>

    <div class="row" id="main-content">


        <?php if ($searchResults->total_results > 0) : ?>

            <header>
                <h1>
                    You searched for: <?php echo esc_html($searchResults->q) ?>
                </h1>
            </header>

            <?php h()->searchFilter($searchResults->q); ?>

            <div class="search-content filtered-list group">

                <?php
                global $wp_query;
                $paged = absint($wp_query->query_vars['paged']);
                if (!$paged) {
                    $paged = 1;
                }

                if (!empty($searchResults->results['promotions']) && is_array($searchResults->results['promotions']) && $paged===1) : ?>
                <?php foreach ($searchResults->results['promotions'] as $item) : ?>

                    <article class="post group promoted">
                        <header>
                            <p role="note">Suggested result</p>
                            <h3><a href="<?php echo esc_attr($item['link']) ?>" rel="bookmark"><?php echo $item['htmlTitle'] ?></a></h3>
                        </header>
                        <?php if ($item['bodyLines']) : ?>
                            <p><?php echo $item['bodyLines'][0]['title'] ?></p>
                        <?php endif; ?>
                    </article>

                <?php endforeach ?>
            <?php endif ?>

            <?php foreach ($searchResults->results['items'] as $item) : ?>

                <article class="post group">
                    <h2><a href="<?php echo esc_attr($item['link']) ?>"><?php echo $item['htmlTitle'] ?></a></h2>
                    <div class="content rich-text">
                        <p>
                            <?php
                            if ($_GET['order-by'] === 'date-desc' || $_GET['order-by'] === 'date-asc') {
                                echo h()->prependDate($item['snippet'], $item);
                            } else {
                                echo $item['snippet'];
                            }
                            ?>
                        </p>
                    </div>
                </article>

            <?php endforeach ?>

            <div class="pager">
                <?php echo $searchResults->pagination->render(); ?>
            </div>

        <?php else : ?>
            <article class="search-content group">
                <h2>No matches found for: <?php echo esc_html($searchResults->q) ?></h2>
                <div class="content rich-text">
                    <p>Sorry, no matches found for that search query, please refine your search.</p>
                    <?php h()->searchMain($searchResults->q) ?>
                </div>
            </article>
        <?php endif ?>

    </div>

</div>

<?php get_footer() ?>

<?php elseif ($searchResults->resolution === \NHSEngland\GoogleSearchResult::RESOLUTION_NO_SEARCH_PERFORMED) : ?>

    <?php get_header() ?>

    <div class="row" id="main-content">
        <header>
            <h1>Search</h1>
        </header>
        <div class="search-content group">
            <?php h()->searchMain($searchResults->q) ?>
        </div>
    </div>

    <?php get_footer() ?>

<?php else : ?>

    <?php trigger_error('This should not be happening', E_USER_ERROR) ?>

<?php endif ?>
