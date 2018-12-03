<?php

//Get user id by display name from user
function get_user_id_by_display_name( $display_name ) {
    global $wpdb;

    if ( ! $user = $wpdb->get_row( $wpdb->prepare(
        "SELECT `ID` FROM $wpdb->users WHERE `display_name` = %s", $display_name
    ) ) ) {
        return false;
    }

    return $user->ID;
}

//Get option name
function livechat_option( $name ) {
    $options = get_option( 'livechat_options' );
    if ( isset( $options[$name] ) ) { 
        return $options[$name]; 
    } else {
        return false;
    }
}

// Function to get the client ip address
function get_client_ip_env() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

//Show chat if same user
function show_chat_if_same_user() {
    $sameUser = DB_Helper::getHelper()->checkUserSame();
    
    if($sameUser == true) {
        echo '<div id="lv-body">
                <div id="lv-output" style="background-color:' . livechat_option( "fontcolor" ) . '; color:' . livechat_option( "backgroundcolor" ) . ';"></div>
            </div>
            <div id="lv-footer">
                <input id="lv-input" placeholder="Nachricht eingeben" style="background-color:' . livechat_option( "backgroundcolor" ).'; color:' . livechat_option( "fontcolor" ) . ';">
            </div>';
    } else {
        echo '<div id="lv-body">
                <div id="lv-output-inactive" style="background-color:' . livechat_option( "fontcolor" ) . '; color:' . livechat_option( "backgroundcolor" ) . ';">
                    <p class="lv-employee-message" style="text-align: center;"><strong>Im Moment ist der Mitarbeiter in einem Gespräch. Bitte probieren Sie es später nocheinmal.</strong></p>
                </div>
            </div>
            <div id="lv-footer">
                <input id="lv-input-inactive" placeholder="Nachricht eingeben" style="background-color:' . livechat_option( "backgroundcolor" ).'; color:' . livechat_option( "fontcolor" ) . '; cursor: not-allowed;" disabled>
            </div>';
    }
}