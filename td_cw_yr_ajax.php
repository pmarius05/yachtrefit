<?php

add_action( 'wp_ajax_nopriv_td_mod_subscription_register_yr', array('td_cw_yr_ajax', 'on_ajax_subscription_register_yr') );
add_action( 'wp_ajax_td_mod_subscription_register_yr',        array('td_cw_yr_ajax', 'on_ajax_subscription_register_yr') );


class td_cw_yr_ajax {

    static function on_ajax_subscription_register_yr() {

        //if registration is open from wp-admin/Settings,  then try to create a new user
        if( get_option('users_can_register') == 1 ) {

            $json_captcha_fail = json_encode(array('register', 0, __td('CAPTCHA verification failed!', TD_THEME_NAME)));
            $json_captcha_score_fail = json_encode(array('register', 0, __td('CAPTCHA user score failed. Please contact us!', TD_THEME_NAME)));

            // get the email address from ajax() call
            $register_email = '';
            if (!empty($_POST['email'])) {
                $register_email = $_POST['email'];
            }
            if (empty($register_email)) {
                die(json_encode(array('register', 0, __td('Email empty!', TD_THEME_NAME))));
            }

            // get user from ajax() call
            $register_user = '';
            if (!empty($_POST['user'])) {
                $register_user = $_POST['user'];
            }
            if (empty($register_user)) {
                die(json_encode(array('register', 0, __td('Username empty!', TD_THEME_NAME))));
            }

            $register_pass = '';
            if (!empty($_POST['pass'])) {
                $register_pass = $_POST['pass'];
            }
            if (empty($register_pass)) {
                die(json_encode(array('register', 0, __td('Pass empty!', TD_THEME_NAME))));
            }

            preg_match('/^(?=.{6,})(?=.*[a-z])(?=.*[A-Z])/', $register_pass, $output_array);
            if (!count($output_array)) {
                die(json_encode(array('register', 0, __td('Pass pattern incorrect!', TD_THEME_NAME))));
            }

            $register_retype_pass = '';
            if (!empty($_POST['retype_pass'])) {
                $register_retype_pass = $_POST['retype_pass'];
            }
            if (empty($register_retype_pass)) {
                die(json_encode(array('register', 0, __td('Retyped pass empty!', TD_THEME_NAME))));
            }

            if ( $register_pass !== $register_retype_pass) {
                die(json_encode(array('register', 0, __td('Retyped pass exactly!', TD_THEME_NAME))));
            }

            $register_role = get_option( 'default_role' );
            if( !empty( $_POST['role'] ) ) {
                $register_role = $_POST['role'];
            }

            $register_region = '';
            if ( !empty( $_POST['region'] ) ) {
                $register_region = $_POST['region'];
            }

            $register_phone = '';
            if ( !empty( $_POST['phone'] ) ) {
                $register_phone = $_POST['phone'];
            }

            $phone_code = '';
            if ( !empty( $_POST['phone_code'] ) ) {
                $phone_code = $_POST['phone_code'];
            }

            $phone_country = '';
            if ( !empty( $_POST['phone_country'] ) ) {
                $phone_country = $_POST['phone_country'];
            }

            $phone_intl = '';
            if ( !empty( $_POST['phone_intl'] ) ) {
                $phone_intl = $_POST['phone_intl'];
            }

            //check user existence before adding it
            $user_id = username_exists($register_user);
            if ( $user_id ) {
                die(json_encode(array('register', 0, __td('User already exists!', TD_THEME_NAME))));
            }

            if (email_exists($register_email)) {
                die(json_encode(array('register', 0, __td('Email already exists!', TD_THEME_NAME))));
            }
            //get recaptcha
            $register_captcha = '';
            if (!empty($_POST['captcha'])) {
                $register_captcha = $_POST['captcha'];
            }

            //recaptcha option from panel
            $show_captcha = td_util::get_option('tds_captcha');
            $captcha_domain = td_util::get_option('tds_captcha_url') !== '' ? 'www.recaptcha.net' : 'www.google.com';

            // userdata
            $userdata = array(
                'user_pass' => $register_pass,
                'user_login' => $register_user,
                'user_email' => $register_email,
                'role' => $register_role
            );

            // recaptcha is active
            if ($show_captcha == 'show' && $register_captcha != '') {

                //get google secret key from panel
                $captcha_secret_key = td_util::get_option('tds_captcha_secret_key');
                //alter captcha result=>score
                $captcha_score = td_util::get_option('tds_captcha_score');
                if ($captcha_score == ''){
                    $captcha_score = 0.5;
                }

                // for cloudflare
                if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                }
                //google recaptcha verify
                $post_data = http_build_query(
                    array(
                        'secret' => $captcha_secret_key,
                        'response' => $register_captcha,
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    )
                );
                $opts = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $post_data
                    )
                );
                $context = stream_context_create($opts);
                $response = file_get_contents('https://' . $captcha_domain . '/recaptcha/api/siteverify', false, $context);
                $result = json_decode($response);

                //die with error
                if ($result->success === false) {
                    die($json_captcha_fail);
                }

                //check captcha score result - default is 0.5
                if ($result->success === true && $result->score <= $captcha_score) {
                    die($json_captcha_score_fail);
                }

                //try to login if we get captcha success result
                if ( $result->success ) {

                    $user_id = wp_insert_user( $userdata );
                    if (is_wp_error($user_id)) {
                        die(json_encode(array('register', 0,__td('Your account could not be created.', TD_THEME_NAME))));
                    }

                    add_user_meta($user_id, 'tds_validate', [
                        'key' => td_global::td_generate_unique_id(),
                        'creation_time' => strtotime('now'),
                        'validation_time' => ''
                    ]);

                    //send email to $register_email
                    td_util::td_new_subscriber_user_notifications($user_id, 'both');

                    wp_signon( array(
                        'user_login'    => $register_user,
                        'user_password' => $register_pass,
                        'remember'      => true
                    ), false );

                    die(json_encode(array('register', 1,__td('Please check your email (inbox or spam folder) to validate your account.', TD_THEME_NAME))));
                } else {
                    die($json_captcha_fail);
                }

            } else {
                $user_id = wp_insert_user( $userdata );
                if (is_wp_error($user_id)) {
                    die(json_encode(array('register', 0,__td('Your account could not be created.', TD_THEME_NAME))));
                }

                add_user_meta($user_id, 'tds_validate', [
                    'key' => td_global::td_generate_unique_id(),
                    'creation_time' => strtotime('now'),
                    'validation_time' => ''
                ]);

                add_user_meta($user_id, 'tds_cwn_region', $register_region);

                $tds_cwn_phone = array(
                    'phone_number' => $register_phone,
                    'phone_codenumber' => $phone_code.$register_phone,
                    'phone_code' => $phone_code,
                    'phone_country' => $phone_country,
                    'phone_intl' => $phone_intl
                );
                add_user_meta($user_id, 'tds_cwn_phone', $tds_cwn_phone);

                //send email to $register_email
                td_util::td_new_subscriber_user_notifications($user_id, 'both');

                wp_signon( array(
                    'user_login'    => $register_user,
                    'user_password' => $register_pass,
                    'remember'      => true
                ), false );

                die(json_encode(array('register', 1,__td('Please check your email (inbox or spam folder) to validate your account.', TD_THEME_NAME))));
            }

        }//end if admin permits registration

    }

}