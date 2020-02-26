<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
add_image_size( 'content', zm_get_option('img_w'), zm_get_option('img_h'), true );
add_image_size( 'long', zm_get_option('img_k_w'), zm_get_option('img_k_h'), true );
add_image_size( 'tao', zm_get_option('img_t_w'), zm_get_option('img_t_h'), true );