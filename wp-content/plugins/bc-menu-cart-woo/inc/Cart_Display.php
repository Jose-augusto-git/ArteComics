<?php



namespace BinaryCarpenter\BC_MNC;
use BinaryCarpenter\BC_MNC\Options_Name as Oname;
use BinaryCarpenter\BC_MNC\Cart_Details as Cart_Details;
use BinaryCarpenter\BC_MNC\Mobile_Detect;
/**
 * @property array|mixed|void options
 */
class Cart_Display {

    /**
     * @return array
     */
	public static function get_designs_id_that_have_menu_linked()
    {
        //get the linked menu option
        $linked_options = BC_Options::get_all_options('bc_menu_cart_linked_menu')->posts;

        if (count($linked_options) == 0)
            return array();


        $menus = Helpers::get_menu_array();

        if(count($menus) == 0)
        {
            //there is no menu available, so skip
            return array();
        }

        //get the BC_Options object
        //get only the first item since we only store the options in one post
        $linked_menu_options = new BC_Options('bc_menu_cart_linked_menu', $linked_options[0]->ID);

        $result = array();

        //add filter to menu that has design attached to it
        foreach ($menus as $menu)
        {
            $design_option_id = $linked_menu_options->get_int($menu['slug']);
            if ($design_option_id > 0)
                $result[] = $design_option_id;
        }

        return array_unique($result);
    }


	/**
	 * Generate the cart icon and the circle that contains the item count
	 * @param BC_Options $design_options
	 *
	 * @return string
	 */
	public static function generate_cart_icon_and_circle_item_count(BC_Options $design_options)
    {
        $cart_layout = $design_options->get_int(Oname::CART_LAYOUT);
        error_log("AGENT ->>>>>>>>>" . $_SERVER['HTTP_USER_AGENT']);
        //If the user has specified a different layout for mobile, use that instead
        if (wp_is_mobile())
        {
            error_log("Cart is on mobile. The default layout (for desktop) is: " . $cart_layout);
            $mobile_layout = $design_options->get_int(Oname::CART_LAYOUT_MOBILE, -1, true);
            if ($mobile_layout != -1) {
                error_log("Has mobile layout. Use mobile layout is $mobile_layout");
                $cart_layout = $mobile_layout;
            }
        }

        $cart_html = '';
        $my_cart_text = $design_options->get_string(Oname::MY_CART_TEXT) != '' ? $design_options->get_string(Oname::MY_CART_TEXT) : 'My cart';


        error_log("Generating html for cart layout: $cart_layout");
        switch ($cart_layout)
        {
            case 0:
                $cart_html = sprintf('<div class="bc-mnc__cart-link--container bc-mnc__cart-link-layout-01">%1$s%2$s</div>', self::generate_cart_icon_html($design_options), self::generate_cart_count_circle($design_options));
                break;
            case 1:
                $cart_html = sprintf('<div class="bc-mnc__cart-link--container bc-mnc__cart-link-layout-02">%1$s <div class="bc-menu-cart-text-container">%2$s <hr class="bc-menu-cart-hr" /> %3$s</div> </div>', self::generate_cart_icon_html($design_options), self::generate_cart_item_count(), self::generate_cart_total());
                break;
            case 2:
                $cart_html = sprintf('<div class="bc-mnc__cart-link--container bc-mnc__cart-link-layout-03">%1$s <div class="bc-menu-cart-text-container">%2$s <hr class="bc-menu-cart-hr" /> %3$s</div> </div>', self::generate_cart_icon_html($design_options), $my_cart_text, self::generate_cart_item_count());
                break;
            case 3:
                $cart_html = sprintf('<div class="bc-mnc__cart-link--container bc-mnc__cart-link-layout-04"><div style="position: relative;">%1$s %4$s</div> <div class="bc-menu-cart-text-container">%2$s <hr class="bc-menu-cart-hr" /> %3$s</div> </div>', self::generate_cart_icon_html($design_options), $my_cart_text, self::generate_cart_total(), self::generate_cart_count_circle($design_options));
                break;
            case 4:
                $cart_html = sprintf('<div class="bc-mnc__cart-link--container bc-mnc__cart-link-layout-05">%1$s%2$s</div>', self::generate_cart_icon_html($design_options), self::generate_cart_count_circle($design_options));
                break;
            case 5:
                $cart_html = sprintf('<div class="bc-mnc__cart-link--container bc-mnc__cart-link-layout-06">%1$s%2$s</div>', self::generate_cart_total(), self::generate_cart_item_count());
                break;
        }

        return $cart_html;
    }


