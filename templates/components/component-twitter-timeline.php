<?php if (get_row_layout() === 'twitter_timeline_component') : ?>

    <div class="row">
        <?php if (get_sub_field('section_title')): ?>
            <h2><?php the_sub_field('section_title') ?></h2>
        <?php else : ?>
            <h2>Join the conversation</h2>
        <?php endif; ?>
        <div class="component-twitter-sub-sections">

            <?php
            $hashtag = get_sub_field('twitter_hashtag');
            $tck = get_sub_field('twitter_consumer_key');
            $tcs = get_sub_field('twitter_consumer_secret');
            $toat = get_sub_field('twitter_oauth_access_token');
            $tosts = get_sub_field('twitter_oauth_access_token_secret');
            ?>


            <?php if (get_sub_field('timeline_type') === 'hashtag'): ?>


                <?php $hashtag_instances = [
                    'hashtag' => $hashtag,
                    'consumer_key' => $tck,
                    'consumer_secret' => $tcs,
                    'oauth_token' => $toat,
                    'oauth_token_secret' => $tosts,
                    'results' => 3,
                ] ?>

                <?php the_widget('Twitter_Hashtag_Feed_Widget', $hashtag_instances); ?>

            <?php elseif (get_sub_field('timeline_type') === 'account'): ?>

                <?php echo do_shortcode('[twitget]'); ?>            

            <?php endif; ?>

        </div>
    </div>

<?php endif; ?>
