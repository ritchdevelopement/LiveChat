<?php

    add_action('wp_head', function() {
        wp_register_style('livechatstyles', plugins_url('css/livechat.css', __FILE__), false);
        wp_enqueue_style('livechatstyles');
        wp_register_script( 'livechatjs', plugins_url('js/livechat.js', __FILE__), '', true );
        wp_localize_script( 'livechatjs', 'livechat_script', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'title' => get_the_title()
            )
        );
        wp_enqueue_script( 'livechatjs' );
        echo '<style>
        #lv-input::placeholder, #lv-input-inactive::placeholder {
            color: ' . livechat_option( "fontcolor" ) . ';
        }
        #lv-output .lv-visitor-message {
            background-color: ' . livechat_option( "backgroundcolor" ) . ';
            color: ' . livechat_option( "fontcolor" ) . ';
        }
        #lv-output .lv-employee-message, #lv-output-inactive .lv-employee-message {
            border: 2px solid ' . livechat_option( "backgroundcolor" ) . ';
        }
        #lv-container {
            border-left: 1px solid ' . livechat_option( "backgroundcolor" ) . ';
        }
        </style>';
    });

    add_action('wp_footer', function() {
        if( livechat_option( "chatactiv" ) == "on" ) {
            echo '<div id="lv-start" style="background-color:' . livechat_option( "backgroundcolor" ) . '; color:' . livechat_option( "fontcolor" ) . ';">Haben Sie eine Frage?</div>
            <div id="lv-container" style="background-color:' . livechat_option( "backgroundcolor" ) . '; color:' . livechat_option( "fontcolor" ) . ';">
                <div id="lv-header">
                    <div id="lv-chatuserpic">
                        <img src="' . get_avatar_url( get_user_id_by_display_name( livechat_option( "chatuser" ) ) ) . '">
                    </div>
                    <div id="lv-headtext">
                        <span>Sie sprechen mit<br><b>' . livechat_option( "chatuser" ) . '</b></span>
                    </div>
                    <div id="lv-close" style="color:' . livechat_option( "fontcolor" ) . ';">&#10007;</div>
                </div>';
            show_chat_if_same_user();
            echo '</div>';
        }
    });