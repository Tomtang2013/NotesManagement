<script type="text/javascript">
    jQuery(function(){
        jQuery( ".menu" ).find('a').each(function(){
            if( '/NotesManagement/dashboard' == jQuery(this).attr('href')){
                jQuery(this).parent().hide();
            } else if( '/NotesManagement/node/add' == jQuery(this).attr('href')){
                jQuery(this).parent().hide();
            }
        });
    });

</script>
<header id="header" class="clearfix" role="banner">
    <hgroup>
        <?php if ($logo): ?>
            <div id="logo">
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" /></a>
            </div>
        <?php endif; ?>
        <div id="sitename">
            <h2><a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>"><?php print $site_name; ?></a></h2>
            <p><?php if ($site_slogan): ?><?php print $site_slogan; ?><?php endif; ?></p>
        </div>
    </hgroup>
</header>

