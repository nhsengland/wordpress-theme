<?php

namespace NHSEngland;

use Dxw\Iguana\Registerable;

class FilterMediaByFileType implements Registerable
{
    public function register()
    {
        add_filter('post_mime_types', [$this, 'modifyPostMimeTypes']);
    }

    public function modifyPostMimeTypes($post_mime_types)
    {
        //Using mime types from https://codex.wordpress.org/Function_Reference/get_allowed_mime_types

        $post_mime_types['application/pdf'] = [
            __('PDFs'),
            __('Manage PDFs'),
            _n_noop('PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>')
        ];
        //xla,xls,xlt,xlw,xlsx,xlsm,xlsb,xltx,xltm,xlam
        $post_mime_types['*ms-excel*,*spreadsheetml*'] = [
            __('Spreadsheets'),
            __('Manage Spreadsheets'),
            _n_noop('Spreadsheet <span class="count">(%s)</span>', 'Spreadsheets <span class="count">(%s)</span>')
        ];
        //doc,docx,docm,dotx,dotm
        $post_mime_types['*msword*,*ms-word*,*wordprocessingml*'] = [
            __('Word docs'),
            __('Manage Word docs'),
            _n_noop('Word doc <span class="count">(%s)</span>', 'Word docs <span class="count">(%s)</span>')
        ];
        //csv
        $post_mime_types['text/csv'] = [
            __('CSVs'),
            __('Manage CSVs'),
            _n_noop('CSV <span class="count">(%s)</span>', 'CSVs <span class="count">(%s)</span>')
        ];
        //pot,pps,ppt,pptx,pptm,ppsx,ppsm,potx,pot,ppam,sldx,sldm
        $post_mime_types['*ms-powerpoint*,*presentationml*'] = [
            __('PowerPoint presentations'),
            __('Manage PowerPoint presentations'),
            _n_noop('PowerPoint presentation <span class="count">(%s)</span>', 'PowerPoint presentations <span class="count">(%s)</span>')
        ];
        //zip
        $post_mime_types['application/zip'] = [
            __('Zip files'),
            __('Manage Zip files'),
            _n_noop('Zip file <span class="count">(%s)</span>', 'Zip files <span class="count">(%s)</span>')
        ];

        return $post_mime_types;
    }
}
