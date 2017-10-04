<?php

namespace NHSEngland\DocumentSearch;

class CategoryField extends TaxonomyField
{
    const PARAMETER = 'filter-category';
    const NAME = 'Topic';
    const NAMEPLURAL = 'Topics';
    const TAXONOMY = 'category';

    protected function getOptions(): array
    {
        $rawPriorityTerms = get_field('priority_topics', 'options');

        $priorityTerms = [];

        $priorityTermIds = [];

        if (!empty($rawPriorityTerms)) {
            foreach ($rawPriorityTerms as $priorityTerm) {
                $priorityTerms[$priorityTerm->slug] = $priorityTerm->name;
                $priorityTermIds[] = $priorityTerm->term_id;
            }
        }

        $raw = get_terms([
            'taxonomy' => static::TAXONOMY,
            'hide_empty' => true,
            'exclude' => $priorityTermIds
        ]);

        if ($raw instanceof \WP_Error) {
            trigger_error('get_terms returned an error', E_USER_WARNING);
        }

        $terms = [];

        foreach ($raw as $term) {
            $terms[$term->slug] = $term->name;
        }

        $allTerms = [
            'priority' => $priorityTerms,
            'standard' => $terms
        ];

        return $allTerms;
    }

    private function termArrayToOptions($terms)
    {
        foreach ($terms as $key => $value) {
            $selected = '';
            if (isset($this->get[static::PARAMETER]) && $this->get[static::PARAMETER] === $key) {
                $selected = ' selected';
            }
            $s[] = '<option value="'.esc_attr($key).'"'.$selected.'>'.esc_html($value).'</option>';
        }
        return $s;
    }

    public function renderHTML(): string
    {
        $s = [];

        $terms = $this->getOptions();

        if (!empty($terms['priority'])) {
            $s[] = '<optgroup label="Priority areas">';
            $s = array_merge($s, $this->termArrayToOptions($terms['priority']));
            $s[] = '</optgroup>';
            $s[] = '<optgroup label="All ' . strtolower(static::NAMEPLURAL) . '">';
        }

        $s = array_merge($s, $this->termArrayToOptions($terms['standard']));

        if (!empty($terms['priority'])) {
            $s[] = '</optgroup>';
        }

        return (
            '<label for="'.esc_attr(static::PARAMETER).'">'.esc_html(static::NAME).'</label>'.
            '<select id="'.esc_attr(static::PARAMETER).'" name="'.esc_attr(static::PARAMETER).'">'.
            '<option value="">Select ' .
            strtolower(static::NAME) . '</option>'.
            implode('', $s).
            '</select>'
        );
    }
}
