<?php
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'livechat_messages', 'livechat_message', __( 'Settings Saved', 'livechat' ), 'updated' );
	}

	settings_errors( 'livechat_messages' );
?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
		<?php
			settings_fields( 'livechat_o' );
			do_settings_sections( 'livechat_o' );
			submit_button( 'Save Settings' );
		?>
		</form>
		<hr>
		<?php 
			settings_fields( 'livechat_c' );
			do_settings_sections( 'livechat_c' );
		?>
		
	</div>