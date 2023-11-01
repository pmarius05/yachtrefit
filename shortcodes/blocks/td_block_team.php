<?php

class td_block_team extends td_block {

    public function get_custom_css() {
        // $unique_block_class - the unique class that is on the block. use this to target the specific instance via css
        $unique_block_class = ((td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax()) ? 'tdc-row .' : '') . $this->block_uid;

        $compiled_css = '';

        $raw_css =
            "<style>
                  
            </style>";


        $td_css_res_compiler = new td_css_res_compiler( $raw_css );
        $td_css_res_compiler->load_settings( __CLASS__ . '::cssMedia', $this->get_all_atts() );

        $compiled_css .= $td_css_res_compiler->compile_css();
        return $compiled_css;
    }

    static function cssMedia( $res_ctx ) {

    }

    /**
     * Disable loop block features. This block does not use a loop and it doesn't need to run a query.
     */
    function __construct() {
        parent::disable_loop_block_features();
    }


    function render( $atts, $content = null )
    {
        parent::render($atts); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        // flag to check if we are in composer
        $is_composer = false;
        if (td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax()) {
            $is_composer = true;
        }

        if (isset($_POST['register_team_member'])) {

            $errors = array();

//            if ( !isset($_POST['name']) || wp_verify_nonce( $_POST['name'], 'action' ) ) {
//                die('Security check failed');
//            }

            $username = sanitize_user($_POST['username']);
            if (empty($username) || username_exists($username)) {
                $errors['username'] = __('The username is empty or already exists.', 'TD-YRN');
            }

            $password = $_POST['password'];
            if (empty($password)) {
                $errors['password'] = __('The password field is empty.', 'TD-YRN');
            }

            $email = sanitize_email($_POST['email']);
            if (!is_email($email) || email_exists($email)) {
                $errors[] = __('The email field is empty or the email address is associated with a different account.', 'TD-YRN');
            }

            $firstname = sanitize_text_field($_POST['firstname']);
            $lastname = sanitize_text_field($_POST['lastname']);

            if (!current_user_can('create_users')) {
                $errors['permission'] = __('You do not have the permission to create users', 'TD-YRN');
            }

            if (empty($errors)) {

                $userdata = array(
                    'user_login' => $username,
                    'user_pass' => $password,
                    'user_email' => $email,
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'role' => 'td_team_member'
                );

                $new_user_id = wp_insert_user($userdata);

                if (is_wp_error($new_user_id)) {
                    echo $create_user_error = $new_user_id->get_error_message();
                } else {

                    //
                    add_user_meta( $new_user_id, 'tdcwn_team', get_current_user_id() );

                    $team_members = get_user_meta(get_current_user_id(), 'tdcwn_team', false);
                    if (empty($team_members)) {
                        add_user_meta(get_current_user_id(), 'tdcwn_team', array($new_user_id));
                    } else {
                        // Make sure that $team_members is a single-dimension array
                        if (!is_array($team_members[0])) {
                            $team_members = array($team_members);
                        }
                        // Append the new user ID to the array
                        $team_members[0][] = $new_user_id;

                        // Update the user meta with the single-dimension array
                        update_user_meta(get_current_user_id(), 'tdcwn_team', $team_members[0]);

                    }
                }
            }

        }


        if (current_user_can('create_users')) {
            $team_members = get_user_meta(get_current_user_id(), 'tdcwn_team', false);

//            echo '<pre>';
//            print_r($team_members);
//            echo '</pre>';
        }


        $buffy = ''; //output buffer
        $buffy .= '<div class="' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';

        //get the block css
        $buffy .= $this->get_block_css();

        //get the js for this block
        $buffy .= $this->get_block_js();

//        $buffy .= 'This is Galda!';

        if (isset($errors) and !empty($errors)) {
            foreach ($errors as $key => $value) {
                $buffy .= '<p>' . $value . '</p>';
            }
        }

        if (current_user_can('create_users')) {

            $buffy .= '<details>';
            $buffy .= '<summary>Add team member</summary>';
            $buffy .= '<div class="content">';
            $buffy .= '<span class="tdcwn-tiny margin-down-20">* fields are required</span>';
            $buffy .= '<form action="" method="POST" class="tdcwn_form">';
    //                $buffy .= wp_nonce_field('action', 'name');

            $buffy .= '<label>Username<span>*</span></label>';
            $buffy .= '<input type="text" name="username" />';

            $buffy .= '<label>Password<span>*</span></label>';
            $buffy .= '<input type="text" name="password" />';

            $buffy .= '<label>Email<span>*</span></label>';
            $buffy .= '<input type="email" name="email" />';

            $buffy .= '<label>First name</label>';
            $buffy .= '<input type="text" name="firstname" />';

            $buffy .= '<label>Last name</label>';
            $buffy .= '<input type="text" name="lastname" />';


            $buffy .= '<input type="submit" name="register_team_member" />';
            $buffy .= '</form>';
            $buffy .= '</div>';
            $buffy .= '</details>';
        }

        $buffy .= '<h5>Team members</h5>';

        if (isset($team_members)) {

            $buffy .= '<div class="team-members">';
            foreach ($team_members[0] as $member) {

                $member_data = get_userdata($member);
//                echo '<pre>';
//                print_r($member_data);
//                echo '</pre>';
//                echo $member_data->data->display_name;

                $buffy .= '<details>';
                $buffy .= '<summary class="team-member">';
                $buffy .= '<span class="">'.$member_data->data->user_nicename.'</span>';
                $buffy .= '<span class="name">'.$member_data->data->display_name.'</span>';
                $buffy .= '</summary>';

                $buffy .= '<div class="content">';
                    $buffy .= '<div class="details">';
                        $buffy .= '<span>Email: '.$member_data->data->user_email.'</span>';
//                        $buffy .= '<span>Name: '.$member_data->data->display_name.'</span>';
                    $buffy .= '</div>';
                $buffy .= '</div>';

                $buffy .= '</details>';
            }
            $buffy .= '</div>';

        }




        $buffy .= '</div>';

        return $buffy;
    }

}