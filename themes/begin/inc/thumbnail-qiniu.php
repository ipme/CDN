<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// 标准缩略图
function zm_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	// 手动缩略图
	if ( get_post_meta($post->ID, 'thumbnail', true) ) {
		$image = get_post_meta($post->ID, 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
		} else {
			echo '<a href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		// 特色图像
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a href="'.get_permalink().'">';
			} else {
				echo '<a href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'">';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'">';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			// 裁剪第一张图
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if($n > 0) {
				// 七牛
				if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
					echo '<a href="'.get_permalink().'">';
					echo  wpjam_post_thumbnail( array( zm_get_option( 'img_w' ), zm_get_option( 'img_h' )), $crop=1 );
					echo '</a>';
				}
			} else { 
				// 随机缩略图
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
					} else {
						echo '<a href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'. $src .'" alt="'.$post->post_title .'" /></a></span>';
					} else {
						echo '<a href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" /></a>';
					}
				}
			}
		}
	}
}

// ajax-tab缩略图
function zm_thumbnail_a() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta($post->ID, 'thumbnail', true) ) {
		$image = get_post_meta($post->ID, 'thumbnail', true);
		echo '<a href="'.get_permalink().'"><img src="';
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /></a>';
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'content');
			echo '<a href="'.get_permalink().'">';
			if (zm_get_option('clipping_thumbnails')) {
				echo '<img src="';
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'">';
			} else {
				the_post_thumbnail('content', array('alt' => get_the_title()));
			}
			echo '</a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if($n > 0) {
				if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
					echo '<a href="'.get_permalink().'">';
					echo  wpjam_post_thumbnail( array( zm_get_option( 'img_w' ), zm_get_option( 'img_h' )), $crop=1 );
					echo '</a>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					echo '<a href="'.get_permalink().'"><img src="';
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
				} else { 
					echo '<a href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" /></a>';
				}
			}
		}
	}
}

// 分类模块宽缩略图
function zm_long_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_long_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta($post->ID, 'long_thumbnail', true) ) {
		$image = get_post_meta($post->ID, 'long_thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/long.png" data-original="';
		} else {
			echo '<a href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'long');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a href="'.get_permalink().'">';
			} else {
				echo '<a href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/long.png" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'">';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/long.png" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'">';
				} else {
					the_post_thumbnail('long', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if($n > 0) {
				if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
					echo '<a href="'.get_permalink().'">';
					echo  wpjam_post_thumbnail( array( zm_get_option( 'img_k_w' ), zm_get_option( 'img_k_h' )), $crop=1 );
					echo '</a>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/long.png" data-original="';
					} else {
						echo '<a href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_k_w').'&h='.zm_get_option('img_k_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/long.png" data-original="'. $src .'" alt="'.$post->post_title .'" /></a></span>';
					} else {
						echo '<a href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" /></a>';
					}
				}
			}
		}
	}
}

// 图片缩略图
function img_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta($post->ID, 'thumbnail', true) ) {
		$image = get_post_meta($post->ID, 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
		} else {
			echo '<a href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a href="'.get_permalink().'">';
			} else {
				echo '<a href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'">';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'">';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if($n > 0) {
				if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
					echo '<a href="'.get_permalink().'">';
					echo  wpjam_post_thumbnail( array( zm_get_option( 'img_i_w' ), zm_get_option( 'img_i_h' )), $crop=1 );
					echo '</a>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
					} else {
						echo '<a href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_i_w').'&h='.zm_get_option('img_i_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'. $src .'" alt="'.$post->post_title .'" /></a></span>';
					} else {
						echo '<a href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" /></a>';
					}
				}
			}
		}
	}
}

// 视频缩略图
function videos_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta($post->ID, 'small', true) ) {
		$image = get_post_meta($post->ID, 'small', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a class="videos" href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
		} else {
			echo '<a class="videos" href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /><i class="be be-play"></i></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a class="videos" href="'.get_permalink().'">';
			} else {
				echo '<a class="videos" href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'">';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'">';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '<i class="be be-play"></i></a></span>';
			} else {
				echo '<i class="be be-play"></i></a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if($n > 0) {
				if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
					echo '<a href="'.get_permalink().'">';
					echo  wpjam_post_thumbnail( array( zm_get_option( 'img_v_w' ), zm_get_option( 'img_v_h' )), $crop=1 );
					echo '</a>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="videos" href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
					} else {
						echo '<a href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_v_w').'&h='.zm_get_option('img_v_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /><i class="be be-play"></i></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a class="videos" href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'. $src .'" alt="'.$post->post_title .'" /><i class="be be-play"></i></a></span>';
					} else {
						echo '<a class="videos" href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" /><i class="be be-play"></i></a>';
					}
				}
			}
		}
	}
}

