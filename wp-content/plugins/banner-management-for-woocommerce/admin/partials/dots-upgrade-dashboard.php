<?php
/**
 * Handles free plugin user dashboard
 * 
 * @package Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @since   3.9.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
global $wcbm_fs;
?>
	<div class="wbm-section-left">
		<div class="dotstore-upgrade-dashboard">
			<div class="premium-benefits-section">
				<h2><?php esc_html_e( 'Go Premium to Increase Profitability', 'banner-management-for-woocommerce' ); ?></h2>
				<p><?php esc_html_e( 'Three Benefits for Upgrading to Premium', 'banner-management-for-woocommerce' ); ?></p>
				<div class="premium-features-boxes">
					<div class="feature-box">
						<span><?php esc_html_e('01', 'banner-management-for-woocommerce'); ?></span>
						<h3><?php esc_html_e('Flexible Banner Creation', 'banner-management-for-woocommerce'); ?></h3>
						<p><?php esc_html_e('Effortlessly creates and customizes engaging banners for maximum impact and increased conversion on your WooCommerce store.', 'banner-management-for-woocommerce'); ?></p>
					</div>
					<div class="feature-box">
						<span><?php esc_html_e('02', 'banner-management-for-woocommerce'); ?></span>
						<h3><?php esc_html_e('Enhance the Visual Appeal', 'banner-management-for-woocommerce'); ?></h3>
						<p><?php esc_html_e('Create eye-catching banners with discount offers for your shop, landing page, and product page to take your visual presentation to new heights.', 'banner-management-for-woocommerce'); ?></p>
					</div>
					<div class="feature-box">
						<span><?php esc_html_e('03', 'banner-management-for-woocommerce'); ?></span>
						<h3><?php esc_html_e('Increased Conversion Rates', 'banner-management-for-woocommerce'); ?></h3>
						<p><?php esc_html_e('Drive growth and increase revenue with strategic offer banners. Maximize conversions and profitability using targeted, engaging banners.', 'banner-management-for-woocommerce'); ?></p>
					</div>
				</div>
			</div>
			<div class="premium-benefits-section unlock-premium-features">
				<p><span><?php esc_html_e( 'Unlock Premium Features', 'banner-management-for-woocommerce' ); ?></span></p>
				<div class="premium-features-boxes">
					<div class="feature-box">
						<h3><?php esc_html_e('Banner For Any Page', 'banner-management-for-woocommerce'); ?></h3>
						<span><i class="fa fa-pager"></i></span>
						<p><?php esc_html_e('Enable banner or slider placement on any page throughout your WooCommerce store, including landing page, about, contact, and more.', 'banner-management-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'banner-management-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WCBM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-one-img.png'); ?>" alt="<?php echo esc_attr('Banner For Any Page', 'banner-management-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Easily add eye-catching banners to any page of your website, from the shop to the thank you page, product pages, and more.', 'banner-management-for-woocommerce'); ?></p>
												<p><?php esc_html_e('Increase engagement and conversions by strategically placing visually appealing banners that deliver targeted messages.', 'banner-management-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Effortlessly resize, position, and align banners with a few clicks for the perfect placement and impactful promotion of special offers, new products, and announcements.', 'banner-management-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Auto-Schedule Slider', 'banner-management-for-woocommerce'); ?></h3>
						<span><i class="fa fa-calendar-days"></i></span>
						<p><?php esc_html_e('Activate your banner slider to align with your offer duration and automatically deactivate it once the offer ends by utilizing the end date option.', 'banner-management-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'banner-management-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WCBM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-two-img.png'); ?>" alt="<?php echo esc_attr('Auto-Schedule Slider', 'banner-management-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Simplify your banner management process with the Auto-Schedule Slider feature.', 'banner-management-for-woocommerce'); ?></p>
												<p><?php esc_html_e('Automatically schedule and display banners at predefined times, ensuring timely promotions without manual intervention.', 'banner-management-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Save time and effort by setting up your banner campaigns in advance and letting the Auto-Schedule Slider handle the display.', 'banner-management-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Call to Action', 'banner-management-for-woocommerce'); ?></h3>
						<span><i class="fa fa-up-right-from-square"></i></span>
						<p><?php esc_html_e('Persuade shoppers to take action with a compelling and customizable call-to-action button on your banner, complete with a custom link.', 'banner-management-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'banner-management-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WCBM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-three-img.png'); ?>" alt="<?php echo esc_attr('Call to Action', 'banner-management-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Drive action and conversions with our powerful Call to Action (CTA) feature.', 'banner-management-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Engage your website visitors and guide them towards desired actions, such as making a purchase, signing up for a newsletter, or contacting your business.', 'banner-management-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Banner Positioning', 'banner-management-for-woocommerce'); ?></h3>
						<span><i class="fa fa-location-crosshairs"></i></span>
						<p><?php esc_html_e('Take control of your banner\'s position on its corresponding webpage, strategically placing it to stand out and capture shopper attention effectively.', 'banner-management-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'banner-management-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WCBM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-four-img.png'); ?>" alt="<?php echo esc_attr('Banner Positioning', 'banner-management-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Achieve precise and strategic banner placement on your website with our Banner Positioning feature.', 'banner-management-for-woocommerce'); ?></p>
												<p><?php esc_html_e('Seamlessly control where banners appear, ensuring maximum visibility and impact for your promotional messages.', 'banner-management-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Customize the position of your banners on different pages, such as the top header or after title.', 'banner-management-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Random Banner Slider', 'banner-management-for-woocommerce'); ?></h3>
						<span><i class="fa fa-shuffle"></i></span>
						<p><?php esc_html_e('Display one or multiple banners anywhere you choose, with randomized rotation. Maximize engagement and deliver diverse messages, capturing attention effectively.', 'banner-management-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'banner-management-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WCBM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-five-img.png'); ?>" alt="<?php echo esc_attr('Random Banner Slider', 'banner-management-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Maximize the effectiveness of your promotional efforts by leveraging the power of random banners.', 'banner-management-for-woocommerce'); ?></p>
												<p><?php esc_html_e('Enjoy the flexibility of random banner rotation, ensuring that each visit to your website presents visitors with a fresh and unique banner.', 'banner-management-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Engage your audience with a variety of messages, promotions, and visuals, keeping them interested and enticing them to explore further.', 'banner-management-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Multi Platform Compatibility', 'banner-management-for-woocommerce'); ?></h3>
						<span><i class="fa fa-desktop"></i></span>
						<p><?php esc_html_e('Display your banner or carousel slider flawlessly on all devices, including desktops, tablets, and mobile devices, with automatic responsiveness.', 'banner-management-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'banner-management-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WCBM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-six-img.png'); ?>" alt="<?php echo esc_attr('Multi Platform Compatibility', 'banner-management-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Ensure seamless functionality across various platforms with our Multi Platform Compatibility feature.', 'banner-management-for-woocommerce'); ?></p>
												<p><?php esc_html_e('Our plugin is designed to work effortlessly on different operating systems, browsers, and devices, providing a consistent experience for all users.', 'banner-management-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Reach a wider audience by supporting popular platforms such as Windows, macOS, iOS, Android, and major web browsers including Chrome, Firefox, Safari, and Edge.', 'banner-management-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Layout Preset', 'banner-management-for-woocommerce'); ?></h3>
						<span><i class="fa fa-person-chalkboard"></i></span>
						<p><?php esc_html_e('It allows you to align the product/category listing layout with your website layout, such as slider, grid, or block.', 'banner-management-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'banner-management-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WCBM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-seven-img.png'); ?>" alt="<?php echo esc_attr('Layout Preset', 'banner-management-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Customize the visual presentation of your banners effortlessly with our Layout Preset feature.', 'banner-management-for-woocommerce'); ?></p>
												<p><?php esc_html_e('Choose from a variety of layout options, including slider, grid, and block, to showcase your slider in a visually appealing and organized manner.', 'banner-management-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Opt for a slider layout to create an engaging slideshow of banners that catch the attention of your website visitors.', 'banner-management-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Say What You Want', 'banner-management-for-woocommerce'); ?></h3>
						<span><i class="fa fa-circle-info"></i></span>
						<p><?php esc_html_e('Craft persuasive category text on your banner to deliver a compelling message that drives customer action and boosts sales.', 'banner-management-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'banner-management-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WCBM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-eight-img.png'); ?>" alt="<?php echo esc_attr('Say What You Want', 'banner-management-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Say exactly what you want with our Banner Details feature for category banners, allowing you to communicate your message effectively.', 'banner-management-for-woocommerce'); ?></p>
												<p><?php esc_html_e('Provide clear and concise information, enticing visitors with compelling text that drives engagement and action.', 'banner-management-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Use the Banner Details feature to communicate special offers, discounts, product features, or any other key information you want to convey.', 'banner-management-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
					<div class="feature-box">
						<h3><?php esc_html_e('Quick Preview', 'banner-management-for-woocommerce'); ?></h3>
						<span><i class="fa fa-eye"></i></span>
						<p><?php esc_html_e('Save time and effort with instant previewing of custom changes, allowing you to make adjustments without the need to save repeatedly confidently.', 'banner-management-for-woocommerce'); ?></p>
						<div class="feature-explanation-popup-main">
							<div class="feature-explanation-popup-outer">
								<div class="feature-explanation-popup-inner">
									<div class="feature-explanation-popup">
										<span class="dashicons dashicons-no-alt popup-close-btn" title="<?php esc_attr_e('Close', 'banner-management-for-woocommerce'); ?>"></span>
										<div class="popup-body-content">
											<div class="feature-image">
												<img src="<?php echo esc_url(WCBM_PRO_PLUGIN_URL . 'admin/images/pro-features-img/feature-box-nine-img.png'); ?>" alt="<?php echo esc_attr('Quick Preview', 'banner-management-for-woocommerce'); ?>">
											</div>
											<div class="feature-content">
												<p><?php esc_html_e('Quickly preview banners or sliders before making them live on the website.', 'banner-management-for-woocommerce'); ?></p>
												<p><?php esc_html_e('This feature provides a convenient way to review and ensure the visual appearance, content, and functionality of banners or sliders without extensive testing or publishing them publicly.', 'banner-management-for-woocommerce'); ?></p>
												<ul>
													<li><?php esc_html_e('Preview your category or product slider settings before making them live and save valuable time and effort.', 'banner-management-for-woocommerce'); ?></li>
												</ul>
											</div>
										</div>
									</div>		
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="upgrade-to-premium-btn">
				<a href="<?php echo esc_url('https://bit.ly/3PiNOJB') ?>" target="_blank" class="button button-primary"><?php esc_html_e('Upgrade to Premium', 'banner-management-for-woocommerce'); ?><svg id="Group_52548" data-name="Group 52548" xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 27.263 24.368"><path id="Path_199491" data-name="Path 199491" d="M333.833,428.628a1.091,1.091,0,0,1-1.092,1.092H316.758a1.092,1.092,0,1,1,0-2.183h15.984a1.091,1.091,0,0,1,1.091,1.092Z" transform="translate(-311.117 -405.352)" fill="#fff"></path><path id="Path_199492" data-name="Path 199492" d="M312.276,284.423h0a1.089,1.089,0,0,0-1.213-.056l-6.684,4.047-4.341-7.668a1.093,1.093,0,0,0-1.9,0l-4.341,7.668-6.684-4.047a1.091,1.091,0,0,0-1.623,1.2l3.366,13.365a1.091,1.091,0,0,0,1.058.825h18.349a1.09,1.09,0,0,0,1.058-.825l3.365-13.365A1.088,1.088,0,0,0,312.276,284.423Zm-4.864,13.151H290.764l-2.509-9.964,5.373,3.253a1.092,1.092,0,0,0,1.515-.4l3.944-6.969,3.945,6.968a1.092,1.092,0,0,0,1.515.4l5.373-3.253Z" transform="translate(-285.455 -280.192)" fill="#fff"></path></svg></a>
			</div>
		</div>
	</div>
	</div>
</div>
</div>
</div>
<?php 
