
</main>

<footer class="footer group" role="contentinfo">
    <div class="row">

        <div class="footer-links">
            <nav class="footer-navigation">
                <?php if (has_nav_menu('footer')) : ?>
                    <?php wp_nav_menu([
                        'theme_location' => 'footer',
                        'container' => '',
                        'depth' => '1'
                    ]); ?>
                <?php endif; ?>
            </nav>

            <?php get_template_part('inc-socialnetworks'); ?>
        </div>

        <div class="footer-logo">
            <a href="http://www.nhs.uk/" title="NHS Choices">
                <img src="<?php h()->assetPath('img/nhs-choices-logo.svg') ?>" onerror="this.src='<?php h()->assetPath('img/nhs-choices-logo.png') ?>'; this.onerror=null;" alt="NHS Choices" />
            </a>
        </div>

    </div>

    <div id="__ba_panel"></div>

</footer>

<?php wp_footer(); ?>

<script>
    // nhs england
    markExternalLinks();
    showDownloadableFileIcons();
    gaTrackDownloadableFiles();

    // Analytics
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-16278926-28', 'auto');
    ga('send', 'pageview');

    // Browsealoud
    var _baTheme=0, _baUseCookies=true, _baHideOnLoad=true;
</script>

</body>
</html>