// 商品缩略图
function tao_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	$url = get_post_meta($post->ID, 'taourl', true);
	if ( get_post_meta($post->ID, 'thumbnail', true) ) {
		$image = get_post_meta($post->ID, 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
		} else {
			echo '<a href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'tao');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a href="'.get_permalink().'">';
			} else {
				echo '<a href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'">';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'">';
				} else {
					the_post_thumbnail('tao', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if($n > 0) {
				if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
					echo '<a href="'.get_permalink().'">';
					echo  wpjam_post_thumbnail( array( zm_get_option( 'img_t_w' ), zm_get_option( 'img_t_h' )), $crop=1 );
					echo '</a>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
					} else {
						echo '<a href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'. $src .'" alt="'.$post->post_title .'" /></a></span>';
					} else {
						echo '<a href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" /></a>';
					}
				}
			}
		}
	}
}

// 图像日志缩略图
function format_image_thumbnail() {
    global $post;
	$content = $post->post_content;
	preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
	if (zm_get_option('lazy_s')) {
		echo '<div class="f4"><div class="format-img"><div class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.get_template_directory_uri().'/prune.php?src='.$strResult[1][0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a></div></div></div>';
		echo '<div class="f4"><div class="format-img"><div class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.get_template_directory_uri().'/prune.php?src='.$strResult[1][1].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a></div></div></div>';
		echo '<div class="f4"><div class="format-img"><div class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.get_template_directory_uri().'/prune.php?src='.$strResult[1][2].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a></div></div></div>';
		echo '<div class="f4"><div class="format-img"><div class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.get_template_directory_uri().'/prune.php?src='.$strResult[1][3].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a></div></div></div>';
	} else {
		echo '<div class="f4"><div class="format-img"><a href="'.get_permalink().'"><img src="'.get_template_directory_uri().'/prune.php?src='.$strResult[1][0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a></div></div>';
		echo '<div class="f4"><div class="format-img"><a href="'.get_permalink().'"><img src="'.get_template_directory_uri().'/prune.php?src='.$strResult[1][1].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a></div></div>';
		echo '<div class="f4"><div class="format-img"><a href="'.get_permalink().'"><img src="'.get_template_directory_uri().'/prune.php?src='.$strResult[1][2].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a></div></div>';
		echo '<div class="f4"><div class="format-img"><a href="'.get_permalink().'"><img src="'.get_template_directory_uri().'/prune.php?src='.$strResult[1][3].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a></div></div>';
	}
}

// 图片布局缩略图
function zm_grid_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta($post->ID, 'thumbnail', true) ) {
		$image = get_post_meta($post->ID, 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
		} else {
			echo '<a href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a href="'.get_permalink().'">';
			} else {
				echo '<a href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'">';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'">';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if($n > 0) {
				if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
					echo '<a href="'.get_permalink().'">';
					echo  wpjam_post_thumbnail( array( zm_get_option( 'grid_w' ), zm_get_option( 'grid_h' )), $crop=1 );
					echo '</a>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
					} else {
						echo '<a href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('grid_w').'&h='.zm_get_option('grid_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'. $src .'" alt="'.$post->post_title .'" /></a></span>';
					} else {
						echo '<a href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" /></a>';
					}
				}
			}
		}
	}
}

// 宽缩略图分类
function zm_full_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_long_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta($post->ID, 'thumbnail', true) ) {
		$image = get_post_meta($post->ID, 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/long.png" data-original="';
		} else {
			echo '<a href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a href="'.get_permalink().'">';
			} else {
				echo '<a href="'.get_permalink().'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/long.png" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'">';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/long.png" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'">';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if($n > 0) {
				if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
					echo '<a href="'.get_permalink().'">';
					echo  wpjam_post_thumbnail( array( zm_get_option( 'img_full_w' ), zm_get_option( 'img_full_h' )), $crop=1 );
					echo '</a>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/long.png" data-original="';
					} else {
						echo '<a href="'.get_permalink().'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_full_w').'&h='.zm_get_option('img_full_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/long.png" data-original="'. $src .'" alt="'.$post->post_title .'" /></a></span>';
					} else {
						echo '<a href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" /></a>';
					}
				}
			}
		}
	}
}

// 网址缩略图
function zm_sites_thumbnail() {
	global $post;
	if (zm_get_option('sites_to')) {
		$sites_img_link = sites_nofollow( base64_encode(get_post_meta($post->ID, 'sites_img_link', true)));
	} else {
		$sites_img_link = get_post_meta($post->ID, 'sites_img_link', true);
	}
	if ( get_post_meta($post->ID, 'thumbnail', true) ) {
		$image = get_post_meta($post->ID, 'thumbnail', true);
		echo '<a href="'.$sites_img_link.'" target="_blank" rel="external nofollow"><img src=';
		echo $image;
		echo ' alt="'.$post->post_title .'" /></a>';
	} else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if($n > 0) {
			if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
				echo '<a href="'.get_permalink().'">';
				echo  wpjam_post_thumbnail( array( zm_get_option( 'img_w' ), zm_get_option( 'img_h' )), $crop=1 );
				echo '</a>';
			}
		}
	}
}

