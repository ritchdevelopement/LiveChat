<?php

function livechat_settings_init() {
	register_setting( 'livechat_o', 'livechat_options' );

	add_settings_section(
		'livechat_section_design',
		null,
		'livechat_design_form',
		'livechat_o'
	);

	add_settings_section(
		'livechat_section_options',
		null,
		'livechat_options_form',
		'livechat_o'
	);

	add_settings_section(
		'livechat_section_communication',
		null,
		'livechat_communication_form',
		'livechat_c'
	);	
}
add_action( 'admin_init', 'livechat_settings_init' );
 
function livechat_design_form( $args ) {
	$options = get_option('livechat_options');
	?>
	<h2><?php esc_html_e( 'LiveChat Design', 'livechat_o' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Hintergrundfarbe</th>
				<td>
					<input id="backgroundcolor" type="text" name="livechat_options[backgroundcolor]" value="<?php echo $options['backgroundcolor']; ?>" class="color-field" data-default-color="#000000"/>
					<p class="description"><?php esc_html_e( 'Wähle eine Hintergrundfarbe für den Chat aus', 'livechat_o' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">Schriftfarbe</th>
				<td>
					<input id="fontcolor" type="text" name="livechat_options[fontcolor]" value="<?php echo $options['fontcolor']; ?>" class="color-field" data-default-color="#000000"/>
					<p class="description"><?php esc_html_e( 'Wähle eine Schriftfarbe für den Chat aus', 'livechat_o' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

function livechat_options_form ( $args ) {
	$options = get_option('livechat_options');
	?>
	<h2><?php esc_html_e( 'LiveChat Optionen', 'livechat_o' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Aktiv</th>
				<td>
					<input id="chatactiv" name="livechat_options[chatactiv]" type="checkbox"<?php checked( isset( $options['chatactiv'] ) ); ?>>
					<p class="description">LiveChat aktivieren</p>
				</td>
			</tr>
			<tr>
				<th scope="row">Chatprofil</th>
				<td>
					<select id="chatuser" name="livechat_options[chatuser]" value="<?php echo $options['chatuser']; ?>">
					<?php
					$chatusers = get_users();
					foreach ( $chatusers as $user ) {
						echo '<option value="' . esc_html( $user->display_name ) . '"' .  selected( $options['chatuser'], $user->display_name, '' )  . '>' . esc_html( $user->display_name ) . '</option>';
					}
					?>
					</select>
					<img name="livechat_options[chatuserpic]" src="<?php esc_html_e( get_avatar_url( get_user_id_by_display_name($options['chatuser']) ) ); ?>" style="vertical-align: top; width: 50px;">
					<p class="description"><?php esc_html_e( 'Wählen Sie ihr Chatprofil aus (Öffentlicher Name des Benutzers)', 'livechat_o' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row">Datenbankoptionen</th>
				<td>
					<input id="db-clear" class="button button-primary" value="Chatverlauf leeren" type="button">
					<p class="description">ACHTUNG: Damit leeren Sie die Datenbank</p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

function livechat_communication_form( $args ) {
	?>
	<h2><?php esc_html_e( 'LiveChat Kommunikation', 'livechat_c' ); ?></h2>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">Besucher online</th>
				<td>
					<div id="vistitoronline"></div>
				</td>
			</tr>
			<tr>
				<th scope="row">Chatverlauf</th>
				<td>
					<div id="chat-history"></div>
				</td>
			</tr>
			<tr>
				<th scrope="row">Chatnachricht</th>
				<td>
					<input id="message-input" class="regular-text" placeholder="Nachricht eingeben" type="text">
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

add_action( 'admin_enqueue_scripts', function ( $hook_suffix ) {
		$screen = get_current_screen();
		if ( $screen->id == 'livechat_v1/admin/admin-view') {
			wp_enqueue_style( 'admin-livechat-styles', plugins_url('css/admin-styles.css', __FILE__), array());
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'elc-color-picker', plugins_url('js/colorpicker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
			wp_register_script( 'livechatjs', plugins_url('js/admin-script.js', __FILE__), '', true );
			wp_enqueue_script( 'livechatjs' );
			wp_localize_script( 'livechatjs', 'livechat_script', array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'title' => get_the_title()
				)
			);
		}
	} 
);

function livechat_options_page() {
    add_menu_page(
        'LiveChat',
        'LiveChat',
        'manage_options',
        plugin_dir_path( __FILE__ ) . 'admin-view.php',
		null,
        'dashicons-admin-comments',
        80
    );
}
add_action('admin_menu', 'livechat_options_page');
