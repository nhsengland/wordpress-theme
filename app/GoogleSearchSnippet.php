<?php

namespace NHSEngland;

class GoogleSearchSnippet implements \Dxw\Iguana\Registerable
{
    const DATE_FORMAT = '/^[A-Z][a-z]{2} \d{1,2}, \d{4}/';

    public function __construct(\Dxw\Iguana\Theme\Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function register()
    {
        $this->helpers->registerFunction('prependDate', [$this, 'prependDate']);
    }

    public function prependDate(String $snippet, $item)
    {
        if (preg_match(self::DATE_FORMAT, $snippet) === 1) {
            return $snippet;
        } elseif ($this->getPubDate($item) === null) {
            return $snippet;
        } else {
            $date = new \DateTime($this->getPubDate($item));
            if (preg_match('/^\.\.\./', $snippet) === 1) {
                $joiner = ' ';
            } else {
                $joiner = ' ... ';
            }
            return $date->format('M j, Y') . $joiner . $snippet;
        }
    }

    public function getPubDate($item)
    {
        if (isset($item['pagemap']['metatags'])) {
            foreach ($item['pagemap']['metatags'] as $meta) {
                if (array_key_exists('pubdate', $meta)) {
                    return $meta['pubdate'];
                }
            }
        }
    }
}
