<?php
$timestampFormat = '%Y-%m-%dT%H:%M:%S%z';
$humanFormat = '%e %B %Y';
$errorString = 'Date not available.';
$tz = get_option('timezone_string');
if (empty($tz)) {
    $tz = 'Etc/UTC';
}
?>
<?php get_header() ?>

<section class="document-container row" id="main-content">

    <?php while (have_posts()) : ?>
        <?php the_post() ?>
        <?php
        global $post;
        $created_at_timestamp = \Missing\Dates::strftime($post->post_date_gmt, $timestampFormat, $errorString, $tz);
        $created_at_human = \Missing\Dates::strftime($post->post_date_gmt, $humanFormat, $errorString, $tz);
        $updated_at_timestamp = \Missing\Dates::strftime($post->post_modified_gmt, $timestampFormat, $errorString, $tz);
        $updated_at_human = \Missing\Dates::strftime($post->post_modified_gmt, $humanFormat, $errorString, $tz);
        ?>

        <div class="document-content">

            <div class="document-meta">
                <header>
                    <h1><?php the_title() ?></h1>
                </header>

                <div class="publishing-meta group">
                    <dl class="group">
                        <dt>Document first published:</dt>
                        <dd><time datetime="<?php echo esc_attr($created_at_timestamp) ?>"><?php echo esc_attr($created_at_human) ?></time></dd>
                        <dt>Page updated:</dt>
                        <dd><time datetime="<?php echo esc_attr($updated_at_timestamp) ?>"><?php echo esc_attr($updated_at_human) ?></time></dd>
                        <dt>Topic:</dt>
                        <dd><?php the_terms(0, 'category') ?></dd>
                        <dt>Publication type:</dt>
                        <dd><?php the_terms(0, 'publication-type') ?></dd>
                    </dl>

                    <div class="excerpt rich-text">
                        <?php the_field('introduction') ?>
                    </div>
                </div>

            </div>

            <?php while (have_rows('documents')) : ?>
                <?php the_row() ?>

                <div class="document-thumbnail group">

                    <div class="<?php if (get_sub_field('type_of_publication') == 'document') {
            echo 'document-file';
        } else {
            echo 'document-media';
        } ?> group">

                    <header>
                        <?php if (get_sub_field('type_of_publication') == 'document') : ?>
                            <h2>Document</h2>
                        <?php elseif (get_sub_field('type_of_publication') == 'audiovideo'): ?>
                            <h2>Media</h2>
                        <?php else: ?>
                        <h2>Link</h2>
                    <?php endif; ?>
                </header>

                <div class="summary group">

                    <?php if (get_sub_field('type_of_publication') == 'document'): ?>
                        <a href="<?php echo esc_url(get_sub_field('document')['url']) ?>" class="doc-thumbnail">
                            <img src="<?php echo esc_url(h()->thumbnailSrc()) ?>" alt="<?php echo esc_html(get_sub_field('title')) ?>">
                        </a>

                    <?php elseif (get_sub_field('type_of_publication') == 'audiovideo'): ?>

                        <div class="media">
                            <?php the_sub_field('audio_or_video') ?>
                        </div>

                    <?php else : ?>

                        <div class="link">

                            <a href="<?php the_sub_field('link_url') ?>" class="doc-thumbnail">
                                <img src="<?php h()->assetPath('img/document-icons/link.png') ?>" alt="<?php echo esc_html(get_sub_field('title')) ?>">
                            </a>

                            <header>
                                <h3><a href="<?php the_sub_field('link_url') ?>">
                                <?php if (get_sub_field('title')) {
            echo esc_html(get_sub_field('title'));
        } else {
            echo get_sub_field('link_url');
        } ?></a></h3>
                                <p class="note">NHS England is not responsible for content on external websites.</p>
                            </header>
                        </div>

                    <?php endif; ?>

                    <?php if (get_sub_field('type_of_publication') == 'document' || get_sub_field('type_of_publication') == 'audiovideo'): ?>
                        <div class="summary-meta">
                            <?php if (get_sub_field('type_of_publication') == 'document'): ?>
                                <h3>
                                    <a href="<?php echo esc_url(get_sub_field('document')['url']) ?>">
                                        <?php echo esc_html(get_sub_field('title')) ?>
                                    </a>
                                </h3>
                            <?php endif; ?>
                            <div class="rich-text">
                                <ul class="attributes">
                                    <?php
                                    $document = get_sub_field('document');
                                    if (get_sub_field('type_of_publication') == 'document') {
                                        if ($document['mime_type']) {
                                            echo '<li>';
                                            echo esc_html(h()->mimeToEnglish($document['mime_type']));
                                            echo '</li>';
                                        }
                                        echo '<li>';
                                        echo size_format(filesize(get_attached_file($document['ID'])));
                                        echo '</li>';
                                        if (get_sub_field('number_of_pages')) {
                                            echo '<li>';
                                            echo esc_html(get_sub_field('number_of_pages'));
                                            echo ' pages</li>';
                                        }
                                    } elseif (get_sub_field('type_of_publication') =='audiovideo') {
                                        if (get_sub_field('length_of_file')) {
                                            echo '<li>Running time: ';
                                            echo esc_html(get_sub_field('length_of_file'));
                                            echo '</li>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (get_sub_field('snapshot')) : ?>
                <div class="document-summary group">
                    <h4>Summary</h4>
                    <div class="content rich-text">
                        <?php the_sub_field('snapshot') ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    <?php endwhile ?>

    <?php comments_template('', true) ?>

</div>

    <?php endwhile ?>

</section>

<?php get_footer() ?>
