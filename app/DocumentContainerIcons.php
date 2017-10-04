<?php

namespace NHSEngland;

class DocumentContainerIcons implements \Dxw\Iguana\Registerable
{
    public function __construct(\Dxw\Iguana\Theme\Helpers $helpers)
    {
        $helpers->registerFunction('thumbnailSrc', [$this, 'thumbnailSrc']);
    }

    public function register()
    {
        add_action('wp_insert_post', [$this, 'wpInsertPost']);
        add_action('admin_print_scripts', [$this, 'adminPrintScripts']);
    }

    public function wpInsertPost(/* int */ $p_id)
    {
        while (have_rows('documents', $p_id)) {
            the_row();

            $field = get_sub_field('document');
            if (is_array($field) && $field['mime_type'] === 'application/pdf') {
                update_sub_field('icon', 'pdf');
            } elseif (is_array($field) && $field['mime_type'] === 'text/csv') {
                update_sub_field('icon', 'csv');
            } elseif (is_array($field) && $field['mime_type'] === 'application/msword') {
                update_sub_field('icon', 'doc');
            } elseif (is_array($field) && $field['mime_type'] === 'application/vnd.ms-excel') {
                update_sub_field('icon', 'xls');
            } else {
                update_sub_field('icon', '');
            }
        }
    }

    public function thumbnailSrc()
    {
        $thumbnail = get_sub_field('thumbnail');
        if ($thumbnail !== false) {
            return $thumbnail['url'];
        }

        $icon = get_sub_field('icon');
        if (is_string($icon) && strlen($icon) > 0) {
            return $this->getAssetPath('img/document-icons/'.$icon.'.png');
        }

        return $this->getAssetPath('img/document-icons/file.png');
    }

    private function getAssetPath($path)
    {
        return dirname(get_stylesheet_directory_uri()).'/assets/'.$path;
    }

    public function adminPrintScripts()
    {
        echo '<style>.acf-field-r12uajnnew{display:none}</style>';
    }
}