	/**
	 * Generate the a element of the cart icon
	 * @param BC_Options $design_options
	 *
	 * @return string
	 */
    public static function generate_cart_a(BC_Options $design_options)
    {


        $cart_icon_with_layout = self::generate_cart_icon_and_circle_item_count($design_options);

        //1. Build the item skeleton

        $on_icon_click = $design_options->get_string(Oname::ON_CART_ICON_CLICK, '', true);
        $on_icon_hover = $design_options->get_string(Oname::ON_CART_ICON_HOVER);





        //these classes will determine the action of class on click or hover
        $on_click_class = '';
        $on_hover_class = '';
        $link_href = '#';

        switch ($on_icon_click)
        {
            case 'go_to_cart':
                $link_href = wc_get_cart_url();
                break;
            case 'show_cart_list':
                if (!Config::IS_FREE)
                    $on_click_class = 'bc-mnc__cart-link--show-details-on-click';
                break;
            case 'do_nothing':
                $link_href = '#';
                break;
            default:
                $link_href = wc_get_cart_url();
        }

        //If user is using free version, go to cart by default
        if (Config::IS_FREE)
        	$link_href = wc_get_cart_url();


        //free user doesn't see cart content on hover or click so this only available for pro
        if (!Config::IS_FREE) {
            if ($on_icon_hover == 'show_cart_list' && !wp_is_mobile()) //on mobile, there is no hover => disable this
                //adding this class will trigger on click on mobile
                $on_hover_class = 'bc-mnc__cart-link--show-details-on-hover';
        } else {
            $on_hover_class = '';
        }





        //attach the design option id to the outer class, it will be used to update the cart later
        $outer_class = "bc-mnc__style-" . $design_options->get_post_id();

        $on_action_classes =$on_hover_class . ' ' . $on_click_class;


        return sprintf('<a href="%1$s" data-bc_mnc-id="%5$s" class="bc-mnc__cart-link %2$s %3$s">%4$s</a>',
            $link_href,
            $on_action_classes,
            $outer_class,
            $cart_icon_with_layout,
            $design_options->get_post_id()
        );


    }

