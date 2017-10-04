<?php if (get_field('show_social_networks', 'options')) : ?>
    <div class="social-networks">
        <?php $social_networks = get_field('social_networks', 'options'); ?>
        <?php  if ($social_networks) : ?>
            <ul>
                <?php foreach ($social_networks as $social_network) : ?>
                    <li>
                        <a href="<?php echo $social_network['network_link']; ?>" title="<?php echo $social_network['network_name']; ?>" target="_blank">
                            <span class="hide"><?php echo $social_network['network_name']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endif; ?>