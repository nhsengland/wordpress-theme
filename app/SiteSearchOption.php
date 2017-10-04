<?php

namespace NHSEngland;

class SiteSearchOption implements \Dxw\Iguana\Registerable
{
    public function register()
    {
        // Allow enabling/disabling search on different sites

        // Option
        add_action('wpmueditblogaction', function ($id) {
            ?>
            <tr class="form-field">
                <th scope="row">Show results from this site in searches</th>
                <td><label><input name="search_enabled" type="checkbox" value="yes" <?php echo get_blog_option($id, 'search_enabled') === 'yes' ? 'checked' : '' ?>> Yes</label></td>
            </tr>
            <?php
        });

        // Update the blog option
        // Action is fired after nonce checks
        add_action('wpmu_update_blog_options', function () {
            if (isset($_POST['search_enabled']) && $_POST['search_enabled'] === 'yes') {
                update_option('search_enabled', 'yes');
            } else {
                update_option('search_enabled', 'no');
            }
        });
    }
}
