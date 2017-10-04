<?php

namespace NHSEngland;

class RelevanssiStopDisplayCommonWords implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_filter('relevanssi_display_common_words', [$this, 'stopDisplayCommonWords'], 10, 1);
    }

    /* By default, Relevanssi displays commonly indexed words on its setting page.
    With a big index, the query for this causes the settings page to bring the whole site down.
    So we're switching it off. */
    public function stopDisplayCommonWords()
    {
        return false;
    }
}
