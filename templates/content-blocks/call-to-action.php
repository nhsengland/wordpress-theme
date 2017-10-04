<?php if (get_sub_field('include_call_to_action') == true) : ?>
    <div class="topic-viewall">
        <a href="<?php echo esc_html(get_sub_field('call_to_action_link')) ?>"><?php echo esc_html(get_sub_field('call_to_action_text')) ?></a>
    </div>
<?php endif; ?>
