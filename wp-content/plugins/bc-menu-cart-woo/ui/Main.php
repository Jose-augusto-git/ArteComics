<?php
namespace BinaryCarpenter\BC_MNC;

use BinaryCarpenter\BC_MNC\Options_Form as Options_Form;
use BinaryCarpenter\BC_MNC\Options_Name as Oname;
use BinaryCarpenter\BC_MNC\Static_UI as Static_UI;

/**
 * Class Main
 * @package BinaryCarpenter\BC_MNC
 */
class Main {


	/**
	 * Build the options page.
	 */
	public static function ui() {
		$all_cart_designs =  BC_Options::get_all_options(Config::OPTION_NAME)->posts;
		//check if the option_id is passed in the URL (in case of edit)
		//get that value if available, otherwise, create a new one
		$action = isset($_GET['action']) ? $_GET['action'] : 'edit';
		$option_post_id = isset($_GET['option_id']) ? intval($_GET['option_id']) : 0;

		if (isset($_GET['active_tab']))
		{
			$active_tab = $_GET['active_tab'];

			?>
			<script>
                //this whole part is just to make the page goes to the right tab after reload. What a fucking pathetic poc
                (function($){
                    $(function(){
                        try {
                            var active_tab_id = '#' + <?php echo "'" . $active_tab . "'"; ?>;
                            console.log("active tab id is: ", active_tab_id);
                            var active_tab_content = $(`${active_tab_id}`);
                            var active_tab_li = $('[href="'+active_tab_id+'"]').closest('li');
                            //remove the active class from the si
                            active_tab_li.siblings('li').removeClass('bc-uk-active');
                            active_tab_content.siblings('li').removeClass('bc-uk-active');

                            active_tab_li.addClass('bc-uk-active');
                            active_tab_content.addClass('bc-uk-active');
                        } catch (e) {
                            console.log("got an exception", e );
                        }

                    });

                })(jQuery)

			</script>

		<?php }


		//if the action is to trash the page, then delete the option and redirect to a blank page
		if ($action == 'trash' && $option_post_id != 0)
		{
			wp_delete_post($option_post_id);
			exit(wp_redirect(admin_url('admin.php?page=bc_menu_bar_cart&active_tab=create-design-tab')));
		}


		$general_bc_ui = new Options_Form(Config::OPTION_NAME, $option_post_id);

		?>


		<div class="bc-root bc-doc">
			<div class="bc-uk-container">
				<h2><?php _e('Binary Carpenter Menu Cart Icon', 'bc-menu-cart-woo' ) ?></h2>



				<?php
				if (!Helpers::get_menu_array()) {
					?>
					<div class="bc-uk-alert-danger" style="width:400px; padding:10px;">
						<h3><?php _e('Important!', 'bc-menu-cart-woo'); ?></h3>
						<p>
							<?php _e('You don\'t have any menu yet. Please Go to <strong>Appearance -> Menus</strong> and create a menu to get started.', 'bc-menu-cart-woo'); ?>
						</p>

					</div>
				<?php } ?>
				<div class="bc-uk-flex" bc-uk-grid>

					<div class="bc-uk-width-1-1@m">
						<ul class="bc-uk-tab" data-uk-tab="{connect:'#bc-menu-cart-top-tab'}">
							<li>
								<a href="#welcome-tab"><?php _e('Welcome', 'bc-menu-cart-woo'); ?></a>
							</li>
							<li>
								<a href="#create-design-tab"><?php _e('Create new cart design', 'bc-menu-cart-woo'); ?></a>
							</li>
							<li>
								<a href="#link-design-to-menu-tab"><?php _e('Link design to menu', 'bc-menu-cart-woo'); ?></a>
							</li>

							<li>
								<a href="#theme-cart-icon-settings-tab"><?php _e('Theme\'s cart icon settings', 'bc-menu-cart-woo'); ?></a>
							</li>
						</ul>

						<ul class="bc-uk-switcher" id="bc-menu-cart-top-tab">
							<li class="bc-single-tab" id="welcome-tab">
                                <div class="bc-uk-container">
                                    <div class="bc-uk-flex bc-uk-grid">


                                    <div class="bc-uk-width-1-2">
                                        <h3><?php _e('Thanks for using our plugin', 'bc-menu-cart-woo'); ?></h3>
                                        <p><?php _e('We hope you enjoy it.', 'bc-menu-cart-woo'); ?></p>
                                        <p><?php _e('We made a list of tutorial <a href="https://www.youtube.com/playlist?list=PL6rw2AEN42Eq4M6bJABDJJI5OhrslZ6cM">here on YouTube</a>', 'bc-menu-cart-woo'); ?></p>
                                        <p><?php _e('If you need help, please <a href="https://tickets.binarycarpenter.com/open.php">contact us</a>', 'bc-menu-cart-woo'); ?></p>


                                        <?php
                                        //try to activate here if it's pro
                                        if (!Config::IS_FREE)
                                        {
                                            $activation_result = json_decode(Activation::activate(Config::KEY_CHECK_OPTION));

                                            Static_UI::notice($activation_result->message, $activation_result->status, false, true);
                                        } ?>

                                        <?php if (Config::IS_FREE): ?>
                                            <h3><?php _e('Like this plugin? The PRO version is much more awesome! <a target="_blank" href="https://www.binarycarpenter.com/app/bc-menu-cart-icon-plugin/?src=upgrade-h3-first-page">Check it out</a>', 'bc-menu-cart-woo') ?></h3>
                                        <?php endif; ?>
                                    </div>

                                    <div  class="bc-uk-width-1-2">
                                        <h3>Recommended plugins</h3>
                                        <div id="bc-recommended-products">

                                        </div>

                                    </div>

                                    </div>

                                </div>





							</li>

							<li class="bc-single-tab" id="create-design-tab">
								<div class="bc-uk-flex" bc-uk-grid>
									<div class="bc-uk-width-auto@m">
										<a href="<?php echo admin_url('admin.php?page=bc_menu_bar_cart&active_tab=create-design-tab'); ?>" class="bc-uk-button bc-uk-button-primary">Create new design</a>


										<?php
										Static_UI::heading(__('Designs you created', 'bc-menu-cart-woo'), 2);

										echo '<ul>';
										foreach ($all_cart_designs as $design)
										{
											echo sprintf('<li><a href="%1$s"><i class="icon-edit-01"></i></a> <a href="%2$s"><i class="icon-trash-01"></i></a>%3$s</li>', admin_url('admin.php?page=bc_menu_bar_cart&active_tab=create-design-tab&action=edit&option_id=' . $design->ID), admin_url('admin.php?page=bc_menu_bar_cart&active_tab=create-design-tab&action=trash&option_id=' . $design->ID), $design->post_title);
										}

										echo '</ul>';


										?>
									</div>
									<div class="bc-uk-width-2-3@m">
										<form class="bc-uk-form">
											<ul class="bc-uk-tab" data-uk-tab="{connect:'#bc-menu-cart-settings-tabs'}">
												<li>
													<a href="#general-tab"><?php _e('General settings', 'bc-menu-cart-woo'); ?></a>
												</li>

												<li>
													<a href="#design-tab"><?php _e('Design settings', 'bc-menu-cart-woo'); ?></a>
												</li>
											</ul>
											<ul class="bc-uk-switcher" id="bc-menu-cart-settings-tabs">

												<li id="general-tab">

													<?php

													if(isset($_GET['option_id']))
													{
														$general_bc_ui->card_section(__('Cart icon shortcode', 'bc-menu-cart-woo'), array(
                                                            sprintf('[bc_cart_icon id=%1$s]', intval($_GET['option_id']))
                                                        ));
													}


													$general_bc_ui->card_section(__('Design title', 'bc-menu-cart-woo'), array(
														$general_bc_ui->input_field(OName::TITLE, false, __('Design title, like post title, easy to remember', 'bc-menu-cart-woo'))

													));


													$general_bc_ui->card_section(__('Cart display', 'bc-menu-cart-woo'), array(
														$general_bc_ui->checkbox(OName::ALWAYS_DISPLAY, false, __('Always show the cart icon on the menu, even when the cart is empty', 'bc-menu-cart-woo'), true),
														$general_bc_ui->checkbox(OName::DISPLAY_CART_ICON, false, __('Display icon on cart', 'bc-menu-cart-woo'), true),
//                                                    $general_bc_ui->checkbox(OName::DISPLAY_ITEM_COUNT, false, 'Display item count'),
//                                                    $general_bc_ui->checkbox(OName::DISPLAY_CART_TOTAL, false, 'Display cart total')
													));



													$general_bc_ui->card_section(__('Cart layout', 'bc-menu-cart-woo'), array(
														$general_bc_ui->radio(OName::CART_LAYOUT, array(
															'0' => array('content' => plugins_url('../bundle/images/layout_01.jpg', __FILE__), 'disabled' => false),
															'1' => array('content' => plugins_url('../bundle/images/layout_02.jpg', __FILE__), 'disabled' => false),
															'2' => array('content' => plugins_url('../bundle/images/layout_03.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'3' => array('content' => plugins_url('../bundle/images/layout_04.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'4' => array('content' => plugins_url('../bundle/images/layout_05.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'5' => array('content' => plugins_url('../bundle/images/layout_06.jpg', __FILE__), 'disabled' => Config::IS_FREE),

														), 'row', 'image', __('Select cart layout on desktop', 'bc-menu-cart-woo'),  array(100, 0)),
														Config::IS_FREE ? Static_UI::notice(__('Some layouts are locked. <a href="https://www.binarycarpenter.com/app/bc-menu-cart-icon-plugin/?src=in-plugin-locked-cart">Click here to upgrade</a> to unlock all features', 'bc-menu-cart-woo'), 'info', false, false) : '',

														$general_bc_ui->radio(OName::CART_LAYOUT_MOBILE, array(
															'0' => array('content' => plugins_url('../bundle/images/layout_01.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'1' => array('content' => plugins_url('../bundle/images/layout_02.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'2' => array('content' => plugins_url('../bundle/images/layout_03.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'3' => array('content' => plugins_url('../bundle/images/layout_04.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'4' => array('content' => plugins_url('../bundle/images/layout_05.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'5' => array('content' => plugins_url('../bundle/images/layout_06.jpg', __FILE__), 'disabled' => Config::IS_FREE),

														), 'row', 'image', __('Select cart layout on mobile', 'bc-menu-cart-woo'),  array(100, 0)),
														Config::IS_FREE ? Static_UI::notice(__('Mobile layout is a pro version only. <a href="https://www.binarycarpenter.com/app/bc-menu-cart-icon-plugin/?src=in-plugin-mobile-layout-locked">Click here to upgrade</a> to unlock all features', 'bc-menu-cart-woo'), 'info', false, false) : '',
														$general_bc_ui->input_field(OName::MY_CART_TEXT, 'text', __('"My Cart" replacement', 'bc-menu-cart-woo'), false),
														$general_bc_ui->radio(OName::CART_FLOAT, array(
															'bc-mnc__float-left' => array('content' => __('Left', 'bc-menu-cart-woo'), 'disabled' => false),
															'bc-mnc__float-right' => array('content' => __('Right', 'bc-menu-cart-woo'), 'disabled' => false),
															'bc-mnc__float-none' => array('content' => __('Default', 'bc-menu-cart-woo'), 'disabled' => false),
														), 'row', 'text', __('Cart float', 'bc-menu-cart-woo'))

													));


													$general_bc_ui->setting_fields();

													$general_bc_ui->js_post_form();
													?>

												</li>

												<li id="design-tab">
													<?php
													$general_bc_ui->card_section(__('Cart icon design', 'bc-menu-cart-woo'), array(



														$general_bc_ui->radio(OName::CART_ICON_TYPE, array(
															'font_icon' => array('content' => __('Font icon', 'bc-menu-cart-woo'), 'disabled' => false),
															'image' => array('content' => __('Image', 'bc-menu-cart-woo'), 'disabled' => Config::IS_FREE)

														), 'row', 'text', __('Cart icon type', 'bc-menu-cart-woo')),

														Static_UI::flex_section(array(
															$general_bc_ui->radio(OName::CART_ICON_FONT, array(
																'icon-cart-01' => array('content' => 'icon-cart-01', 'disabled' => false),
//                                                            'icon-cart-02' => array('content' => 'icon-cart-02', 'disabled' => false),
//                                                            'icon-cart-03' => array('content' => 'icon-cart-03', 'disabled' => false),
																'icon-cart-04' => array('content' => 'icon-cart-04', 'disabled' => false),
																'icon-cart-05' => array('content' => 'icon-cart-05', 'disabled' => Config::IS_FREE),
																'icon-cart-06' => array('content' => 'icon-cart-06', 'disabled' => Config::IS_FREE),
																'icon-cart-07' => array('content' => 'icon-cart-07', 'disabled' => Config::IS_FREE),
																'icon-cart-08' => array('content' => 'icon-cart-08', 'disabled' => Config::IS_FREE),
																'icon-cart-09' => array('content' => 'icon-cart-09', 'disabled' => Config::IS_FREE),
																'icon-cart-10' => array('content' => 'icon-cart-10', 'disabled' => Config::IS_FREE),
																'icon-cart-11' => array('content' => 'icon-cart-11', 'disabled' => Config::IS_FREE),
																'icon-cart-12' => array('content' => 'icon-cart-12', 'disabled' => Config::IS_FREE),
																'icon-cart-13' => array('content' => 'icon-cart-13', 'disabled' => Config::IS_FREE),
																'icon-cart-14' => array('content' => 'icon-cart-14', 'disabled' => Config::IS_FREE),
																'icon-cart-15' => array('content' => 'icon-cart-15', 'disabled' => Config::IS_FREE),


															), 'row', 'icon_font', __('Select icon for your cart', 'bc-menu-cart-woo')),



															$general_bc_ui->image_picker(OName::CART_ICON_IMAGE, __('Select', 'bc-menu-cart-woo'),  __('Select cart image', 'bc-menu-cart-woo'), Config::IS_FREE)

														), 'bc-uk-flex-between'),
														Config::IS_FREE ? Static_UI::notice(__('Some icons are not available in the free version. <a href="https://www.binarycarpenter.com/app/bc-menu-cart-icon-plugin/?src=in-plugin-locked-cart-icons">Click here to upgrade</a> to unlock all features', 'bc-menu-cart-woo'), 'info', false, false) : '',


														Static_UI::flex_section(array(
															$general_bc_ui->input_field(OName::CART_ICON_WIDTH, 'number', __('Icon width(px)', 'bc-menu-cart-woo'), false, 100),
															$general_bc_ui->input_field(OName::CART_ICON_HEIGHT, 'number', __('Icon height(px)', 'bc-menu-cart-woo'), false, 100),
															$general_bc_ui->input_field(OName::CART_ICON_FONT_SIZE, 'number', __('Icon font size(px)', 'bc-menu-cart-woo'), false, 100),
														), 'bc-uk-flex-between'),


														Config::IS_FREE ? Static_UI::notice(__('Cart icon color  is available in the pro version only. <a href="https://www.binarycarpenter.com/app/bc-menu-cart-icon-plugin/?src=in-plugin-cart-color">Click here to upgrade</a> to unlock all features', 'bc-menu-cart-woo'), 'info', false
															, false): '',
														$general_bc_ui->input_field(OName::CART_ICON_COLOR, 'color', __('Cart icon color (in case you use font icon)', 'bc-menu-cart-woo'), Config::IS_FREE, 60)

													));


													$general_bc_ui->card_section(__('Cart count circle design', 'bc-menu-cart-woo'),
														array(

															Static_UI::flex_section(array(
																$general_bc_ui->input_field(OName::ITEM_COUNT_CIRCLE_BG_COLOR, 'color', __('Item count circle background color', 'bc-menu-cart-woo'), false, 60),
																$general_bc_ui->input_field(OName::ITEM_COUNT_CIRCLE_TEXT_COLOR, 'color', __('Item count circle text color', 'bc-menu-cart-woo'), false, 60),
															), 'bc-uk-flex-between'),
															Static_UI::flex_section(array(
																$general_bc_ui->input_field(OName::ITEM_COUNT_CIRCLE_WIDTH, 'number', __('Item count circle width', 'bc-menu-cart-woo'), false, 100),
																$general_bc_ui->input_field(OName::ITEM_COUNT_CIRCLE_HEIGHT, 'number', __('Item count circle height', 'bc-menu-cart-woo'), false, 100),
																$general_bc_ui->input_field(OName::ITEM_COUNT_CIRCLE_FONT_SIZE, 'number', __('Item count font size', 'bc-menu-cart-woo'), false, 100),
															), 'bc-uk-flex-between')
														)

													);

													$general_bc_ui->card_section(__('Single item in cart details colors', 'bc-menu-cart-woo'),
														array(

															Static_UI::flex_section(array(
																$general_bc_ui->input_field(OName::SINGLE_ITEM_TITLE_COLOR, 'color', __('Product title color', 'bc-menu-cart-woo'), false, 60),
																$general_bc_ui->input_field(OName::SINGLE_ITEM_TITLE_FONT_SIZE, 'number', __('Product title font size (px)', 'bc-menu-cart-woo'), false, 100),
															), 'bc-uk-flex-between'),
//															Static_UI::flex_section(array(
//																$general_bc_ui->input_field(OName::ITEM_COUNT_CIRCLE_WIDTH, 'number', __('Item count circle width', 'bc-menu-cart-woo'), false, 100),
//																$general_bc_ui->input_field(OName::ITEM_COUNT_CIRCLE_HEIGHT, 'number', __('Item count circle height', 'bc-menu-cart-woo'), false, 100),
//																$general_bc_ui->input_field(OName::ITEM_COUNT_CIRCLE_FONT_SIZE, 'number', __('Item count font size', 'bc-menu-cart-woo'), false, 100),
//															), 'bc-uk-flex-between')
														)

													);

													if (Config::IS_FREE)
													{
														Static_UI::notice(__('The section below is available in the pro version only. <a href="https://www.binarycarpenter.com/app/bc-menu-cart-icon-plugin/?src=in-plugin">Click here to upgrade</a> to unlock all features', 'bc-menu-cart-woo'), 'info');
													}

													$general_bc_ui->card_section(__('On cart item action behavior', 'bc-menu-cart-woo'), array(
														Static_UI::flex_section(array(
															$general_bc_ui->select(OName::ON_CART_ICON_CLICK, array(
																'go_to_cart' => __('Go to cart', 'bc-menu-cart-woo'),
																'do_nothing' => __('Do nothing', 'bc-menu-cart-woo'),
																'show_cart_list' => __('Show cart items list', 'bc-menu-cart-woo')

															), __('On cart click', 'bc-menu-cart-woo'), Config::IS_FREE, false),
															$general_bc_ui->select(OName::ON_CART_ICON_HOVER, array(
																'do_nothing' => __('Do nothing', 'bc-menu-cart-woo'),
																'show_cart_list' => __('Show cart items list', 'bc-menu-cart-woo')
															), __('On cart hover', 'bc-menu-cart-woo'), Config::IS_FREE, false)
														), 'bc-uk-flex-between'),

                                                        Static_UI::flex_section(array(
                                                            $general_bc_ui->checkbox(Oname::HIDE_CART_ON_CURSOR_OUT, Config::IS_FREE, __('Hide cart details on mouse out?', 'bc-menu-cart-woo'))
                                                        ))


													));



													/**

													This section adds style to the cart details.
													One style needs to have three things:
													1. define a value here
													2. define the style in cart-list-design.scss in src/css
													3. define the style's skeleton in BC_Menu_Cart_Display.php

													Without any of the three, new style does not work.
													 */
													$general_bc_ui->card_section(__('Cart items list design', 'bc-menu-cart-woo'), array(
														$general_bc_ui->radio(OName::CART_LIST_STYLE_CLASS, array(
															'bc-mnc__cart-details-style-1' => array('content' => plugins_url('../bundle/images/cart-details-design-01.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'bc-mnc__cart-details-style-2' => array('content' => plugins_url('../bundle/images/cart-details-design-02.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'bc-mnc__cart-details-style-3' => array('content' => plugins_url('../bundle/images/cart-details-design-03.jpg', __FILE__), 'disabled' => Config::IS_FREE),
															'bc-mnc__cart-details-style-4' => array('content' => plugins_url('../bundle/images/cart-details-design-04.gif', __FILE__), 'disabled' => Config::IS_FREE),



														), 'row', 'image', __('Select preset designs for the cart list', 'bc-menu-cart-woo'), array(70, 0)),

//														$general_bc_ui->checkbox(Oname::DISPLAY_PRICE_INCLUDING_TAX, false, 'Display items price including tax?')



														/**
														 * @TODO later version
														 */
//                                                    $general_bc_ui->select(OName::CART_LIST_DISPLAY_STYLE, array(
//                                                        'drop_down' => 'Drop down',
//                                                        'slide_from_right' => 'Slide from right',
//                                                        'slide_from_left' => 'Slide from left',
//                                                        'full_page' => 'Full page'
//                                                    ), 'Cart list display style', false, false)

													));


													$general_bc_ui->card_section(__('Cart list close button styling', 'bc-menu-cart-woo'), array(
														$general_bc_ui->radio(OName::CLOSE_CART_LIST_ICON, array(
															'icon-close-01' => array('content' => 'icon-close-01', 'disabled' => Config::IS_FREE),

															'icon-close-03' => array('content' => 'icon-close-03', 'disabled' => Config::IS_FREE),
															'icon-close-04' => array('content' => 'icon-close-04', 'disabled' => Config::IS_FREE),

															'icon-close-06' => array('content' => 'icon-close-06', 'disabled' => Config::IS_FREE),
															'icon-close-07' => array('content' => 'icon-close-07', 'disabled' => Config::IS_FREE),
															'icon-close-08' => array('content' => 'icon-close-08', 'disabled' => Config::IS_FREE),
															'icon-close-09' => array('content' => 'icon-close-09', 'disabled' => Config::IS_FREE),
															'icon-close-10' => array('content' => 'icon-close-10', 'disabled' => Config::IS_FREE),
															'icon-close-11' => array('content' => 'icon-close-11', 'disabled' => Config::IS_FREE),
															'icon-close-12' => array('content' => 'icon-close-12', 'disabled' => Config::IS_FREE),

														), 'row', 'icon_font', __('Select close cart list icon (display at the top right of the cart list)', 'bc-menu-cart-woo')),
														Static_UI::flex_section(array(
														    $general_bc_ui->input_field(OName::CLOSE_CART_LIST_ICON_FONT_SIZE, 'number', __('Icon\'s font size', 'bc-menu-cart-woo'), Config::IS_FREE),
															$general_bc_ui->input_field(OName::CLOSE_CART_LIST_ICON_COLOR, 'color',  __('Icon\'s color', 'bc-menu-cart-woo'), Config::IS_FREE, 60),
                                                            ), 'bc-uk-flex-between'),
													));

													$general_bc_ui->card_section(__('Product image styling', 'bc-menu-cart-woo'), array(
														$general_bc_ui->checkbox(OName::DISPLAY_PRODUCT_IMAGE, Config::IS_FREE, __('Display product image', 'bc-menu-cart-woo')),
														Static_UI::flex_section(array(
															$general_bc_ui->input_field(OName::PRODUCT_IMAGE_WIDTH, 'number', __('Product image width', 'bc-menu-cart-woo'), Config::IS_FREE),
															$general_bc_ui->input_field(OName::PRODUCT_IMAGE_HEIGHT, 'number', __('Product image height', 'bc-menu-cart-woo'), Config::IS_FREE),

														), 'bc-uk-flex-between')
													));

													$general_bc_ui->card_section(__('Product remove button styling', 'bc-menu-cart-woo'), array(

														$general_bc_ui->radio(OName::REMOVE_PRODUCT_ICON, array(
															'icon-close-01' => array('content' => 'icon-close-01', 'disabled' => Config::IS_FREE),

															'icon-close-03' => array('content' => 'icon-close-03', 'disabled' => Config::IS_FREE),
															'icon-close-04' => array('content' => 'icon-close-04', 'disabled' => Config::IS_FREE),

															'icon-close-06' => array('content' => 'icon-close-06', 'disabled' => Config::IS_FREE),
															'icon-close-07' => array('content' => 'icon-close-07', 'disabled' => Config::IS_FREE),
															'icon-close-08' => array('content' => 'icon-close-08', 'disabled' => Config::IS_FREE),
															'icon-close-09' => array('content' => 'icon-close-09', 'disabled' => Config::IS_FREE),
															'icon-close-10' => array('content' => 'icon-close-10', 'disabled' => Config::IS_FREE),
															'icon-close-11' => array('content' => 'icon-close-11', 'disabled' => Config::IS_FREE),
															'icon-close-12' => array('content' => 'icon-close-12', 'disabled' => Config::IS_FREE),

														), 'row', 'icon_font', __('Select remove product icon (display in front of every products in cart)', 'bc-menu-cart-woo')),


														Static_UI::flex_section(array(
															$general_bc_ui->input_field(OName::PRODUCT_REMOVE_BUTTON_FONT_SIZE, 'number', __('Product removal icon font size', 'bc-menu-cart-woo'), Config::IS_FREE),
															$general_bc_ui->input_field(OName::PRODUCT_REMOVE_BUTTON_COLOR, 'color',  __('Icon\'s color', 'bc-menu-cart-woo'), Config::IS_FREE, 60),
														), 'bc-uk-flex-between'),
													));


													$general_bc_ui->card_section(__('Product quantity change styling', 'bc-menu-cart-woo'), array(
														$general_bc_ui->checkbox(OName::DISPLAY_QUANTITY_CHANGE_BOX, Config::IS_FREE, __('Display quantity change option for every product in cart', 'bc-menu-cart-woo')),
														Static_UI::flex_section(array(
															$general_bc_ui->radio(OName::QUANTITY_CHANGE_BOX_INCREASE_ICON, array(
																'icon-up-01' => array('content' => 'icon-up-01', 'disabled' => false),
																'icon-plus-04' => array('content' => 'icon-plus-04', 'disabled' => Config::IS_FREE),
																'icon-plus-02' => array('content' => 'icon-plus-02', 'disabled' => Config::IS_FREE),
																'icon-plus-01' => array('content' => 'icon-plus-01', 'disabled' => Config::IS_FREE),

																'icon-right-01' => array('content' => 'icon-right-01', 'disabled' => Config::IS_FREE),
																'icon-right-02' => array('content' => 'icon-right-02', 'disabled' => Config::IS_FREE),

															) , 'row', 'icon_font', __('Icon for increase button', 'bc-menu-cart-woo')),

															$general_bc_ui->radio(OName::QUANTITY_CHANGE_BOX_DECREASE_ICON, array(
																'icon-down-01' => array('content' => 'icon-down-01', 'disabled' => false),
																'icon-minus-01' => array('content' => 'icon-minus-01', 'disabled' => Config::IS_FREE),
																'icon-minus-02' => array('content' => 'icon-minus-02', 'disabled' => Config::IS_FREE),
																'icon-minus-03' => array('content' => 'icon-minus-03', 'disabled' => Config::IS_FREE),

																'icon-left-03' => array('content' => 'icon-left-03', 'disabled' => Config::IS_FREE),
																'icon-left-01' => array('content' => 'icon-left-01', 'disabled' => Config::IS_FREE),

															) , 'row', 'icon_font', __('Icon for increase button', 'bc-menu-cart-woo'))

														), 'bc-uk-flex-between'),

                                                        Static_UI::flex_section(array(
	                                                        $general_bc_ui->input_field(OName::QUANTITY_CHANGE_BOX_ICON_COLOR, 'color', __('Increase, decrease icons color', 'bc-menu-cart-woo'), Config::IS_FREE, 60),
	                                                        $general_bc_ui->input_field(OName::QUANTITY_CHANGE_BOX_QUANTITY_COLOR, 'color', __('Item\'s quantity text color', 'bc-menu-cart-woo'), Config::IS_FREE, 60)
                                                        ), 'bc-uk-flex-between')
													));

													$general_bc_ui->card_section(__('Move the cart icon on mobile', 'bc-menu-cart-woo'), array(
                                                        Static_UI::flex_section(array(

                                                            $general_bc_ui->radio(OName::DESIGN_MOBILE_POSITION, array(
                                                                'nothing' => array('content' => __('Do nothing', 'bc-menu-cart-woo'), 'disabled' => false),
                                                                'left' => array('content' => __('Left', 'bc-menu-cart-woo'), 'disabled' => false),
                                                                'right' => array('content' => __('Right', 'bc-menu-cart-woo'), 'disabled' => false),
                                                                'inside_replace' => array('content' => __('Inside replace', 'bc-menu-cart-woo'), 'disabled' => Config::IS_FREE),
                                                                'inside_no_replace' => array('content' => __('Inside don\'t replace', 'bc-menu-cart-woo'), 'disabled' => Config::IS_FREE),

                                                            ), 'column', 'text', __('Position against an element', 'bc-menu-cart-woo')),
                                                        ), 'bc-uk-flex-between'),

                                                        Static_UI::flex_section(array(
                                                            $general_bc_ui->input_field(OName::DESIGN_MOBILE_POSITION_RELATIVE_TO_ELEMENT, 'text',   __('Element selector (the element that the cart icon is positioned against)', 'bc-menu-cart-woo'), Config::IS_FREE),
                                                            $general_bc_ui->checkbox(OName::DESIGN_MOBILE_REMOVE_ORIGIN_ELEMENT, Config::IS_FREE,   __('Remove the original cart', 'bc-menu-cart-woo') ),
                                                        ), 'bc-uk-flex-between'),

                                                        Static_UI::flex_section(array(

                                                            $general_bc_ui->radio(OName::DESIGN_MOBILE_POSITION, array(
//                                                                'float_top_right' => array('content' => __('Float top right', 'bc-menu-cart-woo'), 'disabled' => false),
//                                                                'float_top_left' => array('content' => __('Float top left', 'bc-menu-cart-woo'), 'disabled' => false),
                                                                'float_bottom_right' => array('content' => __('Float bottom right', 'bc-menu-cart-woo'), 'disabled' => false),
                                                                'float_bottom_left' => array('content' => __('Float bottom left', 'bc-menu-cart-woo'), 'disabled' => false),

                                                            ), 'column', 'text', __('Float on screen', 'bc-menu-cart-woo')),
                                                        ), 'bc-uk-flex-between'),



                                                        $general_bc_ui->textarea(OName::DESIGN_MOBILE_EXTRA_STYLE, __('enter extra CSS rules here (property: value;), if any', 'bc-menu-cart-woo'), Config::IS_FREE)
													));

													$general_bc_ui->card_section(__('Other cart list settings', 'bc-menu-cart-woo'), array(
														Static_UI::flex_section(array(
															$general_bc_ui->input_field(OName::CART_LIST_HEADER_TEXT, 'text', __('Cart list header text (currently is "Your cart")', 'bc-menu-cart-woo'), Config::IS_FREE),
															$general_bc_ui->input_field(OName::CART_LIST_SUBTOTAL_TEXT, 'text', __('Cart list subtotal text (default is "Subtotal")', 'bc-menu-cart-woo'), Config::IS_FREE),
														), 'bc-uk-flex-between'),


														$general_bc_ui->checkbox(OName::DISPLAY_GO_TO_CART_BUTTON, Config::IS_FREE, __('Display go to cart button', 'bc-menu-cart-woo')),
														Static_UI::flex_section(array(

															$general_bc_ui->input_field(OName::GO_TO_CART_BUTTON_BG_COLOR, 'color',  __('Go to cart button background color', 'bc-menu-cart-woo'), Config::IS_FREE, 60),
															$general_bc_ui->input_field(OName::GO_TO_CART_BUTTON_TEXT, 'text',  __('Go to cart button text', 'bc-menu-cart-woo'), Config::IS_FREE),

														), 'bc-uk-flex-between'),


														$general_bc_ui->checkbox(OName::DISPLAY_GO_TO_CHECKOUT_BUTTON, Config::IS_FREE, __('Display go to checkout button'), 'bc-menu-cart-woo'),
														Static_UI::flex_section(array(

															$general_bc_ui->input_field(OName::GO_TO_CHECKOUT_BUTTON_BG_COLOR, 'color', __('Go to checkout button background color', 'bc-menu-cart-woo'), Config::IS_FREE, 60),
															$general_bc_ui->input_field(OName::GO_TO_CHECKOUT_BUTTON_TEXT, 'text',  __('Go to cart button text', 'bc-menu-cart-woo'), Config::IS_FREE),
														), 'bc-uk-flex-between')
													));




													//output a hidden field to instruct the script to reload or redirect the page or not
													//if the option_id get parameter is not set, that means the user
													//is creating the option, not editing it, thus, it is necessary to redirect
													//after save
													if (!isset($_GET['option_id']))
													{
														$general_bc_ui->raw_hidden(OName::REDIRECT_URL, admin_url('admin.php?page=bc_menu_bar_cart&action=edit&option_id=' . $general_bc_ui->get_option_post_id()));
													}
													?>

												</li>

											</ul>
											<?php $general_bc_ui->submit_button(__('Save settings', 'bc-menu-cart-woo')); ?>
										</form>
									</div>
								</div>
							</li>

							<li class="bc-single-tab" id="link-design-to-menu-tab">

								<h3>Link menu to design</h3>
								<p>Please select the design for your menus (These are the menu you created in Appearance->Menu section)</p>
								<form class="bc-uk-form">
									<?php
									$link_menu_options = BC_Options::get_all_options('bc_menu_cart_linked_menu');

									if (count($all_cart_designs) == 0)
									{
										echo __('You have not created any design. Go create one', 'bc-menu-cart-woo');
										return;
									}

									$design_radios = array();

									foreach ($all_cart_designs as $design)
									{
										$design_radios[$design->ID] = array(
											'content' => $design->post_title,
											'disabled' => false
										);
									}

									//add a blank radio to disable the linking
                                    $design_radios[0] = array(
                                        'content' => 'Unlink',
                                        'disabled' => false
                                    );


									if ($link_menu_options->have_posts())
										$link_menu_ui = new Options_Form(Oname::MENU_CART_LINK_MENUS, $link_menu_options->next_post()->ID);
									else
										$link_menu_ui = new Options_Form(Oname::MENU_CART_LINK_MENUS, 0);




									foreach (Helpers::get_menu_array() as $menu)
									{
										echo $link_menu_ui->radio(urldecode_deep($menu['slug']), $design_radios, 'row', 'text', $menu['name']);
										echo '<hr />';
									}


									?>

									<?php



									$link_menu_ui->setting_fields();
									$link_menu_ui->js_post_form();
									$link_menu_ui->submit_button(__('Save links', 'bc-menu-cart-woo'));

									?>


								</form>
							</li>

							<li class="bc-single-tab" id="theme-cart-icon-settings-tab">
								<form class="bc-uk-form">
									<?php
									$theme_cart_option = BC_Options::get_all_options('bc_menu_cart_theme_cart_icon');


									if ($theme_cart_option->have_posts())
										$theme_cart_ui = new Options_Form('bc_menu_cart_theme_cart_icon', $theme_cart_option->next_post()->ID);
									else
										$theme_cart_ui = new Options_Form('bc_menu_cart_theme_cart_icon', 0);


									$theme_cart_ui->card_section(__('Theme\'s cart icon settings', 'bc-menu-cart-woo'), array(
									        Static_UI::label('', 'Here you can configure the display of your theme\'s default cart icon', false),
										    $theme_cart_ui->checkbox(OName::HIDE_THEME_CART, false, __('Hide theme cart icon?', 'bc-menu-cart-woo')),
										    $theme_cart_ui->input_field(OName::THEME_CART_CSS_SELECTOR, 'text', __('Theme\'s cart CSS selector', 'bc-menu-cart-woo'), false)
									));



									$theme_cart_ui->setting_fields();
									$theme_cart_ui->js_post_form();
									$theme_cart_ui->submit_button(__('Save settings', 'bc-menu-cart-woo'));

									?>

								</form>


							</li>


						</ul>

					</div>
				</div>



			</div> <!-- End container -->
		</div>
		<?php
	}


}