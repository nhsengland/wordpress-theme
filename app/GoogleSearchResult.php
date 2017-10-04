<?php

namespace NHSEngland;

class GoogleSearchResult
{
    const RESOLUTION_ERROR = 1;
    const RESOLUTION_SEARCH_PERFORMED = 2;
    const RESOLUTION_NO_SEARCH_PERFORMED = 3;

    public $resolution;
    public $q;
    public $pagination;
    public $results;
    public $total_results;

    public function __construct(
        $resolution,
        $q,
        $pagination,
        $results,
        $total_results
    ) {
        $this->resolution = $resolution;
        $this->q = $q;
        $this->pagination = $pagination;
        $this->results = $results;
        $this->total_results = $total_results;
    }
}