// 网址自动截取缩略图
function zm_sites_shot_img() {
	global $post;
	$sites_link = get_post_meta($post->ID, 'sites_img_link', true);
	echo '<a class="load" href="' . $sites_link . '" target="_blank" rel="external nofollow">';
	if (zm_get_option('shot_thumbnail')) {
		echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original=';
		echo get_template_directory_uri().'/prune.php?src=https://s0.wordpress.com/mshots/v1/'.$sites_link.'?w='.zm_get_option('sites_w').'&h='.zm_get_option('sites_h').'.jpg&w='.zm_get_option('sites_w').'&h='.zm_get_option('sites_h').'&a='.zm_get_option('crop_top').'&zc=1';
	} else {
		echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original=';
		echo 'https://s0.wordpress.com/mshots/v1/'.$sites_link.'?w='.zm_get_option('sites_w').'&h='.zm_get_option('sites_h').'.jpg';
	}
	echo ' alt="'.$post->post_title .'" /></a>';
}

function zm_sites_shot() {
	global $post;
	$sites_link = get_post_meta($post->ID, 'sites_link', true);
	echo '<a class="load" href="' . $sites_link . '" target="_blank" rel="external nofollow">';
	if (zm_get_option('shot_thumbnail')) {
		echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original=';
		echo get_template_directory_uri().'/prune.php?src=https://s0.wordpress.com/mshots/v1/'.$sites_link.'?w='.zm_get_option('sites_w').'&h='.zm_get_option('sites_h').'.jpg&w='.zm_get_option('sites_w').'&h='.zm_get_option('sites_h').'&a='.zm_get_option('crop_top').'&zc=1';
	} else {
		echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original=';
		echo 'https://s0.wordpress.com/mshots/v1/'.$sites_link.'?w='.zm_get_option('sites_w').'&h='.zm_get_option('sites_h').'.jpg';
	}
	
	echo ' alt="'.$post->post_title .'" /></a>';
}

// 公司左右图
function gr_wd_thumbnail() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta($post->ID, 'wd_img', true) ) {
		$image = get_post_meta($post->ID, 'wd_img', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
		} else {
			echo '<a href="'.get_permalink().'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w=700&h=380&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if($n > 0) {
			if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
				echo '<a href="'.get_permalink().'">';
				echo  wpjam_post_thumbnail( array( 700,380), $crop=1 );
				echo '</a>';
			}
		} else { 
			if ( zm_get_option('rand_img_n') ) {
				$random = mt_rand(1, zm_get_option('rand_img_n'));
			} else { 
				$random = mt_rand(1, 5);
			}
			if (zm_get_option('clipping_rand_img')) {
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
				} else {
					echo '<a href="'.get_permalink().'"><img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$src.'&w=700&h=380&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
				if (zm_get_option('lazy_s')) {
					echo '</span>';
				}
			} else { 
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'. $src .'" alt="'.$post->post_title .'" /></a></span>';
				} else {
					echo '<a href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" /></a>';
				}
			}
		}
	}
}

// 链接形式
function zm_thumbnail_link() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta($post->ID, 'thumbnail', true) ) {
		$image = get_post_meta($post->ID, 'thumbnail', true);
		if (zm_get_option('lazy_s')) {
			echo '<span class="load"><a href="'.$direct.'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
		} else {
			echo '<a href="'.$direct.'"><img src="';
		}
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /></a>';
		if (zm_get_option('lazy_s')) {
			echo '</span>';
		}
	} else {
		if ( has_post_thumbnail() ) {
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'content');
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a href="'.$direct.'">';
			} else {
				echo '<a href="'.$direct.'">';
			}
			if (zm_get_option('clipping_thumbnails')) {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
				} else {
					echo '<img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'">';
			} else {
				if (zm_get_option('lazy_s')) {
					echo '<img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.$full_image_url[0].'" alt="'.get_the_title().'">';
				} else {
					the_post_thumbnail('content', array('alt' => get_the_title()));
				}
			}
			if (zm_get_option('lazy_s')) {
				echo '</a></span>';
			} else {
				echo '</a>';
			}
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if($n > 0) {
				// 七牛
				if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
					echo '<a href="'.$direct.'">';
					echo  wpjam_post_thumbnail( array( zm_get_option( 'img_w' ), zm_get_option( 'img_h' )), $crop=1 );
					echo '</a>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.$direct.'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
					} else {
						echo '<a href="'.$direct.'"><img src="';
					}
					echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
					if (zm_get_option('lazy_s')) {
						echo '</span>';
					}
				} else { 
					if (zm_get_option('lazy_s')) {
						echo '<span class="load"><a href="'.$direct.'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'. $src .'" alt="'.$post->post_title .'" /></a></span>';
					} else {
						echo '<a href="'.$direct.'"><img src="'. $src .'" alt="'.$post->post_title .'" /></a>';
					}
				}
			}
		}
	}
}

