<?php
//
// Component ### Video
//
if (get_row_layout() === 'video_component') :

    $fullpath = get_sub_field('youtube_link');
    $videoid = preg_split('/(\?[v]\=)/', $fullpath);
    $video_size = get_sub_field('video_size');
    ?>

    <div class="row">
        <div class="video-component <?php echo $video_size; ?>">

            <div class="video-component-frame">
                <div class="video-player">
                    <div class="video-frame">
                        <div class="fluid-width-video-wrapper" style="padding-top: 56.25%;">
                            <iframe src="https://www.youtube.com/embed/<?php echo esc_attr($videoid[1]) ?>?feature=oembed&rel=0" frameborder="0" allowfullscreen id="fitvid972597"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <article class="video-component-caption">
                <?php if (get_sub_field('video_title')): ?>
                    <h2><?php the_sub_field('video_title') ?></h2>
                <?php endif; ?>

                <?php if (get_sub_field('content')): ?>
                    <div class="rich-text content">
                        <?php the_sub_field('content') ?>
                    </div>
                <?php endif; ?>

            </article>

        </div>
    </div>

<?php endif; ?>