    /**
     * @param $design_option_id
     * @param string $wrapper : default is li as it is displayed on a menu. However, in case of shortcode, it could be other things
     *
     * actually get the HTML for the menu item
     * @return string
     */
	public static function generate_menu_item_html($design_option_id, $wrapper = 'li')
    {
        if (get_post_status($design_option_id) != 'publish') {
            error_log("option ID is not published");
            return '';
        }

        $design_options = new BC_Options(Config::OPTION_NAME, $design_option_id);
        $only_show_for_loggedin_users = $design_options->get_bool(Oname::ONLY_SHOW_FOR_LOGGEDIN_USER, false);

        if ($only_show_for_loggedin_users && !is_user_logged_in()) {
            return '';
        }

        /**
         * If the user sets hide cart when it's empty, then do not display it when cart is empty
         */
        $always_display_cart = $design_options->get_bool(Oname::ALWAYS_DISPLAY, false);

        if (is_null(\WC()->cart) || (!$always_display_cart && \WC()->cart->is_empty()))
            return '';


        //generate the cart link (a) section
        $cart_a_section = self::generate_cart_a($design_options);

        $on_icon_click = $design_options->get_string(Oname::ON_CART_ICON_CLICK, '', true);
        $on_icon_hover = $design_options->get_string(Oname::ON_CART_ICON_HOVER);


        $hide_cart_details_on_mouse_out = $design_options->get_bool(Oname::HIDE_CART_ON_CURSOR_OUT);

        $hide_cart_on_mouse_out_class = $hide_cart_details_on_mouse_out? 'bc_mnc-hide-on-mouse-out' : '';



        $cart_list_style_class = $design_options->get_string(Oname::CART_LIST_STYLE_CLASS, 'bc-mnc__cart-details-style-1');

        $cart_float = $design_options->get_string(Oname::CART_FLOAT, 'bc-mnc__float-none');

        //if the user set to not show cart details on hover nor click, don't generate the cart details list
        $cart_details_html = '';

        if (!Config::IS_FREE && class_exists("BinaryCarpenter\BC_MNC\Cart_Details"))
          $cart_details_html = ($on_icon_hover == 'do_nothing' && $on_icon_click == 'do_nothing') ? '' : Cart_Details::generate_cart_items_list($design_options);

        //
        $relative_to_element = $design_options->get_string(Oname::DESIGN_MOBILE_POSITION_RELATIVE_TO_ELEMENT);
        $relative_position = $design_options->get_string(Oname::DESIGN_MOBILE_POSITION);
        $remove_origin_element = $design_options->get_bool(Oname::DESIGN_MOBILE_REMOVE_ORIGIN_ELEMENT) ? 1 : 0;
        $extra_css = str_replace("\n", " ", $design_options->get_string(Oname::DESIGN_MOBILE_EXTRA_STYLE));


        $relative_to_desktop_element = $design_options->get_string(Oname::DESIGN_DESKTOP_POSITION_RELATIVE_TO_ELEMENT);
        $relative_desktop_position = $design_options->get_string(Oname::DESIGN_DESKTOP_POSITION);

        $admin_bar_class = '';

        $display_close_cart_link = $design_options->get_bool(Oname::DISPLAY_CLOSE_CART_LINK, true);
        $display_close_cart_text = $design_options->get_string(Oname::GO_TO_CART_BUTTON_TEXT, __('close', 'bc-menu-cart-woo') , false);

        $cart_link = '';

        if ($display_close_cart_link)
            $cart_link = "<div id='bc_mnc-close-cart-link'><span class=''>$display_close_cart_text</span></div>";
        //if there is an user logged in and the site is on mobile, add a margin of 46px to accommodate with the admin bar
        if ((wp_is_mobile()) && is_user_logged_in())
        {
            $admin_bar_class = 'bc-mnc__mobile-logged-in';
        }
        //pass the design id here to update the fragments later via ajax (the function to remove item from cart needs this)
        error_log('wrapper is ' . $wrapper);
        if ($wrapper == 'li')
        {
            error_log("RELATIVE TO $relative_to_element and $relative_position");
            if (wp_is_mobile() && $relative_to_element !='' && $relative_position !='nothing') {

            	$returned_html  = sprintf(
		            '<li style="%10$s;" data-mobile-position="%7$s"  data-mobile-relative-element="%8$s" data-mobile-remove-origin="%9$s" class="bc-mnc %5$s %6$s">%1$s <div class="bc-mnc__cart-details bc-root %2$s %11$s" data-option-id="%3$s">%4$s %12$s</div></li>',
		            $cart_a_section,
//		            '',
		            $cart_list_style_class,
		            $design_option_id,
		            $cart_details_html,
		            $cart_float,
		            $admin_bar_class,
		            $relative_position,
		            $relative_to_element,
		            $remove_origin_element,
		            $extra_css,
                    $hide_cart_on_mouse_out_class,
                    $cart_link
                );

            	if ($remove_origin_element)  {
                    error_log('hidding element' . $relative_to_element);
                    $returned_html .= "<style>$relative_to_element {display: none; }</style>";
                }
            }
            else

                $returned_html  = sprintf('<li class="bc-mnc %5$s %6$s">%1$s <div class="bc-mnc__cart-details bc-root %2$s %7$s" data-option-id="%3$s">%4$s %8$s</div></li>',
                    $cart_a_section,
                    $cart_list_style_class,
                    $design_option_id,
                    $cart_details_html,
                    $cart_float,
                    $admin_bar_class,
                    $hide_cart_on_mouse_out_class,
                    $cart_link
                );
        } else if ($wrapper === false) //return the content, will be used to update the cart via ajax
        {
            $returned_html =  $cart_a_section;
        }
        else //use different wrapper than li, maybe div
        {
            error_log("RELATIVE TO $relative_to_element");
	        if (wp_is_mobile() && $relative_to_element !='' && $relative_position !='nothing') {

                $returned_html = sprintf(
                    '<div style="%10$s;" data-mobile-position="%7$s"  data-mobile-relative-element="%8$s" data-mobile-remove-origin="%9$s" class="bc-mnc %5$s %6$s">%1$s <div class="bc-mnc__cart-details bc-root %2$s %11$s" data-option-id="%3$s">%4$s %12$s</div></div>',
                    $cart_a_section,
                    $cart_list_style_class,
                    $design_option_id,
                    $cart_details_html,
                    $cart_float,
                    $admin_bar_class,
                    $relative_position,
                    $relative_to_element,
                    $remove_origin_element,
                    $extra_css,
                    $hide_cart_on_mouse_out_class,
                    $cart_link
                );

                if ($remove_origin_element)  {
                    error_log('hiding element' . $relative_to_element);
                    $returned_html .= "<style>$relative_to_element {display: none; }</style>";
                }
            } else {
                $returned_html  = sprintf('<div class="bc-mnc %5$s %6$s">%1$s <div class="bc-mnc__cart-details bc-root %2$s %7$s" data-option-id="%3$s">%4$s %8$s</div></div>',
                    $cart_a_section,
                    $cart_list_style_class,
                    $design_option_id,
                    $cart_details_html,
                    $cart_float,
                    $admin_bar_class,
                    $hide_cart_on_mouse_out_class,
                    $cart_link
                );
            }


        }

        error_log("Generating code OK ->>>>>>>>>>>> 12323");

        return $returned_html;

    }


