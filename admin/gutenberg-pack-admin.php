<?php
/**
 * Gutenberg Pack Admin HTML.
 *
 * @package Gutenberg Pack
 */

?>
<div class="gutenberg-pack-menu-page-wrapper">
	<div id="gutenberg-pack-menu-page">
		<div class="gutenberg-pack-menu-page-header <?php echo esc_attr( implode( ' ', $gutenberg_pack_header_wrapper_class ) ); ?>">
			<div class="gutenberg-pack-container gutenberg-pack-flex">
				<div class="gutenberg-pack-title">
					<a href="<?php echo esc_url( $gutenberg_pack_visit_site_url ); ?>" target="_blank" rel="noopener" >
					<?php if ( $gutenberg_pack_icon ) { ?>
						<img src="<?php echo esc_url( GUTENBERG_PACK_URL . 'admin/assets/images/gutenberg_pack_logo.png' ); ?>" class="gutenberg-pack-header-icon" alt="<?php echo GUTENBERG_PACK_PLUGIN_NAME; ?> " >
						<?php
} else {
	echo '<h4>' . GUTENBERG_PACK_PLUGIN_NAME . '</h4>'; }
?>
					</a>
				</div>
				<div class="gutenberg-pack-top-links">
					<?php
						esc_attr_e( 'Extend your gutenberg editor', 'gutenberg-pack' );
					?>
				</div>
			</div>
		</div>

		<?php
		// Settings update message.
		if ( isset( $_REQUEST['message'] ) && ( 'saved' == $_REQUEST['message'] || 'saved_ext' == $_REQUEST['message'] ) ) {
			?>
				<div id="message" class="notice notice-success is-dismissive gutenberg-pack-notice"><p> <?php esc_html_e( 'Settings saved successfully.', 'gutenberg-pack' ); ?> </p></div>
			<?php
		}
		do_action( 'gutenberg_pack_render_admin_content' );
		?>
	</div>
</div>
