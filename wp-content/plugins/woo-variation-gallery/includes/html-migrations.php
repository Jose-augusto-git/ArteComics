<?php
defined( 'ABSPATH' ) or die( 'Keep Quit' );
?>

<h2>
	<?php esc_html_e( 'Gallery Migration', 'woo-variation-gallery' ) ?>
</h2>

<div id="migration_settings">
	<p><?php esc_html_e( 'Migrate gallery from other plugins. Migration process will run on background.', 'woo-variation-gallery' ) ?></p>
</div>


<table class="wc_status_table wc_status_table--tools widefat">
	<tbody class="tools">

	<?php

	$migration_list = (array) apply_filters( 'woo_variation_gallery_migration_list', array() );

	foreach ( $migration_list as $action => $tool ): ?>

		<tr class="<?php echo esc_attr( $action ) ?>">
			<th>
				<strong class="name"><?php echo esc_html( $tool['name'] ) ?></strong>
				<p class="description"><?php echo wp_kses_post( $tool['desc'] ) ?></p>
			</th>
			<td class="run-tool">
				<a onclick='return confirm("<?php printf( esc_html__( 'Are you sure you want to \n%s?', 'woo-variation-gallery' ), str_ireplace( '&quot;', '\"', esc_attr( $tool['name'] ) ) ) ?>");' href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=wc-status&tab=tools&action=' . $action ), 'debug_action' ) ); ?>" class="button button-large <?php echo esc_attr( $action ); ?>"><?php echo esc_html( $tool['button'] ); ?></a>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>

