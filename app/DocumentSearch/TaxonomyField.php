<?php

namespace NHSEngland\DocumentSearch;

abstract class TaxonomyField implements SearchField
{
    public function __construct(\Dxw\Iguana\Value\Get $__get)
    {
        $this->get = $__get;
    }

    protected function getValue(): string
    {
        if (!isset($this->get[static::PARAMETER])) {
            return '';
        }

        if ($this->get[static::PARAMETER] === '') {
            return '';
        }

        $terms = get_terms([
            'taxonomy' => static::TAXONOMY,
            'hide_empty' => true,
            'slug' => $this->get[static::PARAMETER],
        ]);

        if (count($terms) === 0) {
            return '';
        }

        return $this->get[static::PARAMETER];
    }

    protected function getOptions(): array
    {
        $raw = get_terms([
            'taxonomy' => static::TAXONOMY,
            'hide_empty' => true,
        ]);

        if ($raw instanceof \WP_Error) {
            trigger_error('get_terms returned an error', E_USER_WARNING);
        }

        $terms = [];

        foreach ($raw as $term) {
            $terms[$term->slug] = $term->name;
        }

        return $terms;
    }

    public function modifyQuery(\WP_Query $query)
    {
        $value = $this->getValue();
        if ($value === '') {
            return;
        }

        $existingQuery = $query->get('tax_query');
        if ($existingQuery === '') {
            $query->set('tax_query', [
                'relation' => 'AND',
                [
                    'taxonomy' => static::TAXONOMY,
                    'field' => 'slug',
                    'terms' => [$value],
                ],
            ]);
        } else {
            $existingQuery[] = [
                'taxonomy' => static::TAXONOMY,
                'field' => 'slug',
                'terms' => [$value],
            ];
            $query->set('tax_query', $existingQuery);
        }
    }

    public function renderHTML(): string
    {
        $s = [];

        foreach ($this->getOptions() as $key => $value) {
            $selected = '';
            if (isset($this->get[static::PARAMETER]) && $this->get[static::PARAMETER] === $key) {
                $selected = ' selected';
            }
            $s[] = '<option value="'.esc_attr($key).'"'.$selected.'>'.esc_html($value).'</option>';
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
