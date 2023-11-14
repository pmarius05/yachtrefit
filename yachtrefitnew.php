<?php

/**
 *
 * Plugin Name: tagDiv YachtRefit
 * Plugin URI: #
 * Description: Custom options
 * Author: tagDiv
 * Author URI: https://www.tagdiv.com
 * Version: 0.3.6
 *
 */

if ( !function_exists( 'add_action' )) { exit; }

define( 'TDCW_YR_VER', '1' );
define( 'TDCW_YR_URL', plugin_dir_url( __FILE__ ) );
define( 'TDCW_YR_PATH', dirname( __FILE__ ) );

define( 'ADD_JOB_PAGE', '906');
define( 'JOBS_POSTED_PAGE', '904' );
define ( 'NOTIFICATIONS_PAGE', '900' );

//PLAN_1
define( 'PLAN_1', '2' );
define( 'JOB_CATS_PLAN_1', '3');

//PLAN_2
define( 'PLAN_2', '3' );
define( 'JOB_CATS_PLAN_2', '5');


require_once ('includes/tdcwnUtil.php');
//require_once ('database/index.php');
//require_once ('chat-app/index.php');
require_once ('shortcodes/config.php');
require_once ('td-contractors-categories-ajax.php');
require_once ('td_cw_yr_ajax.php');

class YachtRefit {

    var $notifications_table;
    var $plugin_url = '';

    public function __construct()
    {
        $this->notifications_table = 'tdcwn_notifications';
        $this->plugin_url = plugins_url('', __FILE__);

        add_action( 'init', array($this, 'add_client_role') );
//        add_action( 'template_redirect', array($this, 'manage_access_to_client_dashboard') );
        add_action( 'wp_enqueue_scripts', array( $this, 'define_ajax_url' ) );
        add_action('wp_enqueue_scripts', array($this, 'tdcwn_load_assets') );
//        //////add_action( 'template_redirect', array($this, 'manage_access_to_client_dashboard') );
//        add_action( 'template_redirect', array($this, 'manage_user_pages_access') );
        add_action( 'save_post_job', array( $this, 'register_notification' ), 10, 3 );
        add_action( 'wp_ajax_tdcwn_send_message', array( $this, 'tdcwn_send_message' ) );
    }

    public function define_ajax_url()
    {
        wp_register_script( 'my-script',  $this->plugin_url . '/shortcodes/assets/js/script.js', array('jquery'), null, true );
        wp_localize_script('my-script', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        do_action('before_my_script_enqueue');
        wp_enqueue_script('my-script');
    }

    public function tdcwn_send_message() {

        if (isset($_POST)) {

            $message = $_POST['message'];
            //            $current_user_id = $_POST['current_user_id'];
            $current_user_id = get_current_user_id();
            $other_user = $_POST['other_user'];
            $chat_id = $_POST['chat_id'];
            $file_to_upload  = $_POST['image'];



//            if (!empty($file_to_upload)) {
//                $upload_overrides = array('test_form' => false);
                $movefile = wp_handle_upload($file_to_upload);

//                if ($movefile && !isset($movefile['error'])) {
//                    wp_send_json_success(array('message' => 'File uploaded successfully!', 'file' => $movefile));
//                } else {
//                    wp_send_json_error(array('message' => $movefile['error']));
//                }
//            }

            global $wpdb;
            $wpdb->query(
                "
                INSERT INTO `wp_tdcwn_messages` 
                    (`id`, `chat_id`, `user_from`, `user_to`, `message`, `timestamp`) 
                    VALUES 
                    (NULL, '$chat_id', '$current_user_id', '$other_user', '$message', current_timestamp());
                "
            );
            //            global $wp;
            //            $url = home_url($wp->request);
            //            echo("<script>location.href = '".$url."'</script>");
        }

//        echo json_encode(array('success' => true));
        echo json_encode($file_to_upload);
        wp_die();
    }

    public function add_client_role() {
        add_role(
            'td_client_role',
            'YachtRefit Client',
            array(
                'read'          => true, //Can read
                'create_users'  => true
            )
        );

        add_role(
            'td_contractor_role',
            'YachtRefit Contractor',
            array(
                'read'          => true,
                'create_users'  => true,
            )
        );

        add_role(
            'td_team_member',
            __('Team member', 'TD-YRN'),
            array(
                'read' => true,
                'create_users' => false
            )
        );
    }

    public function manage_access_to_client_dashboard()
    {
       global $post;

       if ( $post->ID == '847' && current_user_can( 'td_client_role', 'administrator' ) ) {
           wp_redirect( home_url() );
           exit();
       }
    }

    //Add job page
    public function manage_user_pages_access()
    {
        global $post;
        global $tdcwnUtil;

        $current_user_id = get_current_user_id();
        $the_team = $tdcwnUtil->get_the_team($current_user_id);
        $account_type = $tdcwnUtil->get_account_type($current_user_id);

        if ( $post->ID == ADD_JOB_PAGE )
        {
            if (
                isset($post) && 'client' != $account_type
                || isset($post) && 'administrator' != $account_type
            ) {
                wp_redirect(home_url());
                exit;
            }
        }

//        if ( isset($post) && 'client' != $account_type && $post->ID == ADD_JOB_PAGE ) {
//
//        }
    }

    function register_notification ( $post_id, $post, $update )
    {
//        $post_id = $post_id;
//        $update = $update;

        global $wpdb;

//        var_dump('new post');
        // Don't run on autosaves
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        // Don't run on revisions
        if ($post->post_status === 'inherit') {
            return;
        }

        if( !$update && $post->post_type === 'job' ) {


            $current_user_id = get_current_user_id();
//            $post = serialize($post);

            $job_terms = wp_get_post_terms($post_id);
            $job_terms = serialize($job_terms);
            $terms = wp_get_post_terms($post_id, 'job');

//            $job_terms = get_terms_by( 'term_id', $post_id, 'job' );

            $notification_title = 'New job opportunity: '.$post->post_title;

            $notifications_table = $wpdb->prefix . $this->notifications_table;


            $insert = $wpdb->query(
                "
                    INSERT INTO $notifications_table
                        (`id`, `message`, `job_id`, `client_id`, `job_category`, `created_at`)
                        VALUES (NULL, '$notification_title', $post_id, $current_user_id, 'Job', current_timestamp());
                "
            );

        }
    }

    public function tdcwn_load_assets() {
        wp_enqueue_style('tdcwn_yr_main', $this->plugin_url.'/assets/css/tdcwn_yr_main.css', '', '', 'all');
    }

}

$yachtrefit = new YachtRefit();