    /**
     *
     * @return integer number of items in cart
     */
    public static  function generate_cart_count_circle(BC_Options $design_options)
    {
        $text_color = $design_options->get_string(Oname::ITEM_COUNT_CIRCLE_TEXT_COLOR, '#fff', false);
        $bg_color = $design_options->get_string(Oname::ITEM_COUNT_CIRCLE_BG_COLOR, '#ff6000', false);
        $width =  $design_options->get_int(Oname::ITEM_COUNT_CIRCLE_WIDTH, 16, false);
        $height = $design_options->get_int(Oname::ITEM_COUNT_CIRCLE_HEIGHT, 16, false);

        $font_size = $design_options->get_int(Oname::ITEM_COUNT_CIRCLE_FONT_SIZE, 12, false);
        //get the number of items in cart


        if (is_null(\WC()->cart))
            return '<!-- cart is null, output nothing -->';
        return sprintf('<div class="bc-mnc__cart-link--count-circle" style="color: %2$s; background: %3$s; width: %4$s; height: %5$s; line-height: %6$s; font-size: %7$s;">%1$s</div>', \WC()->cart->get_cart_contents_count(), $text_color, $bg_color, $width . 'px', $height . 'px', $height . 'px', $font_size . 'px');
    }

    /**
     * Generate cart content, number of items and the word items
     */
    public static function generate_cart_item_count()
    {
        if (is_null(\WC()->cart))
            return '<!-- cart is null, output nothing -->';

        return sprintf ( _n( '<div class="bc-mnc__cart-details--cart-items-count">%d item</div>', '<div class="bc-mnc__cart-details--cart-items-count">%d items</div>', \WC()->cart->get_cart_contents_count() , 'bc-menu-cart-woo'), \WC()->cart->get_cart_contents_count() );
    }

    public static function generate_cart_total()
    {
        $withTax = get_option( 'woocommerce_tax_display_shop' ) == 'incl';

        $total = \WC()->cart->get_cart_contents_total();
        error_log("Cart-->Totoot " . $total);
        if ($withTax)
            $total = $total +  floatval(\WC()->cart->get_taxes_total());

        return sprintf('<div class="bc-mnc__cart-details--cart-total__amount">%1$s</div>', wc_price($total));
    }

    /**
     * @param BC_Options $design_option
     * @return string the HTML string of the Cart icon ONLY
     */
    private static function generate_cart_icon_html(BC_Options $design_option)
    {
        $icon_type = $design_option->get_string(Oname::CART_ICON_TYPE, 'font_icon', false);

        $icon_font = $design_option->get_string(Oname::CART_ICON_FONT, 'icon-cart-01', false);

        $icon_font_size = $design_option->get_int(Oname::CART_ICON_FONT_SIZE, 24, false);


        $icon_width = $design_option->get_int(Oname::CART_ICON_WIDTH, 40, false);
        $icon_height = $design_option->get_int(Oname::CART_ICON_HEIGHT, 40, false);
        $icon_image = $design_option->get_string(Oname::CART_ICON_IMAGE);

        $icon_display = $design_option->get_bool(Oname::DISPLAY_CART_ICON, true);

        $icon_color = $design_option->get_string(Oname::CART_ICON_COLOR, '#000000', false);

        $html = '';



        if ($icon_type == 'font_icon')
        {
            $html = sprintf('<i style="width:%1$s; height: %2$s; font-size: %3$s; color: %5$s;" class="%4$s bc-menu-cart-icon"></i>', $icon_width . 'px', $icon_height . 'px', $icon_font_size . 'px', $icon_font, $icon_color);
        } else
        {
            if ($icon_image!= '')
            {
                $html = sprintf('<img src="%1$s" style="width:%2$s; height: %3$s;" />', $icon_image, $icon_width . 'px', $icon_height . 'px');
            }

        }
        $hidden = !$icon_display ? 'style="display:none;"' : '';

        return sprintf('<div class="bc-mnc__cart-link--cart-icon" %1$s>%2$s</div>', $hidden, $html);
    }

}
