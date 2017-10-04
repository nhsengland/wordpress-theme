<?php

namespace NHSEngland;

class GoogleSearch implements \Dxw\Iguana\Registerable
{
    const GOOGLE_ORDER_BYS = [
        'date-desc' => [
            'page' => 'metatags-pubdate',
            'doc'  => 'date'
        ],
        'date-asc' => [
            'page' => 'metatags-pubdate:a',
            'doc' => 'date:a'
        ]
    ];

    public function __construct(\Dxw\Iguana\Theme\Helpers $helpers)
    {
        $helpers->registerFunction('searchDoIt', [$this, 'doIt']);
        $helpers->registerFunction('searchMain', [$this, 'searchMain']);
        $helpers->registerFunction('searchFilter', [$this, 'searchFilter']);
    }

    public function register()
    {
        // Always show the search, even if there are no results
        add_filter('template_include', [$this, 'templateInclude']);
    }

    public function templateInclude(string $template): string
    {
        global $wp_query;

        if (\Missing\Strings::endsWith($template, '/404.php') && isset($wp_query->query['s'])) {
            //change the status code back to 200, and amend wp_query to reflect changes
            status_header(200);
            $wp_query->is_404 = false;
            $wp_query->is_search = true;
            return locate_template('search.php');
        }

        return $template;
    }

    public function googleSearch($q, $page, $order_by): \Dxw\Result\Result
    {
        if (!(defined('NHSENGLAND_GOOGLESEARCH_CX') && defined('NHSENGLAND_GOOGLESEARCH_KEY'))) {
            return \Dxw\Result\Result::err('undefined constants');
        }

        $num = 10;
        $start_index = (($page - 1) * $num) + 1;

        $url = add_query_arg(
            [
                'cx' => urlencode(NHSENGLAND_GOOGLESEARCH_CX),
                'key' => urlencode(NHSENGLAND_GOOGLESEARCH_KEY),
                'q' => urlencode($q),
                'num' => urlencode($num),
                'start' => urlencode($start_index),
                'sort' => $order_by
            ],
            'https://www.googleapis.com/customsearch/v1'
        );
        $resp = wp_remote_request($url);
        if (is_wp_error($resp)) {
            return \Dxw\Result\Result::err('got WP error: '.$resp->get_error_message());
        }

        if ($resp['response']['code'] !== 200) {
            return \Dxw\Result\Result::err('non-200 response');
        }

        $data = json_decode($resp['body'], true);
        if ($data === null) {
            return \Dxw\Result\Result::err('json_decode returned null');
        }

        return \Dxw\Result\Result::ok([$data, $start_index]);
    }

    // Template

    public function searchMain($query)
    {
        $documents_only = isset($_GET['documents']) && $_GET['documents'] === 'yes'; ?>
        <form>
            <div class="search-page-form">
                <div class="search-box">
                    <input type="text" name="s" value="<?php echo esc_attr($query) ?>" class="search">
                    <input type="submit" value="Search" class="button hidden-phone">
                </div>
                <input type="submit" value="Search" class="button visible-phone">
                <div class="search-select">
                    <label class="checkbox">
                        <input type="checkbox" name="documents" value="yes" <?php echo $documents_only ? 'checked' : '' ?>>
                        Show only documents
                    </label>
                </div>
            </div>
        </form>
        <?php
    }

    public function searchFilter($query)
    {
        $documents_only = isset($_GET['documents']) && $_GET['documents'] === 'yes';
        $order_by = $_GET['order-by']; ?>
        <aside class="sidebar sidebar-filters">
            <p>You can use the filters to show only results that match your interests</p>

            <form class="filters">

                <div class="filter-group">
                    <label for="s">Keyword(s)</label>
                    <input type="text" name="s" id="s" value="<?php echo esc_attr($query) ?>" class="search">
                </div>

                <div class="filter-group">
                    <label class="checkbox checkbox-group">
                        <input type="checkbox" name="documents" value="yes" <?php echo $documents_only ? 'checked' : '' ?>>
                        Show only documents
                    </label>
                </div>

                <div class="filter-group">
                    <label for="order-by">Order by</label>
                    <select id="order-by" name="order-by">
                        <option value="relevance" <?php selected($order_by, "relevance")?> >
                            Relevance
                        </option>
                        <option value="date-desc" <?php selected($order_by, "date-desc")?> >
                            Date (Newest first)
                        </option>
                        <option value="date-asc" <?php selected($order_by, "date-asc")?> >
                            Date (Oldest first)
                        </option>
                    </select>
                </div>

                <input type="submit" value="Search">

            </form>
        </aside>
        <?php
    }

    public function doIt()
    {
        global $wp_query;

        $q = $wp_query->query_vars['s'];
        $paged = absint($wp_query->query_vars['paged']);
        if ($paged < 1) {
            $paged = 1;
        }

        // Google search doesn't return more than 1000 results, so redirect
        if ($paged > 100) {
            wp_redirect(add_query_arg([
                's' => $wp_query->query_vars['s'],
            ], '/'));
            exit();
        }

        $actual_q = $q;

        $documents_only = isset($_GET['documents']) && $_GET['documents'] === 'yes';

        if ($documents_only) {
            $actual_q .= ' inurl:wp-content/uploads';
        }

        $order_by = $_GET['order-by'];
        if (array_key_exists($order_by, self::GOOGLE_ORDER_BYS) && $documents_only) {
            $q_order_by = self::GOOGLE_ORDER_BYS[$order_by]['doc'];
        } elseif (array_key_exists($order_by, self::GOOGLE_ORDER_BYS) && !$documents_only) {
            $q_order_by = self::GOOGLE_ORDER_BYS[$order_by]['page'];
        } else {
            $q_order_by = null;
        }

        $err = null;
        $results = [];
        if ($actual_q !== '') {
            $result = $this->googleSearch($actual_q, $paged, $q_order_by);
            if ($result->isErr()) {
                return new GoogleSearchResult(
                    GoogleSearchResult::RESOLUTION_ERROR,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null
                );
            }

            list($results, $start_index) = $result->unwrap();

            // If we're on a page beyond Google's actual results, redirect
            if (
                isset($results['queries']) &&
                isset($results['queries']['request']) &&
                isset($results['queries']['request'][0]) &&
                isset($results['queries']['request'][0]['startIndex']) &&
                $results['queries']['request'][0]['startIndex'] !== $start_index
            ) {
                wp_redirect(add_query_arg([
                    's' => $wp_query->query_vars['s'],
                ], '/'));
                exit();
            }

            $total_results = $results['searchInformation']['totalResults'];
            $args = ['s' => $q];

            if ($documents_only) {
                $args['documents'] = 'yes';
            }

            if ($q_order_by) {
                $args['order-by'] = $order_by;
            }

            $max = ceil($total_results / 10);
            if (absint($max) === 0) {
                $max = 1;
            }
            $pagination = new \Dxw\Pagination($paged, $max, 1, 0, function ($n) use ($args) {
                $args['paged'] = $n;
                return add_query_arg($args, site_url());
            });


            return new GoogleSearchResult(
                GoogleSearchResult::RESOLUTION_SEARCH_PERFORMED,
                $q,
                $pagination,
                $results,
                $total_results
            );
        }

        return new GoogleSearchResult(
            GoogleSearchResult::RESOLUTION_NO_SEARCH_PERFORMED,
            $q,
            null,
            null,
            null
        );
    }
}
