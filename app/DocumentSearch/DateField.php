<?php

namespace NHSEngland\DocumentSearch;

abstract class DateField implements SearchField
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

        if (!preg_match('_^\d{4}-\d{2}-\d{2}$_', $this->get[static::PARAMETER])) {
            return '';
        }

        return $this->get[static::PARAMETER];
    }

    public function modifyQuery(\WP_Query $query)
    {
        $value = $this->getValue();
        if ($value === '') {
            return;
        }

        $existingQuery = $query->get('date_query');
        if ($existingQuery === '') {
            $query->set('date_query', [
                'inclusive' => true,
                static::QUERY => $value,
            ]);
        } else {
            $existingQuery['before'] = $value;
            $query->set('date_query', $existingQuery);
        }
    }

    public function renderHTML(): string
    {
        return (
            '<label for="'.esc_attr(static::PARAMETER).'">'.esc_html(static::NAME).'</label>'.
            '<input type="date" id="'.esc_attr(static::PARAMETER).'" name="'.esc_attr(static::PARAMETER).'" value="'.esc_attr($this->getValue()).'">'
        );
    }
}
