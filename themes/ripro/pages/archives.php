<?php
/**
 * Template name: 存档页面
 * Description:   A archives page
 */
get_header();
?>

<div class="container">
    <div class="row">
        <div class="article-content col-lg-12">
            <div class="content-area">
                <main class="site-main">

                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post archives' ); ?>>

                      <div class="container">
                       <?php
                        $previous_year = $year = 0;
                        $previous_month = $month = 0;
                        $ul_open = false;
                        $numberposts = _cao('zhuanti_postnum','30');
                        $myposts = get_posts('numberposts='.$numberposts.'&orderby=post_date&order=DESC');
                        foreach($myposts as $post) :
                            setup_postdata($post);
                            $year = mysql2date('Y', $post->post_date);
                            $month = mysql2date('n', $post->post_date);
                            $day = mysql2date('j', $post->post_date);
                            if($year != $previous_year || $month != $previous_month) :
                                if($ul_open == true) : 
                                    echo '</ul></div>';
                                endif;
                         
                                echo '<div class="item"><h3>'; echo the_time('F Y'); echo '</h3>';
                                echo '<ul class="archives-list">';
                                $ul_open = true;
                            endif;
                            $previous_year = $year; $previous_month = $month;

                        ?>
                            <li>
                                <time><?php the_time('j'); ?>日</time>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?> </a>
                                <span class="text-muted"><?php comments_number('', '1评论', '%评论'); ?></span>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                      </div>
                    </article>

                </main>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
