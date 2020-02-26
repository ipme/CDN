<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! function_exists( 'ascent_light_edd_buy_button' ) ) : ?>
<?php function ascent_light_edd_buy_button( $class = null ) { ?>
	<?php if( function_exists('edd_price')) { ?>
		<div class="text-center">
			<div class="product-cta <?php echo $class; ?> <?php edd_has_variable_prices(get_the_ID()) ? print 'product-cta-variable' : print 'product-cta-standard'; ?>">
				<div class="product-price">
					<?php 
						if ( edd_has_variable_prices(get_the_ID()) ) {
							// if the download has variable prices, show the first one as a starting price
							echo '<div class="edd_price">';
							echo '<span class="edd_tb">价格 </span>';
							echo edd_price(get_the_ID());
							echo '</div>'; 
							echo '<div class="edd_dy">'; 
							echo edd_get_purchase_link( array( 'id' => get_the_ID() ) );
							echo '</div>'; 
						} else {
							echo '<span class="edd_tb">价格 </span>'; 
							edd_price(get_the_ID());
						}
                    ?>
				</div><!--end .product-price-->
				<?php if ( function_exists('edd_price') ) { ?>
						
					<div class="product-buttons">
						<?php if ( !edd_has_variable_prices(get_the_ID()) ) { ?>
							<?php
								echo edd_get_purchase_link(
									array(
										'download_id' => get_the_ID(),
										'class' => 'edd-submit btn',
										'price' => 0, 
										'text' =>'购买'
									)
								);
							?>
						<?php } ?>
						<div class="clear"></div>
					</div><!--end .product-buttons-->
				<?php } ?>
			</div>
		</div>
       <?php } ?>
   <?php } ?>
<?php endif; ?>