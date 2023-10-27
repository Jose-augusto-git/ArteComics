<?php
namespace BinaryCarpenter\BC_MNC;

use BinaryCarpenter\BC_MNC\Options_Name as Oname;
use WC_Product;
class Cart_Styles {
    private $design_options, $cart_list_style_class;

    public function __construct($cart_list_style_class, BC_Options $design_options) {
        $this->cart_list_style_class = $cart_list_style_class;
        $this->design_options = $design_options;
    }

    public function get_style() {
        $display_go_to_cart = $this->design_options->get_bool(Oname::DISPLAY_GO_TO_CART_BUTTON, true);
        $display_go_to_checkout = $this->design_options->get_bool(Oname::DISPLAY_GO_TO_CHECKOUT_BUTTON, true);

        $go_to_cart_html = $display_go_to_cart ? Cart_Details::generate_go_to_cart_button($this->design_options) : '';
        $go_to_checkout_html = $display_go_to_checkout ? Cart_Details::generate_go_to_checkout_button($this->design_options) : '';


        $cart_total_html = sprintf('<div class="bc-uk-flex bc-uk-flex-between bc-uk-width-1-1 bc-mnc__cart-details--cart-total"><div class="bc-mnc__cart-details--cart-total__title"><strong>%1$s </strong></div><div class="bc-mnc__cart-details--cart-total__amount">%2$s</div></div>', $this->design_options->get_string(Oname::CART_LIST_SUBTOTAL_TEXT, __('Subtotal', 'bc-menu-cart-woo'), false), Cart_Display::generate_cart_total());

        $mini_cart_heading = sprintf('<h3 class="bc-mnc__cart-details--header">%1$s</h3>', $this->design_options->get_string(Oname::CART_LIST_HEADER_TEXT, __('Your cart', 'bc-menu-cart-woo')));
        $cart_items = \WC()->cart->get_cart();

        if (count($cart_items) == 0) {
            $cart_items_html =  __('Your cart is empty', 'bc-menu-cart-woo');
            return sprintf('<section style="overflow: visible;" class="bc-mnc__cart-details--items-section bc-menu-cart-cart-details-%1$s clearfix">  %2$s %3$s %4$s <div></div></section>', $this->design_options->get_post_id(), $mini_cart_heading, $cart_items_html, Cart_Details::generate_cart_close_icon_html($this->design_options));
        }

        //style 1 and 2 share the same skeleton
        if ($this->cart_list_style_class == 'bc-mnc__cart-details-style-1' || $this->cart_list_style_class == 'bc-mnc__cart-details-style-2' ) {
            return $this->dg1($go_to_cart_html, $go_to_checkout_html, $cart_items, $mini_cart_heading, $cart_total_html);
        } else if ($this->cart_list_style_class == 'bc-mnc__cart-details-style-3' || $this->cart_list_style_class == 'bc-mnc__cart-details-style-4')
        {
            return $this->dg2($go_to_cart_html, $go_to_checkout_html, $cart_items, $mini_cart_heading, $cart_total_html);
        }

        return '';
    }

    /**
     * @param string $go_to_cart_html
     * @param string $go_to_checkout_html
     * @param array $cart_items
     * @param string $mini_cart_heading
     * @param string $cart_total_html
     * @return string
     */
    private function dg1(string $go_to_cart_html, string $go_to_checkout_html, array $cart_items, string $mini_cart_heading, string $cart_total_html): string
    {
        $cart_items_html = '';
        //the list of items in cart

        $cart_checkout_buttons = sprintf('<div class="bc-mnc__cart-checkout-container bc-uk-flex bc-uk-flex-between">%1$s %2$s</div>', $go_to_cart_html, $go_to_checkout_html);

        foreach ($cart_items as $single_cart_item) {
            $product_id = $single_cart_item['product_id'];
            $variation_id = $single_cart_item['variation_id'];
            $quantity = $single_cart_item['quantity'];
            $variation_array = $single_cart_item['variation'];
            $product_cart_data = Cart_Details::generate_single_item_cart_data($single_cart_item);

            $variations_string = '';

            if (is_array($variation_array) && count($variation_array) > 0) {
                foreach ($variation_array as $k => $v) {

                    $variation_tile = explode("_", $k);
                    $variation_tile = $variation_tile[count($variation_tile) - 1];
                    $variation_tile = ucfirst(str_replace("-", " ", $variation_tile));
                    $variations_string .= sprintf('<div class="bc-mnc__single-variation">%1$s: %2$s</div>', ucfirst($variation_tile), wc_attribute_label($v));
                }

            }

            $product = new WC_Product($product_id);

            //Get the product removal button HTML
            $remove_product_button_html = Cart_Details::generate_product_removal_button_html($this->design_options, $single_cart_item);
            $product_title = Cart_Details::generate_product_title_html($product, $this->design_options);
            error_log("++++++++++++++++++++++++++++++++");
            $product_price = Cart_Details::generate_product_price($product, $variation_id, $this->design_options);
            error_log("product price is: " . $product_price);

            $line_sub_total = wc_price($product_price * ($quantity));

            $product_price = wc_price($product_price);

            //total string of a single product, it is style dependant
            $total_string = sprintf('<span>%1$s</span> x <span>%2$s</span> = <strong>%3$s</strong>', $product_price, $quantity, $line_sub_total);

            $product_image_html = Cart_Details::generate_product_image_html($this->design_options, $product, $variation_id);

            $product_quantity_change = Cart_Details::generate_product_change_section_html($this->design_options, $quantity);

            $cart_items_html .= sprintf('<div class="bc-mnc__cart-details--single-item"><div class="bc-uk-flex"> %5$s %1$s  <div class="bc-mnc__cart-details--single-item__info bc-uk-width-1-1"> <div class="bc-mnc__cart-details--single-item__info--title">%2$s</div> <div class="bc-mnc__cart-details--single-item__info--attributes"> %3$s<!-- display variation name here, if not blank --> </div> <div class="bc-mnc__cart-details--single-item__info--order-total"> %4$s<!-- display qty x price = total here --> </div> %6$s <!-- display the product change boxes here -->  </div>  </div>%7$s</div>', $product_image_html, $product_title, $variations_string, $total_string, $remove_product_button_html, $product_quantity_change, $product_cart_data);


        }

        //wrap the $$cart_items_html inside a div
        $cart_items_html = '<div class="bc-mnc__cart-details--all-items">' . $cart_items_html . '</div>';

        return sprintf('<section class="bc-mnc__cart-details--items-section bc-menu-cart-cart-details-%1$s clearfix">  %2$s %3$s %4$s %5$s %6$s</section>',
            $this->design_options->get_post_id(),
            $mini_cart_heading,
            $cart_items_html, $cart_total_html,
            $cart_checkout_buttons,
            Cart_Details::generate_cart_close_icon_html($this->design_options)
        );
    }

