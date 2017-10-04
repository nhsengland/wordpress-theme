<?php

namespace NHSEngland\DocumentSearch;

interface SearchField
{
    public function __construct(\Dxw\Iguana\Value\Get $__get);
    public function modifyQuery(\WP_Query $query);
    public function renderHTML(): string;
}
