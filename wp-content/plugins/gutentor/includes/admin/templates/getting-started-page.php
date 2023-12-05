<main class="gutentor-get-started-landing-page">
	<section class="gutentor-plugin-banner gutentor-get-started-header">
		<div class="container">
			<div class="gutentor-plugin-header">
				<h2 class="gutentor-plugin-header-title">
					<?php esc_html_e( 'Getting Started To Gutentor', 'gutentor' ); ?>
				</h2>
				<h4>
					<?php esc_html_e( 'WordPress Page Building Blocks', 'gutentor' ); ?>
				</h4>
				<a href="https://www.youtube.com/watch?v=bGMi7L78hVk" class="btn btn-primary gutentor-getting-started-watch-video">
					<?php esc_html_e( 'Intro Video', 'gutentor' ); ?>
					<span class="dashicons dashicons-controls-play"></span>
				</a>
				<a href="https://www.youtube.com/channel/UCkPtdikwcTsBhJknaa6r10w" class="btn btn-primary" target="_blank">
					<?php esc_html_e( 'Video Tutorials', 'gutentor' ); ?>
					<span class="dashicons dashicons-controls-play"></span>
				</a>
			</div>
		</div>
	</section>
	<article class="gutentor-get-started">
		<section>
			<div class="container">
				<div class="col col-image">
					<img src="<?php echo GUTENTOR_URL . 'assets/img/admin/page-post.png'; ?>">
				</div>
				<div class="col">
					<div class="gutentor-get-started-content">
						<h3><?php esc_html_e( '1. Add New Page/Post', 'gutentor' ); ?></h3>
						<p><?php esc_html_e( 'Here is the steps to add page/post in WordPress', 'gutentor' ); ?></p>
						<h4><?php esc_html_e( 'Adding Page', 'gutentor' ); ?></h4>
						<ul>
							<li><?php esc_html_e( 'Go to Pages => Add New', 'gutentor' ); ?></li>
							<li><?php esc_html_e( 'OR', 'gutentor' ); ?> <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=page' ) ); ?>" target="_blank"><?php esc_html_e( 'Click here', 'gutentor' ); ?></a></li>
						</ul>
						<h4><?php esc_html_e( 'Adding Post', 'gutentor' ); ?></h4>
						<ul>
							<li><?php esc_html_e( 'Go to Posts => Add New', 'gutentor' ); ?></li>
							<li><?php esc_html_e( 'OR', 'gutentor' ); ?> <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=post' ) ); ?>" target="_blank"><?php esc_html_e( 'Click here', 'gutentor' ); ?></a></li>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<section>
			<div class="container">
				<div class="col col-image">
					<img src="<?php echo GUTENTOR_URL . 'assets/img/admin/template-library.png'; ?>">
				</div>
				<div class="col">
					<div class="gutentor-get-started-content">
						<h3><?php esc_html_e( '2. Import Template Library or Add New Blocks', 'gutentor' ); ?></h3>
						<p><?php esc_html_e( 'From Edit screen of page/post, you can easily import Beautiful Templates', 'gutentor' ); ?></p>
						<h4><?php esc_html_e( 'Import template library', 'gutentor' ); ?></h4>
						<ul>
							<li>
								<?php esc_html_e( 'At the top of page/post edit Screen, click on “Template Library”', 'gutentor' ); ?>
								<ul>
									<li><strong><?php esc_html_e( 'Blocks: ', 'gutentor' ); ?></strong><?php esc_html_e( 'Search and Import Elegant Blocks', 'gutentor' ); ?></li>
									<li><strong><?php esc_html_e( 'Templates: ', 'gutentor' ); ?></strong><?php esc_html_e( 'Import Beautiful Stunning Templates', 'gutentor' ); ?></li>
									<li><strong><?php esc_html_e( 'File: ', 'gutentor' ); ?></strong><?php esc_html_e( 'Import Demo Data from JSON file', 'gutentor' ); ?></li>
								</ul>
							</li>
						</ul>
						<h4><?php esc_html_e( 'To add fresh block follow below steps', 'gutentor' ); ?></h4>
						<ul>
							<li><?php esc_html_e( 'At the top left corner of the page/post edit Screen, click on Sign', 'gutentor' ); ?><span class="dashicons dashicons-plus"></span></li>
							<li><?php esc_html_e( 'Type Block name in search bar, if you already know the block name.', 'gutentor' ); ?></li>
							<li><?php esc_html_e( 'OR Scroll down and navigate to Gutentor section and Add New Block.', 'gutentor' ); ?></li>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<section>
			<div class="container">
				<div class="col col-image">
					<img src="<?php echo GUTENTOR_URL . 'assets/img/admin/start-designing.png'; ?>" />
				</div>
				<div class="col">
					<div class="gutentor-get-started-content">
						<h3><?php esc_html_e( '3. Start Designing', 'gutentor' ); ?></h3>
						<p><?php echo sprintf( esc_html__( 'Gutentor blocks are divided into element,module,post,term and widget. Visit %1$s Dashboard: Gutentor=>Blocks %2$s indepth understanding of gutentor blocks classification.', 'gutentor' ), '<strong>', '</strong>' ); ?></p>
						<p><?php esc_html_e( 'After Adding a Block (element/module/post/term) in the editor, It is ready to Design', 'gutentor' ); ?></p>
						<ul>
							<li><strong><?php esc_html_e( 'General: ', 'gutentor' ); ?></strong><?php esc_html_e( 'It consists set of options to design blocks. e.g color, typography, margin, padding and so on.', 'gutentor' ); ?></li>
							<li><strong><?php esc_html_e( 'Advanced: ', 'gutentor' ); ?></strong><?php esc_html_e( 'It consists of set of Advanced Setting which effect whole Block Section.', 'gutentor' ); ?></li>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<section>
			<div class="container">
				<div class="col col-image">
					<img src="<?php echo GUTENTOR_URL . 'assets/img/admin/widget-designing.png'; ?>">
				</div>
				<div class="col">
					<div class="gutentor-get-started-content">
						<h3><?php esc_html_e( '4. Designing Widget', 'gutentor' ); ?></h3>
						<p><?php esc_html_e( 'After Adding a Block in the editor, It is ready to Design', 'gutentor' ); ?></p>
						<p><?php echo sprintf( esc_html__( 'At right sidebar of Edit screen, there are Document and Block Tabs, under %1$s Block Tab %2$s, you will find setting to respective block.', 'gutentor' ), '<strong>', '</strong>' ); ?></p>
						<ul>
							<li>
								<strong><?php esc_html_e( 'General', 'gutentor' ); ?></strong>
								<ul>
									<li><strong><?php esc_html_e( 'Block Title: ', 'gutentor' ); ?></strong><?php esc_html_e( 'Setting related to block title.', 'gutentor' ); ?></li>
									<li><strong><?php esc_html_e( 'Block Items: ', 'gutentor' ); ?></strong><?php esc_html_e( 'It is Main part of block setting which consist of 3 tabs like general, style and advanced.', 'gutentor' ); ?>
										<ul>
											<li><strong><?php esc_html_e( 'General: ', 'gutentor' ); ?></strong><?php esc_html_e( 'It consists functional setting like select, enable and disable the features of the block. ', 'gutentor' ); ?></li>
											<li><strong><?php esc_html_e( 'Style: ', 'gutentor' ); ?></strong><?php esc_html_e( 'Style tabs consists of different styling setting of the block item.', 'gutentor' ); ?></li>
											<li><strong><?php esc_html_e( 'Advanced: ', 'gutentor' ); ?></strong><?php esc_html_e( 'It consists of margin/padding and animation setting of block items.', 'gutentor' ); ?></li>
										</ul>
									</li>
								</ul>
							</li>
							<li><strong><?php esc_html_e( 'Advanced: ', 'gutentor' ); ?></strong><?php esc_html_e( 'It consists of set of Advanced Setting which effect whole Block Section.', 'gutentor' ); ?></li>
						</ul>
					</div>
				</div>
			</div>
		</section>
	</article>

	<footer class="gutentor-get-started-footer">
		<a href="https://www.gutentor.com/documentation/" target="_blank" class="btn btn-primary">
			<span class="dashicons dashicons-media-document"></span>
			<?php esc_html_e( 'Documentation', 'gutentor' ); ?>
		</a>
		<a href="https://www.demo.gutentor.com" target="_blank" class="btn btn-primary">
			<span class="dashicons dashicons-visibility"></span>
			<?php esc_html_e( 'Gutentor Demo', 'gutentor' ); ?>
		</a>
		<a href="https://templateberg.com/gutenberg-templates/" target="_blank" class="btn btn-primary">
			<span class="dashicons dashicons-screenoptions"></span>
			<?php esc_html_e( 'Gutenberg Templates', 'gutentor' ); ?>
		</a>
	</footer>
</main>
