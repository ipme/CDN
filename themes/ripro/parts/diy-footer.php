<div class="footer-widget">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-3 widget--about">
            <div class="widget--content">
                <div class="footer--logo mb-20">
                    <img class="tap-logo" src="<?php echo esc_url( _cao( 'site_footer_logo') ); ?>" data-dark="<?php echo esc_url(_cao( 'site_footer_logo')); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                </div>
                <p class="mb-10"><?php echo _cao('site_footer_desc');?></p>
            </div>
        </div>
        <!-- .col-md-2 end -->
        <div class="col-xs-12 col-sm-3 col-md-2 col-md-offset-1 widget--links">
            <div class="widget--title">
                <h5><?php echo _cao('site_footer_link1');?></h5>
            </div>
            <div class="widget--content">
                <ul class="list-unstyled mb-0">
                    <?php $footer_link1_group = _cao('site_footer_link1_group');
                    if (!empty($footer_link1_group)) {
                        foreach ($footer_link1_group as $key => $link) {
                            $_blank = ($link['_blank']) ? ' target="_blank"' : '' ;
                            echo '<li><a'.$_blank.' href="'.$link['_link'].'">'.$link['_title'].'</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <!-- .col-md-2 end -->
        <div class="col-xs-12 col-sm-3 col-md-2 widget--links">
            <div class="widget--title">
                <h5><?php echo _cao('site_footer_link2');?></h5>
            </div>
            <div class="widget--content">
                <ul class="list-unstyled mb-0">
                    <?php $footer_link2_group = _cao('site_footer_link2_group');
                    if (!empty($footer_link2_group)) {
                        foreach ($footer_link2_group as $key => $link) {
                            $_blank = ($link['_blank']) ? ' target="_blank"' : '' ;
                            echo '<li><a'.$_blank.' href="'.$link['_link'].'">'.$link['_title'].'</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <!-- .col-md-2 end -->
        <div class="col-xs-12 col-sm-12 col-md-4 widget--newsletter">
            <div class="widget--title">
                <h5>快速搜索</h5>
            </div>
            <div class="widget--content">
                <form class="newsletter--form mb-30" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                    <input type="text" class="form-control" name="s" placeholder="关键词">
                    <button type="submit"><i class="fa fa-arrow-right"></i></button>
                </form>
                <h6><?php echo _cao('site_footer_copdesc');?></h6>
            </div>
        </div>

    </div>
</div>