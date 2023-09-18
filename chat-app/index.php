<?php

if ( !function_exists( 'add_action' )) { exit; }

class ChatApp
{
    var $plugin_url = '';
    var $plugin_path = '';

    function __construct()
    {

        $this->plugin_url = plugins_url('', __FILE__); // path used for elements like images, css, etc which are available on end user
        $this->plugin_path = dirname(__FILE__); // used for internal (server side) files
//var_dump($this->plugin_path);

        add_action('init', array($this, 'chat_assets'));

        add_action( 'rest_api_init', array($this, 'register_chat_routes') );
//        add_action('wp_head', array($this, 'myplugin_ajaxurl'));

//        add_action('wp_ajax_save_chat_message', 'save_chat_message');
//        add_action('wp_ajax_nopriv_save_chat_message', 'save_chat_message');

    }

    function chat_assets()
    {
        wp_enqueue_script(
            'tdcwn-vue',
            //'https://cdnjs.cloudflare.com/ajax/libs/vue/3.3.4/vue.global.prod.min.js',
            'https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js',
            array(),
            true,
            true
        );

        wp_enqueue_script(
            'tdcwn-chat-app',
            $this->plugin_url . '/chat-app.js',
            array('jquery'),
            true,
            true
        );
    }

    function register_chat_routes()
    {
        register_rest_route('chatxxx/v1', 'message', array(
            'methods' => 'POST',
            'callback' => array($this, 'test'),
//            'permission_callback' => '__return_true'
        ));
    }

    function test($request_data)
    {
//        $text = $_SERVER['REQUEST_METHOD'];
//        return $text;

        $data = array();

        $parameters = $request_data->get_param();
        $name = $parameters['name'];

        if (isset($name)) {
            $data['status'] = 'OK';
            $data['received_data'] = array(
                'name' => $name
            );
            $data['message'] = 'Some random message';
        }else {
            $data['status'] = 'Failed';
            $data['message'] = 'Error message';
        }

        return $data;
    }

    function save_chat_message(WP_REST_Request $request) {
        global $wpdb;
        $messages_table = $wpdb->prefix . 'tdcwn_messages';

        $params = $request->get_json_params();
        $user = sanitize_text_field($params['user']);
        $message = samitize_textarea_field($params['message']);

        $wpdb->insert(
            $messages_table,
            array(
                'user' => $user,
                'message' => $message,
                'timestamp' => current_time('mysql')
            )
        );

        if ($wpdb->insert_id) {
            return new WP_REST_Response('Message saved', 200);
        }else {
            return new WP_Error('cant-save', 'message could not be saved', array('status' => 500));
        }

        return new WP_PEST_Response('Your message was saved', 200);
    }

//    function myplugin_ajaxurl()
//    {
//        echo '<script type="text/javascript">
//           var ajaxurl = "' . 'http://localhost' . admin_url('admin-ajax.php') . '";
//         </script>';
//    }

//    function save_chat_message()
//    {
//        global $wpdb;
//        $messages_table = $wpdb->prefix . 'tdcwn_messages';
//        $message_data = json_decode(stripslashes($_POST['message_data']), true);
//
//        error_log(print_r($_POST, true));
//
//        $wpdb->insert(
//            $messages_table,
//            array(
//                'user' => sanitize_text_field($message_data['user']),
//                'message' => sanitize_text_field($message_data['message']),
//                'timestamp' => current_time('mysql')
//            )
//        );
//
//        if ($wpdb->insert_id) {
//            wp_send_json_success();
//        }else {
//            wp_send_json_error();
//        }
//
////        wp_send_json_success('AJAX received');
//
//        die();
//    }
}

$chat_app = new ChatApp();