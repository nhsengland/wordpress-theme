<?php
//
// Content Block ### Video
//
if (get_row_layout() === 'video') :

    $fullpath = get_sub_field('youtube_link');
    $videoid = preg_split('/(\?[v]\=)/', $fullpath);
    ?>
    <div class="row">
        <div class="topic-video">

            <div class="topic-video-frame">
                <div class="video-player">
                    <div class="video-frame">
                        <div class="fluid-width-video-wrapper" style="padding-top: 56.25%;">
                            <iframe src="https://www.youtube.com/embed/<?php echo esc_attr($videoid[1]) ?>?feature=oembed&rel=0" frameborder="0" allowfullscreen id="fitvid972597"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <article class="topic-video-caption">
                <header>
                    <h2><?php the_sub_field('video_title') ?></h2>
                </header>

                <div class="content rich-text">
                    <?php the_sub_field('content') ?>
                </div>

            </article>
            <?php include locate_template('content-blocks/call-to-action.php') ?>
        </div>
    </div>

    <?php endif;
