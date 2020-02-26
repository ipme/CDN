<?php
/*
Name: Widget Logic
*/
if ( ! defined( 'ABSPATH' ) ) { exit; }
if (is_admin()){
	add_filter( 'in_widget_form', 'begin_widget_logic_in_widget_form', 10, 3 );
	add_filter( 'widget_update_callback', 'begin_widget_logic_update_callback', 10, 4);
}else{
	add_filter( 'sidebars_widgets', 'begin_widget_logic_filter_sidebars_widgets', 10);
}

function begin_widget_logic_update_callback( $instance, $new_instance, $old_instance, $this_widget ){
	if ( isset( $new_instance['begin_widget_logic'] ) )
		$instance['begin_widget_logic'] = $new_instance['begin_widget_logic'];
	return $instance;
}

function begin_widget_logic_in_widget_form( $widget, $return, $instance ){
	$logic = isset( $instance['begin_widget_logic'] ) ? $instance['begin_widget_logic'] : begin_widget_logic_by_id( $widget->id );
	?>
		<p>
			<label for="<?php echo $widget->get_field_id('begin_widget_logic'); ?>">条件判断</label>
			<textarea class="widefat" name="<?php echo $widget->get_field_name('begin_widget_logic'); ?>" id="<?php echo $widget->get_field_id('begin_widget_logic'); ?>"><?php echo esc_textarea( $logic ) ?></textarea>
		</p>
	<?php
	return;
}


function begin_widget_logic_by_id( $widget_id ){
	global $wl_options;
	if ( preg_match( '/^(.+)-(\d+)$/', $widget_id, $m ) ){
		$widget_class = $m[1];
		$widget_i = $m[2];
		$info = get_option( 'widget_'.$widget_class );
		if ( empty( $info[ $widget_i ] ) )
			return '';
		$info = $info[ $widget_i ];
	}else
		$info = (array)get_option( 'widget_'.$widget_id, array() );
	if ( isset( $info['begin_widget_logic'] ) )
		$logic = $info['begin_widget_logic'];
	elseif ( isset( $wl_options[ $widget_id ] ) ){
		$logic = stripslashes( $wl_options[ $widget_id ] );
		begin_widget_logic_save( $widget_id, $logic );
		unset( $wl_options[ $widget_id ] );
		update_option( 'begin_widget_logic', $wl_options );
	}
	else
		$logic = '';
	return $logic;
}

function begin_widget_logic_save( $widget_id, $logic ){
	global $wl_options;
	if ( preg_match( '/^(.+)-(\d+)$/', $widget_id, $m ) ){
		$widget_class = $m[1];
		$widget_i = $m[2];
		$info = get_option( 'widget_'.$widget_class );
		if ( !is_array( $info[ $widget_i ] ) )
			$info[ $widget_i ] = array();
		$info[ $widget_i ]['begin_widget_logic'] = $logic;
		update_option( 'widget_'.$widget_class, $info );
	}else{
		$info = (array)get_option( 'widget_'.$widget_id, array() );
		$info['begin_widget_logic'] = $logic;
		update_option( 'widget_'.$widget_id, $info );
	}
}

// CALLED ON 'sidebars_widgets' FILTER
function begin_widget_logic_filter_sidebars_widgets( $sidebars_widgets ){
	global $wl_options, $wl_in_customizer;
	if ( $wl_in_customizer )
		return $sidebars_widgets;
	if ( !empty( $wl_options['begin_widget_logic-options-wp_reset_query'] ) )
		wp_reset_query();
	foreach($sidebars_widgets as $widget_area => $widget_list){
		if ($widget_area=='wp_inactive_widgets' || empty($widget_list))
			continue;
		foreach($widget_list as $pos => $widget_id){
			$logic = begin_widget_logic_by_id( $widget_id );
			if ( !begin_widget_logic_check_logic( $logic ) )
				unset($sidebars_widgets[$widget_area][$pos]);
		}
	}
	return $sidebars_widgets;
}

function begin_widget_logic_check_logic( $logic ){
	$logic = @trim( (string)$logic );
	$logic = apply_filters( "begin_widget_logic_eval_override", $logic );
	if ( is_bool( $logic ) )
		return $logic;
	if ( $logic === '' )
		return true;
	if ( stristr( $logic, "return" ) === false )
		$logic = "return ( $logic );";
	try {
		$show_widget = eval($logic);
	}
	catch ( Error $e ) {
		trigger_error( $e->getMessage(), E_USER_WARNING );
		$show_widget = false;
	}
	restore_error_handler();
	return $show_widget;
}