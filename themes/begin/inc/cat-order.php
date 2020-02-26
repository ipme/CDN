<?php
/**
 * Name: WP Term Order 分类排序
 * Author:      John James Jacoby
 * Author URI:  https://jjj.me/
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'ZM_Term_Order' ) ) :
final class ZM_Term_Order {
	public $version = '0.1.4';
	public $db_version = 201510280002;
	public $db_version_key = 'wpdb_term_taxonomy_version';
	public $file = '';
	public $url = '';
	public $fancy = false;
	public function __construct() {
		// 安装
		$this->file     = __FILE__;
		$this->url      = get_template_directory_uri( $this->file );
		$this->fancy    = apply_filters( 'wp_fancy_term_order', true );
		// 查询
		add_filter( 'get_terms_orderby', array( $this, 'get_terms_orderby' ), 10, 2 );
		add_action( 'create_term',       array( $this, 'add_term_order'    ), 10, 3 );
		add_action( 'edit_term',         array( $this, 'add_term_order'    ), 10, 3 );
		// 获得分类
		$taxonomies = $this->get_taxonomies();
		foreach ( $taxonomies as $value ) {
			if ( false === $this->fancy ) {
				add_filter( "manage_edit-{$value}_columns",          array( $this, 'add_column_header' ) );
				add_filter( "manage_{$value}_custom_column",         array( $this, 'add_column_value' ), 10, 3 );
				add_filter( "manage_edit-{$value}_sortable_columns", array( $this, 'sortable_columns' ) );
			}
			add_action( "{$value}_add_form_fields",  array( $this, 'term_order_add_form_field'  ) );
			add_action( "{$value}_edit_form_fields", array( $this, 'term_order_edit_form_field' ) );
		}
		add_action( 'wp_ajax_reordering_terms', array( $this, 'ajax_reordering_terms' ) );
		if ( is_blog_admin() || doing_action( 'wp_ajax_inline_save_tax' ) ) {
			add_action( 'admin_init',         array( $this, 'admin_init' ) );
			add_action( 'load-edit-tags.php', array( $this, 'edit_tags'  ) );
		}
	}

	// Administration area hooks
	public function admin_init() {
		$this->maybe_upgrade_database();
	}

	public function edit_tags() {
		// Enqueue javascript
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	 add_action( 'admin_head',            array( $this, 'admin_head'      ) );
		// Quick edit
		add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_term_order' ), 10, 3 );
	}
	// 调用资源
	public function enqueue_scripts() {
		// Enqueue fancy ordering
		if ( true === $this->fancy ) {
			wp_enqueue_script( 'term-order-reorder', $this->url . '/js/reorder.js', array( 'jquery-ui-sortable' ), $this->db_version, true );
		}
	}
	// 样式
	public function admin_head() {
		if ( true === $this->fancy ) {

		} ?>

		<style type="text/css">
			.column-order {
				text-align: center;
				width: 74px;
			}

			<?php if ( true === $this->fancy ) : ?>

			.wp-list-table .ui-sortable tr:not(.no-items) {
				cursor: move;
			}

			.striped.dragging > tbody > .ui-sortable-helper ~ tr:nth-child(even) {
				background: #f9f9f9;
			}

			.striped.dragging > tbody > .ui-sortable-helper ~ tr:nth-child(odd) {
				background: #fff;
			}

			.wp-list-table .to-updating tr,
			.wp-list-table .ui-sortable tr.inline-editor {
				cursor: default;
			}

			.wp-list-table .ui-sortable-placeholder {
				outline: 1px dashed #bbb;
				background: #f1f1f1 !important;
				visibility: visible !important;
			}
			.wp-list-table .ui-sortable-helper {
				background-color: #fff !important;
				outline: 1px solid #bbb;
			}
			.wp-list-table .ui-sortable-helper .row-actions {
				visibility: hidden;
			}
			.to-row-updating .check-column {
				background: url('<?php echo admin_url( '/images/spinner.gif' );?>') 10px 9px no-repeat;
			}
			@media print,
			(-o-min-device-pixel-ratio: 5/4),
			(-webkit-min-device-pixel-ratio: 1.25),
			(min-resolution: 120dpi) {
				.to-row-updating .check-column {
					background-image: url('<?php echo admin_url( '/images/spinner-2x.gif' );?>');
					background-size: 20px 20px;
				}
			}
			.to-row-updating .check-column input {
				visibility: hidden;
			}
			<?php endif; ?>
		</style>
	<?php
	}


	private static function get_taxonomies( $args = array() ) {
		$r = wp_parse_args( $args, array(
			'show_ui' => true
		) );
		$taxonomies = get_taxonomies( $r );
		return apply_filters( 'wp_term_order_get_taxonomies', $taxonomies, $r, $args );
	}

	// 列 -------------------------


	public function add_column_header( $columns = array() ) {
		$columns['order'] = __( '排序', 'begin' );
		return $columns;
	}


	public function add_column_value( $empty = '', $custom_column = '', $term_id = 0 ) {
		if ( empty( $_REQUEST['taxonomy'] ) || ( 'order' !== $custom_column ) || ! empty( $empty ) ) {
			return;
		}
		return $this->get_term_order( $term_id );
	}

	public function sortable_columns( $columns = array() ) {
		$columns['order'] = 'order';
		return $columns;
	}

	public function add_term_order( $term_id = 0, $tt_id = 0, $taxonomy = '' ) {
		$order = ! empty( $_POST['order'] )
			? (int) $_POST['order']
			: 0;

		self::set_term_order( $term_id, $taxonomy, $order );
	}

	public static function set_term_order( $term_id = 0, $taxonomy = '', $order = 0, $clean_cache = false ) {
		global $wpdb;
		$wpdb->update(
			$wpdb->term_taxonomy,
			array(
				'order' => $order
			),
			array(
				'term_id'  => $term_id,
				'taxonomy' => $taxonomy
			)
		);

		if ( true === $clean_cache ) {
			clean_term_cache( $term_id, $taxonomy );
		}
	}

	public function get_term_order( $term_id = 0 ) {
		$term = get_term( $term_id, $_REQUEST['taxonomy'] );
		$retval = 0;
		if ( isset( $term->order ) ) {
			$retval = $term->order;
		}
		if ( empty( $retval ) ) {
			$key    = "term_order_{$term->taxonomy}";
			$orders = get_option( $key, array() );

			if ( ! empty( $orders ) ) {
				foreach ( $orders as $position => $value ) {
					if ( $value === $term->term_id ) {
						$retval = $position;
						break;
					}
				}
			}
		}
		return (int) $retval;
	}

	// 添加新术语时输出“订单”表单字段
	public static function term_order_add_form_field() {
		?>

		<div class="form-field form-required">
			<label for="order">
				<?php esc_html_e( '分类排序', 'begin' ); ?>
			</label>
			<input type="number" pattern="[0-9.]+" name="order" id="order" value="0" size="11">
			<p class="description">
				<?php esc_html_e( '输入数字自定义排序。', 'begin' ); ?>
			</p>
		</div>
		<?php
	}


	// 编辑分类添加排序项
	public function term_order_edit_form_field( $term = false ) {
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="order">
					<?php esc_html_e( '分类排序', 'begin' ); ?>
				</label>
			</th>
			<td>
				<input name="order" id="order" type="text" value="<?php echo $this->get_term_order( $term ); ?>" size="11" />
				<p class="description">
					<?php esc_html_e( '输入数字自定义排序。', 'begin' ); ?>
				</p>
			</td>
		</tr>

		<?php
	}

	// 输出“order”快速编辑字段
	public function quick_edit_term_order( $column_name = '', $screen = '', $name = '' ) {
		if ( ( 'order' !== $column_name ) || ( 'edit-tags' !== $screen ) || ! in_array( $name, $this->get_taxonomies() ) ) {
			return false;
		} ?>

		<fieldset>
			<div class="inline-edit-col">
				<label>
					<span class="title"><?php esc_html_e( '排序', 'begin' ); ?></span>
					<span class="input-text-wrap">
						<input type="number" pattern="[0-9.]+" class="ptitle" name="order" value="" size="11">
					</span>
				</label>
			</div>
		</fieldset>

		<?php
	}

	// 查询过滤器
	public function get_terms_orderby( $orderby = 'name', $args = array() ) {
		if ( ! empty( $_GET['orderby'] ) && ! empty( $_GET['taxonomy'] ) ) {
			return $orderby;
		}
		if ( empty( $args['orderby'] ) || empty( $orderby ) || ( 'order' === $args['orderby'] ) || in_array( $orderby, array( 'name', 't.name' ) ) ) {
			$orderby = 'tt.order';
		} elseif ( 't.name' === $orderby ) {
			$orderby = 'tt.order, t.name';
		}
		return $orderby;
	}

		// 数据库修改

		// 是否应该进行数据库更新
	private function maybe_upgrade_database() {

		// Check DB for version
		$db_version = get_option( $this->db_version_key );

		// Needs
		if ( $db_version < $this->db_version ) {
			$this->upgrade_database( $db_version );
		}
	}

	// 修改`term_taxonomy`表并向其添加`order`列
	private function upgrade_database( $old_version = 0 ) {
		global $wpdb;
		$old_version = (int) $old_version;
		if ( $old_version < 201508110005 ) {
			$wpdb->query( "ALTER TABLE `{$wpdb->term_taxonomy}` ADD `order` INT (11) NOT NULL DEFAULT 0;" );
		}
		update_option( $this->db_version_key, $this->db_version );
	}

	// 处理Ajax项重新排序

	public static function ajax_reordering_terms() {
		if ( empty( $_POST['id'] ) || empty( $_POST['tax'] ) || ( ! isset( $_POST['previd'] ) && ! isset( $_POST['nextid'] ) ) ) {
			die( -1 );
		}

		$tax = get_taxonomy( $_POST['tax'] );

		if ( empty( $tax ) ) {
			die( -1 );
		}

		if ( ! current_user_can( $tax->cap->edit_terms ) ) {
			die( -1 );
		}

		$term = get_term( $_POST['id'], $_POST['tax'] );
		if ( empty( $term ) ) {
			die( -1 );
		}

		$taxonomy = $_POST['tax'];
		$previd   = empty( $_POST['previd']   ) ? false : (int) $_POST['previd'];
		$nextid   = empty( $_POST['nextid']   ) ? false : (int) $_POST['nextid'];
		$start    = empty( $_POST['start']    ) ? 1     : (int) $_POST['start'];
		$excluded = empty( $_POST['excluded'] ) ?
			array( $term->term_id ) :
			array_filter( (array) $_POST['excluded'], 'intval' );

		$new_pos     = array();
		$return_data = new stdClass;

		$parent_id        = $term->parent;
		$next_term_parent = $nextid
			? wp_get_term_taxonomy_parent_id( $nextid, $taxonomy )
			: false;

		if ( $previd === $next_term_parent ) {
			$parent_id = $next_term_parent;

		} elseif ( $next_term_parent !== $parent_id ) {
			$prev_term_parent = $previd
				? wp_get_term_taxonomy_parent_id( $nextid, $taxonomy )
				: false;

			if ( $prev_term_parent !== $parent_id ) {
				$parent_id = ( $prev_term_parent !== false )
					? $prev_term_parent
					: $next_term_parent;
			}
		}

		if ( $next_term_parent !== $parent_id ) {
			$nextid = false;
		}

		$siblings = get_terms( $taxonomy, array(
			'depth'      => 1,
			'number'     => 100,
			'parent'     => $parent_id,
			'orderby'    => 'order',
			'order'      => 'ASC',
			'hide_empty' => false,
			'exclude'    => $excluded
		) );

		foreach ( $siblings as $sibling ) {

			if ( $sibling->term_id === (int) $term->term_id ) {
				continue;
			}

			if ( $nextid === (int) $sibling->term_id ) {
				self::set_term_order( $term->term_id, $taxonomy, $start, true );

				$ancestors = get_ancestors( $term->term_id, $taxonomy, 'taxonomy' );

				$new_pos[ $term->term_id ] = array(
					'order'  => $start,
					'parent' => $parent_id,
					'depth'  => count( $ancestors ),
				);

				$start++;
			}

			if ( isset( $new_pos[ $term->term_id ] ) && (int) $sibling->order >= $start ) {
				$return_data->next = false;
				break;
			}

			if ( $start !== (int) $sibling->order ) {
				self::set_term_order( $sibling->term_id, $taxonomy, $start, true );
			}

			$new_pos[ $sibling->term_id ] = $start;
			$start++;

			if ( empty( $nextid ) && ( $previd === (int) $sibling->term_id ) ) {
				self::set_term_order( $term->term_id, $taxonomy, $start, true );

				$ancestors = get_ancestors( $term->term_id, $taxonomy, 'taxonomy' );

				$new_pos[ $term->term_id ] = array(
					'order'  => $start,
					'parent' => $parent_id,
					'depth'  => count( $ancestors )
				);

				$start++;
			}
		}

		if ( ! isset( $return_data->next ) && count( $siblings ) > 1 ) {
			$return_data->next = array(
				'id'       => $term->term_id,
				'previd'   => $previd,
				'nextid'   => $nextid,
				'start'    => $start,
				'excluded' => array_merge( array_keys( $new_pos ), $excluded ),
				'taxonomy' => $taxonomy
			);
		} else {
			$return_data->next = false;
		}

		if ( empty( $return_data->next ) ) {

			$children = get_terms( $taxonomy, array(
				'number'     => 1,
				'depth'      => 1,
				'orderby'    => 'order',
				'order'      => 'ASC',
				'parent'     => $term->term_id,
				'fields'     => 'ids',
				'hide_empty' => false
			) );

			if ( ! empty( $children ) ) {
				die( 'children' );
			}
		}

		$return_data->new_pos = $new_pos;

		die( json_encode( $return_data ) );
	}
}
endif;

function _zm_term_order() {
	new ZM_Term_Order();
}
add_action( 'init', '_zm_term_order', 99 );