// 幻灯小工具
function zm_widge_thumbnail() {
	global $post;
	if ( get_post_meta($post->ID, 'widge_img', true) ) {
		$image = get_post_meta($post->ID, 'widge_img', true);
		echo '<a href="'.get_permalink().'"><img src=';
		echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_s_w').'&h='.zm_get_option('img_s_h').'&a='.zm_get_option('crop_top').'&zc=1';
		echo ' alt="'.$post->post_title .'" /></a>';
	} else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if($n > 0){
			if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
				echo '<a href="'.get_permalink().'">';
				echo  wpjam_post_thumbnail( array( zm_get_option( 'img_s_w' ), zm_get_option( 'img_s_h' )), $crop=1 );
				echo '</a>';
			}
		}
	}
}

// 图片滚动
function zm_thumbnail_scrolling() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta($post->ID, 'thumbnail', true) ) {
		$image = get_post_meta($post->ID, 'thumbnail', true);
		echo '<a href="'.get_permalink().'"><img class="owl-lazy" data-src="';
		if (zm_get_option('manual_thumbnail')) {
			echo get_template_directory_uri().'/prune.php?src='.$image.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1"';
		} else {
			echo $image . '"';
		}
		echo ' alt="'.$post->post_title .'" /></a>';
	} else {
		if ( has_post_thumbnail() ) {
			echo '<a href="'.get_permalink().'">';
			if (zm_get_option('clipping_thumbnails')) {
				$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'content');
				echo '<img src="'.get_template_directory_uri().'/prune.php?src='.$full_image_url[0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.get_the_title().'">';
			} else {
				the_post_thumbnail('content', array('alt' => get_the_title()));
			}
			echo '</a>';
		} else {
			$content = $post->post_content;
			preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
			$n = count($strResult[1]);
			if($n > 0) {
				if ( function_exists( 'wpjam_has_post_thumbnail' ) && wpjam_has_post_thumbnail() ) {
					echo '<a href="'.get_permalink().'">';
					echo  wpjam_post_thumbnail( array( zm_get_option( 'img_w' ), zm_get_option( 'img_h' )), $crop=1 );
					echo '</a>';
				}
			} else { 
				if ( zm_get_option('rand_img_n') ) {
					$random = mt_rand(1, zm_get_option('rand_img_n'));
				} else { 
					$random = mt_rand(1, 5);
				}
				if (zm_get_option('clipping_rand_img')) {
					echo '<a href="'.get_permalink().'"><img class="owl-lazy" data-src="'.get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
				} else { 
					echo '<a href="'.get_permalink().'"><img class="owl-lazy" data-src="'. $src .'" alt="'.$post->post_title .'" /></a>';
				}
			}
		}
	}
}

// 瀑布流
function zm_waterfall_img() {
	$random_img = explode(',' , zm_get_option('random_image_url'));
	$random_img_array = array_rand($random_img);
	$src = $random_img[$random_img_array];
	global $post;
	if ( get_post_meta($post->ID, 'fall_img', true) ) {
		$image = get_post_meta($post->ID, 'fall_img', true);
		echo '<a href="'.get_permalink().'"><img src=';
		echo $image;
		echo ' alt="'.$post->post_title .'" /></a>';
	} else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if($n > 0) {
			if (zm_get_option('lazy_s')) {
				echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.$strResult[1][0].'" alt="'.$post->post_title .'" /></a></span>';
			} else {
				echo '<a href="'.get_permalink().'"><img src="'.$strResult[1][0].'" alt="'.$post->post_title .'" /></a>';
			}
		} else { 
			if ( zm_get_option('rand_img_n') ) {
				$random = mt_rand(1, zm_get_option('rand_img_n'));
			} else { 
				$random = mt_rand(1, 5);
			}
			if (zm_get_option('clipping_rand_img')) {
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="';
				} else {
					echo '<a href="'.get_permalink().'"><img src="';
				}
				echo get_template_directory_uri().'/prune.php?src='.$src.'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
				if (zm_get_option('lazy_s')) {
					echo '</span>';
				}
			} else { 
				if (zm_get_option('lazy_s')) {
					echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'. $src .'" alt="'.$post->post_title .'" /></a></span>';
				} else {
					echo '<a href="'.get_permalink().'"><img src="'. $src .'" alt="'.$post->post_title .'" /></a>';
				}
			}
		}
	}
}