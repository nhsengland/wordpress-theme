<?php
//
// Content Block ### Document
//
if (get_row_layout() === 'document') :

    $file = get_sub_field('file');
    $file_size = strtoupper(size_format(filesize(get_attached_file($file->ID)), 0));
    switch ($file->post_mime_type) {
        case 'application/pdf':
        $icon = 'pdf';
        break;
        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
        case 'application/msword':
        $icon = 'doc';
        break;
        case 'application/vnd.ms-excel':
        case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
        $icon = 'xls';
        break;
        case 'text/csv':
        $icon = 'csv';
        break;
        default:
        $icon = 'file';
    }
    $thumbnail = get_bloginfo('template_directory') . '/../assets/img/document-icons/' . $icon . '.png';

    $documentContainer = get_field('documents', $file->post_parent);

    if (!empty($documentContainer) && is_array($documentContainer)) {
        foreach ($documentContainer as $doc) {
            if (isset($doc['document']['ID']) && $doc['document']['ID'] === $file->ID && $doc['thumbnail']) {
                $thumbnail = $doc['thumbnail']['url'];
            }
        }
    }

    ?>
    <div class="row">
        <div class="topic-publication">

            <div class="topic-publication-detail">
                <header>
                    <h2><?php the_sub_field('document_title') ?></h2>
                </header>

                <div class="thumbnail-meta rich-text">
                    <p>
                        <?php echo h()->mimeToEnglish($file->post_mime_type) ?>, <?php echo $file_size ?>
                    </p>
                </div>

                <div class="content rich-text">
                    <?php the_sub_field('description') ?>
                </div>

                <div class="download"><a href="<?php echo wp_get_attachment_url($file->ID) ?>" class="button">Download this document</a></div>
            </div>

            <div class="topic-publication-thumbnail">
                <div class="thumbnail">
                    <a href="<?php echo wp_get_attachment_url($file->ID) ?>" class="doc-thumbnail"><img src="<?php echo $thumbnail ?>" alt="Document Container"></a>
                </div>
            </div>

            <?php include locate_template('content-blocks/call-to-action.php'); ?>
        </div>
    </div>

    <?php endif;
