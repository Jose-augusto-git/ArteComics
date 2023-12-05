<?php

/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woothemes.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.2.1
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="woocommerce-order">

	<?php 

if ( $order ) {
    ?>

		<?php 
    
    if ( $order->has_status( 'failed' ) ) {
        ?>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php 
        esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'banner-management-for-woocommerce' );
        ?></p>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                <a href="<?php 
        echo  esc_url( $order->get_checkout_payment_url() ) ;
        ?>"
                   class="button pay"><?php 
        esc_html_e( 'Pay', 'banner-management-for-woocommerce' );
        ?></a>
				<?php 
        
        if ( is_user_logged_in() ) {
            ?>
                    <a href="<?php 
            echo  esc_url( wc_get_page_permalink( 'myaccount' ) ) ;
            ?>"
                       class="button pay"><?php 
            esc_html_e( 'My account', 'banner-management-for-woocommerce' );
            ?></a>
				<?php 
        }
        
        ?>
            </p>
		<?php 
    } else {
        $wbm_thankyou_page_stored_results_serialize_benner_src = '';
        $wbm_thankyou_page_stored_results_serialize_benner_link = '';
        $wbm_thankyou_page_stored_results_serialize_benner_enable_status = '';
        $wbm_thankyou_page_stored_results_serialize_banner_target = '';
        $wbm_thankyou_page_stored_results_serialize_banner_relation = '';
        $alt_tag_value = '';
        $wbm_thankyou_page_stored_results = ( function_exists( 'wcbm_get_page_banner_data' ) ? wcbm_get_page_banner_data( 'thankyou' ) : '' );
        if ( function_exists( 'wcbm_get_page_banner_data' ) ) {
            $wbm_shop_page_stored_results = wcbm_get_page_banner_data( 'shop' );
        }
        $wbm_shop_page_stored_results_serialize = $wbm_shop_page_stored_results;
        $wbm_shop_page_stored_results_serialize_banner_image_size = ( !empty($wbm_shop_page_stored_results_serialize['shop_page_banner_image_size']) ? $wbm_shop_page_stored_results_serialize['shop_page_banner_image_size'] : '' );
        $banner_global_select_size_class = ( function_exists( 'get_banner_class' ) ? get_banner_class( $wbm_shop_page_stored_results_serialize_banner_image_size ) : '' );
        
        if ( isset( $wbm_thankyou_page_stored_results ) && !empty($wbm_thankyou_page_stored_results) ) {
            $wbm_thankyou_page_stored_results_serialize = $wbm_thankyou_page_stored_results;
            
            if ( !empty($wbm_thankyou_page_stored_results_serialize) ) {
                $wbm_thankyou_page_stored_results_serialize_benner_src = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_image_src']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_image_src'] : '' );
                $wbm_thankyou_page_stored_results_serialize_benner_link = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_link_src']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_link_src'] : '' );
                $wbm_thankyou_page_stored_results_serialize_benner_enable_status = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_enable_status']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_enable_status'] : '' );
                $wbm_thankyou_page_stored_results_serialize_banner_target = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_target']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_target'] : '' );
                $wbm_thankyou_page_stored_results_serialize_banner_relation = ( !empty($wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_relation']) ? $wbm_thankyou_page_stored_results_serialize['thankyou_page_banner_relation'] : '' );
            }
        
        }
        
        
        if ( !empty($wbm_thankyou_page_stored_results_serialize_benner_enable_status) && $wbm_thankyou_page_stored_results_serialize_benner_enable_status === 'on' ) {
            ?>
						<div class="wbm_banner_image <?php 
            echo  esc_attr( $banner_global_select_size_class ) ;
            ?>">
							<?php 
            
            if ( '' === $wbm_thankyou_page_stored_results_serialize_benner_link ) {
                $alt_tag_css_thankyou_page_front = 'class="no-link"';
            } else {
                
                if ( !preg_match( "~^(?:f|ht)tps?://~i", $wbm_thankyou_page_stored_results_serialize_benner_link ) ) {
                    $image_link = "http://" . $wbm_thankyou_page_stored_results_serialize_benner_link;
                } else {
                    $image_link = $wbm_thankyou_page_stored_results_serialize_benner_link;
                }
                
                
                if ( 'self' === $wbm_thankyou_page_stored_results_serialize_banner_target ) {
                    $target_attr = "_self";
                } else {
                    $target_attr = "_blank";
                }
                
                
                if ( 'nofollow' === $wbm_thankyou_page_stored_results_serialize_banner_relation ) {
                    $rel_attr = "noopener noreferrer nofollow";
                } else {
                    $rel_attr = "noopener noreferrer  follow";
                }
                
                $alt_tag_css_thankyou_page_front = 'href="' . esc_url( $image_link ) . '" target="' . esc_attr( $target_attr ) . '" ref="' . esc_attr( $rel_attr ) . '"';
            }
            
            ?>
                            <a <?php 
            echo  wp_kses_post( $alt_tag_css_thankyou_page_front ) ;
            ?>>
                                <p>
                                    <img src="<?php 
            echo  esc_url( $wbm_thankyou_page_stored_results_serialize_benner_src ) ;
            ?>"
                                         class="category_banner_image" alt="<?php 
            echo  esc_attr( $alt_tag_value ) ;
            ?>">
                                </p>
                            </a>
                        </div>
						<?php 
        }
        
        ?>
            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php 
        echo  esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'banner-management-for-woocommerce' ), $order ) ) ;
        ?></p>

            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

                <li class="woocommerce-order-overview__order order">
					<?php 
        esc_html_e( 'Order number:', 'banner-management-for-woocommerce' );
        ?>
                    <strong><?php 
        echo  esc_html( $order->get_order_number() ) ;
        ?></strong>
                </li>

                <li class="woocommerce-order-overview__date date">
					<?php 
        esc_html_e( 'Date:', 'banner-management-for-woocommerce' );
        ?>
                    <strong><?php 
        echo  wp_kses_post( wc_format_datetime( $order->get_date_created() ) ) ;
        ?></strong>
                </li>

                <li class="woocommerce-order-overview__total total">
					<?php 
        esc_html_e( 'Total:', 'banner-management-for-woocommerce' );
        ?>
                    <strong><?php 
        echo  wp_kses_post( $order->get_formatted_order_total() ) ;
        ?></strong>
                </li>

				<?php 
        
        if ( $order->get_payment_method_title() ) {
            ?>

                    <li class="woocommerce-order-overview__payment-method method">
						<?php 
            esc_html_e( 'Payment method:', 'banner-management-for-woocommerce' );
            ?>
                        <strong><?php 
            echo  wp_kses_post( $order->get_payment_method_title() ) ;
            ?></strong>
                    </li>

				<?php 
        }
        
        ?>
            </ul>
            <div class="clear"></div>
		<?php 
    }
    
    ?>
		<?php 
    do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
    ?>
		<?php 
    do_action( 'woocommerce_thankyou', $order->get_id() );
    ?>
	<?php 
} else {
    ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php 
    echo  esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'banner-management-for-woocommerce' ), null ) ) ;
    ?></p>

	<?php 
}

?>
</div>
