<div class="gutentor-plugin-landing-page">
	<section class="gutentor-plugin-banner">
			<div class="gutentor-plugin-header">
				<h2 class="gutentor-plugin-header-title"><?php esc_html_e( 'Gutentor - WordPress Page Building Blocks', 'gutentor' ); ?></h2>
				<p><?php esc_html_e( 'Advanced yet easy drag & drop WordPress block page builder. Create masterpiece, pixel perfect websites using modern WordPress way. Work with any theme, create any design.', 'gutentor' ); ?></p>
				<a href="https://www.gutentor.com/documentation/" target="_blank" class="btn btn-primary"><span class="dashicons dashicons-media-document"></span><?php echo esc_html__( 'Documentation', 'gutentor' ); ?></a>
				<a href="https://www.demo.gutentor.com" target="_blank" class="btn btn-primary"><span class="dashicons dashicons-visibility"></span><?php echo esc_html__( 'Gutentor Demo', 'gutentor' ); ?></a>
			</div>
	</section>

	<div class="gutentor-plugin-list">
		<div class="gutentor-tab">
			<div class="gutentor-container">
				<div class="gutentor-row">
					<div class="gutentor-tab-list">
						<span id="gutentor-admin-element-tab" class="tablist-item tablist-item-active"><?php esc_html_e( 'Elements', 'gutentor' ); ?></span>
						<span id="gutentor-admin-module-tab" class="tablist-item"><?php esc_html_e( 'Modules', 'gutentor' ); ?></span>
						<span id="gutentor-admin-post-tab" class="tablist-item"><?php esc_html_e( 'Posts', 'gutentor' ); ?></span>
						<span id="gutentor-admin-term-tab" class="tablist-item"><?php esc_html_e( 'Terms', 'gutentor' ); ?></span>
						<span id="gutentor-admin-widget-tab" class="tablist-item"><?php esc_html_e( 'Widgets', 'gutentor' ); ?></span>
					</div>
				</div>
			</div> 
		</div>
		<div class="gutentor-tab-wrapper">
			<section class="gutentor-tab-content gutentor-admin-element-content gutentor-active ">
				<div class="gutentor-container">
					<div class="gutentor-row">
						<?php
						$contents = Gutentor_Admin::elements();
						foreach ( $contents as $block_id => $content ) :
							?>
						<div class="col">
							<div class="plugin-box">
								<h3><?php echo $content['title']; ?></h3>
								<p>
									<?php echo $content['description']; ?>
								</p>
								<div class="onoffswitch">
									<?php
									$status = Gutentor_Admin::is_block_active( $block_id );
									$cls    = $status ? 'enabled' : 'disabled';
									?>
									<input
											name="onoffswitch"
											id="myonoffswitch3"
											type="checkbox"
											data-action="<?php echo esc_attr( $block_id ); ?>"
											class="onoffswitch-checkbox  <?php echo esc_attr( $cls ); ?>"
										<?php checked( $status, true ); ?>
									>
									<label class="onoffswitch-label" for="myonoffswitch">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
			<section class="gutentor-tab-content gutentor-admin-module-content">
				<div class="gutentor-container">
					<div class="gutentor-row">
						<?php
						$contents = Gutentor_Admin::modules();
						foreach ( $contents as $block_id => $content ) :
							?>
						<div class="col">
							<div class="plugin-box">
								<h3><?php echo $content['title']; ?></h3>
								<p>
									<?php echo $content['description']; ?>
								</p>
								<div class="onoffswitch">
									<?php
									$status = Gutentor_Admin::is_block_active( $block_id );
									$cls    = $status ? 'enabled' : 'disabled';
									?>
									<input
											name="onoffswitch"
											id="myonoffswitch3"
											type="checkbox"
											data-action="<?php echo esc_attr( $block_id ); ?>"
											class="onoffswitch-checkbox  <?php echo esc_attr( $cls ); ?>"
										<?php checked( $status, true ); ?>
									>
									<label class="onoffswitch-label" for="myonoffswitch">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
			<section class="gutentor-tab-content gutentor-admin-post-content">
				<div class="gutentor-container">
					<div class="gutentor-row">
						<?php
						$contents = Gutentor_Admin::posts();
						foreach ( $contents as $block_id => $content ) :
							?>
						<div class="col">
							<div class="plugin-box">
								<h3><?php echo $content['title']; ?></h3>
								<p>
									<?php echo $content['description']; ?>
								</p>
								<div class="onoffswitch">
									<?php
									$status = Gutentor_Admin::is_block_active( $block_id );
									$cls    = $status ? 'enabled' : 'disabled';
									?>
									<input
											name="onoffswitch"
											id="myonoffswitch3"
											type="checkbox"
											data-action="<?php echo esc_attr( $block_id ); ?>"
											class="onoffswitch-checkbox  <?php echo esc_attr( $cls ); ?>"
										<?php checked( $status, true ); ?>
									>
									<label class="onoffswitch-label" for="myonoffswitch">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
            <section class="gutentor-tab-content gutentor-admin-term-content">
                <div class="gutentor-container">
                    <div class="gutentor-row">
                        <?php
                        $contents = Gutentor_Admin::terms();
                        foreach ( $contents as $block_id => $content ) :
                            ?>
                            <div class="col">
                                <div class="plugin-box">
                                    <h3><?php echo $content['title']; ?></h3>
                                    <p>
                                        <?php echo $content['description']; ?>
                                    </p>
                                    <div class="onoffswitch">
                                        <?php
                                        $status = Gutentor_Admin::is_block_active( $block_id );
                                        $cls    = $status ? 'enabled' : 'disabled';
                                        ?>
                                        <input
                                                name="onoffswitch"
                                                id="myonoffswitch3"
                                                type="checkbox"
                                                data-action="<?php echo esc_attr( $block_id ); ?>"
                                                class="onoffswitch-checkbox  <?php echo esc_attr( $cls ); ?>"
                                            <?php checked( $status, true ); ?>
                                        >
                                        <label class="onoffswitch-label" for="myonoffswitch">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
			<section class="gutentor-tab-content gutentor-admin-widget-content">
				<div class="gutentor-container">
					<div class="gutentor-row">
						<?php
						$contents = Gutentor_Admin::content();
						foreach ( $contents as $block_id => $content ) :
							?>
						<div class="col">
							<div class="plugin-box">
								<h3><?php echo $content['title']; ?></h3>
								<p>
									<?php echo $content['description']; ?>
								</p>
								<div class="onoffswitch">
									<?php
									$status = Gutentor_Admin::is_block_active( $block_id );
									$cls    = $status ? 'enabled' : 'disabled';
									?>
									<input
											name="onoffswitch"
											id="myonoffswitch3"
											type="checkbox"
											data-action="<?php echo esc_attr( $block_id ); ?>"
											class="onoffswitch-checkbox  <?php echo esc_attr( $cls ); ?>"
										<?php checked( $status, true ); ?>
									>
									<label class="onoffswitch-label" for="myonoffswitch">
										<span class="onoffswitch-inner"></span>
										<span class="onoffswitch-switch"></span>
									</label>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
