<?php if (get_row_layout() === 'a_to_z_index_component') : ?>

    <div class="row">
        <div class="atoz-index-component">
            <header>
                <h2><?php the_sub_field('a_to_z_index_title') ?></h2>
                <div class="rich-text">
                    <p><?php the_sub_field('a_to_z_index_content') ?></p>
                </div>
            </header>

            <div class="full-atoz-index">
                <ul>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#a">A</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#b">B</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#c">C</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#d">D</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#e">E</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#f">F</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#g">G</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#h">H</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#i">I</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#j">J</a></li>
                    <li class="empty"><a href="<?php echo site_url('/a-to-z/'); ?>#">K</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#l">L</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#m">M</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#n">N</a></li>
                    <li class="empty"><a href="<?php echo site_url('/a-to-z/'); ?>#">O</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#p">P</a></li>
                    <li class="empty"><a href="<?php echo site_url('/a-to-z/'); ?>#">Q</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#r">R</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#s">S</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#t">T</a></li>
                    <li class="empty"><a href="<?php echo site_url('/a-to-z/'); ?>#">U</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#v">V</a></li>
                    <li><a href="<?php echo site_url('/a-to-z/'); ?>#w">W</a></li>
                    <li class="empty"><a href="<?php echo site_url('/a-to-z/'); ?>#">X</a></li>
                    <li class="empty"><a href="<?php echo site_url('/a-to-z/'); ?>#">Y</a></li>
                    <li class="empty"><a href="<?php echo site_url('/a-to-z/'); ?>#">Z</a></li>
                    <li class="numbered"><a href="<?php echo site_url('/a-to-z/'); ?>#0-9">0-9</a></li>
                </ul>
            </div>

        </div>
    </div>

<?php endif; ?>