    /**
     * @param string $go_to_cart_html
     * @param string $go_to_checkout_html
     * @param array $cart_items
     * @param string $mini_cart_heading
     * @param string $cart_total_html
     * @return string
     */
    private function dg2(string $go_to_cart_html, string $go_to_checkout_html, array $cart_items, string $mini_cart_heading, string $cart_total_html): string
    {
        $cart_items_html = '';
        $cart_checkout_buttons = sprintf('<div class="bc-mnc__cart-checkout-container bc-uk-flex bc-uk-flex-between">%1$s %2$s</div>', $go_to_cart_html, $go_to_checkout_html);

        foreach ($cart_items as $single_cart_item) {
            $product_id = $single_cart_item['product_id'];
            $variation_id = $single_cart_item['variation_id'];
            $quantity = $single_cart_item['quantity'];
            $variation_array = $single_cart_item['variation'];
//            $line_sub_total = $single_cart_item['line_subtotal'];
            $product_cart_data = Cart_Details::generate_single_item_cart_data($single_cart_item);

            $variations_string = '';

            if (is_countable($variation_array) && count($variation_array) > 0) {
                $temp_variation = [];

                foreach ($variation_array as $v)
                    $temp_variation[] =  wc_attribute_label($v);

                $variations_string .= sprintf('<span class="bc-mnc__single-variation">%1$s</span>', implode(" - ", $temp_variation));
            }

            $product = new WC_Product($product_id);

            $product_price = Cart_Details::generate_product_price($product, $variation_id, $this->design_options);
            $line_sub_total = wc_price($product_price * ($quantity));

            error_log(">>> " . $product_price . " ==== " . $quantity);

            //Get the product removal button HTML
            $remove_product_button_html = Cart_Details::generate_product_removal_button_html($this->design_options, $single_cart_item);
            $product_title = Cart_Details::generate_product_title_html($product, $this->design_options);


            //total string of a single product, it is style dependant
            $total_string = sprintf('<strong>%1$s</strong>', ($line_sub_total));

            $product_image_html = Cart_Details::generate_product_image_html($this->design_options, $product, $variation_id);

            $product_quantity_change = Cart_Details::generate_product_change_section_html($this->design_options, $quantity, 'increase');

            $cart_items_html .= sprintf('<div class="bc-mnc__cart-details--single-item"><div class="bc-uk-flex"> %5$s %1$s  <div class="bc-mnc__cart-details--single-item__info bc-uk-width-1-1"> <div class="bc-mnc__cart-details--single-item__info--title">%2$s</div> <div class="bc-mnc__cart-details--single-item__info--attributes"> %3$s<!-- display variation name here, if not blank --> </div> <div class="bc-mnc__cart-details--single-item__info--order-total"> %4$s<!-- display qty x price = total here --> </div>   </div>  </div>%6$s <!-- display the product change boxes here --> %7$s</div>',
                $product_image_html,
                $product_title,
                $variations_string,
                $total_string,
                $remove_product_button_html,
                $product_quantity_change,
                $product_cart_data
            );

        }

        //wrap the $$cart_items_html inside a div
        $cart_items_html = '<div class="bc-mnc__cart-details--all-items">' . $cart_items_html . '</div>';

        return sprintf('<section class="bc-mnc__cart-details--items-section bc-menu-cart-cart-details-%1$s clearfix">  %2$s %3$s %4$s %5$s %6$s',
            $this->design_options->get_post_id(),
            $mini_cart_heading,
            $cart_items_html,
            $cart_total_html,
            $cart_checkout_buttons,
            Cart_Details::generate_cart_close_icon_html($this->design_options)
        );
    }

}
