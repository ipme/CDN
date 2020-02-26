<?php
    if ( post_password_required() || _cao('is_site_comments','true')) {
        return;
    }
?>

<div class="bottom-area">
    <div id="comments" class="comments-area">
        <?php if ( have_comments() ) : ?>
            <h3 class="comments-title">
                <?php
                    printf(
                        esc_html( _nx( '1 评论', '%1$s 评论', get_comments_number(), 'comments title', 'cao' ) ),
                        number_format_i18n( get_comments_number() )
                    );
                ?>
            </h3>

            <ol class="comment-list">
                <?php
                    wp_list_comments( array(
                        'style' => 'ol',
                        'short_ping' => true,
                        'callback' => 'cao_comment',
                        'avatar_size' => 100,
                    ) );
                ?>
            </ol>

            <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
                <nav class="navigation comment-navigation">
                    <h2 class="screen-reader-text"><?php esc_html_e( '评论导航', 'cao' ); ?></h2>
                    <div class="nav-links">
                        <?php if ( get_previous_comments_link() ) : ?>
                            <div class="nav-previous"><?php previous_comments_link( '<i class="mdi mdi-chevron-right"></i>' ); ?></div>
                        <?php endif; ?>
                        <?php if ( get_next_comments_link() ) : ?>
                            <div class="nav-next"><?php next_comments_link( '<i class="mdi mdi-chevron-left"></i>' ); ?></div>
                        <?php endif; ?>
                    </div>
                </nav>
            <?php endif; ?>

        <?php endif; ?>

        <?php if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
            <p class="no-comments"><?php esc_html_e( '评论已关闭', 'cao' ); ?></p>
        <?php endif; ?>

        <?php
            $req = get_option( 'require_name_email' );
            $aria_req = ( $req ? " aria-required='true'" : '' );

            $fields = array(
                'author' => '<div class="row comment-author-inputs"><div class="col-md-4 input"><p class="comment-form-author">' .
                '<label for="author">昵称*</label>' .
                '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
                '" size="30"' . $aria_req . '></p></div>',

                'email' => '<div class="col-md-4 input"><p class="comment-form-email">' .
                '<label for="email">E-mail*</label>' .
                '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
                '" size="30"' . $aria_req . '></p></div>',

                'url' => '<div class="col-md-4 input"><p class="comment-form-url">' .
                '<label for="url">网站</label>' .
                '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
                '" size="30"></p></div></div>',
                
                'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"> <label for="wp-comment-cookies-consent">下次发表评论时，请在此浏览器中保存我的姓名、电子邮件和网站</label></p>'
            );

            $comment_field = '<p class="comment-form-comment">' .
                // '<label for="comment">' . esc_html__( 'Comment*', 'magsy' ) . '</label>' .
                '<textarea id="comment" name="comment" rows="8" aria-required="true">' .
                '</textarea></p>';

            $comment_args = array( 'comment_field' => $comment_field, 'fields' => $fields, 'class_submit' => 'button', 'comment_notes_before' => '', 'comment_notes_after' => '' );
        ?>

        <?php comment_form( $comment_args ); ?>
    </div>
</div>