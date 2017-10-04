<?php

namespace NHSEngland;

class DxwContentReviewPostTypes implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        add_filter('dxw_content_review_post_types', function ($post_type) {
            return ['page', 'attachment'];
        });
    }
}
