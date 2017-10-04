<?php

namespace NHSEngland\DocumentSearch;

class KeywordField implements SearchField
{
    public function __construct(\Dxw\Iguana\Value\Get $__get)
    {
        $this->get = $__get;
    }

    private function getValue(): string
    {
        if (isset($this->get['filter-keyword'])) {
            return $this->get['filter-keyword'];
        }

        return '';
    }

    public function modifyQuery(\WP_Query $query)
    {
        $query->set('s', $this->getValue());
    }

    public function renderHTML(): string
    {
        return (
            '<label for="filter-keyword">Keyword</label>'.
            '<input type="search" id="filter-keyword" name="filter-keyword" value="'.esc_attr($this->getValue()).'">'
        );
    }
}
