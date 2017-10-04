<?php

namespace NHSEngland\DocumentSearch;

class FieldHelper
{
    public function __construct(
        \Dxw\Iguana\Theme\Helpers $helpers,
        KeywordField $keywordField,
        CategoryField $categoryField,
        PublicationField $publicationField,
        DateFromField $dateFromField,
        DateToField $dateToField
    ) {
        $helpers->registerFunction('renderDocumentSearchField', [$this, 'renderHTML']);
        $this->fields = [
            'keyword' => $keywordField,
            'category' => $categoryField,
            'publication' => $publicationField,
            'dateFrom' => $dateFromField,
            'dateTo' => $dateToField,
        ];
    }

    public function renderHTML(string $field)
    {
        if (!isset($this->fields[$field])) {
            trigger_error('unknown field', E_USER_ERROR);
        }

        echo $this->fields[$field]->renderHTML();
    }
}
