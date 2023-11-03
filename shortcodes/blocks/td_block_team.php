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

//            if (!current_user_can('create_users')) {
//                $errors['permission'] = __('You do not have the permission to create users', 'TD-YRN');
//            }

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

        global $tdcwnUtil;


        if ( 'td_client_role' == $tdcwnUtil->get_current_user_role() || 'td_contractor_role' == $tdcwnUtil->get_current_user_role() ) {

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

        if  ('td_team_member' == $this->get_current_user_role() ) {
            $team_leader = $this->get_team_leader();
            $leader_data = get_userdata($team_leader);
            $buffy.= '<h5>Team leader</h5>';
            $buffy .= '<details>';
            $buffy .= '<summary class="team-member">';
            $buffy .= '<span class="">'.$leader_data->data->user_nicename.'</span>';
            $buffy .= '<span class="name">'.$leader_data->data->display_name.'</span>';



            $buffy .= '</details>';
        }

        $buffy .= '<h5>Team members</h5>';

        global $tdcwnUtil;
        $the_team = $tdcwnUtil->get_the_team(get_current_user_id());
        $the_team = $tdcwnUtil->flatten_array($the_team);
echo '<pre>';
print_r($the_team);
echo '</pre>';

        if ( 'td_team_member' == $this->get_current_user_role() ) {
            $team_members = $this->get_team_members_by_member_id();

        }

        if (!empty($the_team)) {


            $buffy .= '<div class="team-members">';
            foreach ($the_team as $member) {

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

    public function get_user_role_by_id( $user_id )
    {
        // Get user data by user ID
        $user = get_userdata( $user_id );

        // Check if user exists and has roles set
        if ( $user && !empty( $user->roles ) && is_array( $user->roles ) ) {
            // Users can have multiple roles, so return all roles
            return $user->roles;
        } else {
            // No role found for the user, or user does not exist, returning null
            return null;
        }

        return null;
    }

    public function get_current_user_role() {
        // Ensure the WordPress user functions are loaded
        if ( ! function_exists( 'wp_get_current_user' ) ) {
            include( ABSPATH . "wp-includes/pluggable.php" );
        }

        // Get the current user
        $current_user = wp_get_current_user();

        // Return the role of the current user
        if ( !empty( $current_user->roles ) && is_array( $current_user->roles ) ) {
            // Users can have multiple roles, so return the first one
            return $current_user->roles[0];
        } else {
            // No role found for the user, returning null
            return null;
        }
    }

    public function get_team_leader($member_id = NULL)
    {
        $member_id = (isset($member_id)) ? $member_id : get_current_user_id();

        return ( get_user_meta( $member_id, 'tdcwn_team', true ) ) ;
    }

    public function get_team_members_by_team_leader ($team_leader_id)
    {
        define('META_KEY', 'tdcwn_team');
        $team_leader_id = ( isset($team_leader_id) ? $team_leader_id : get_current_user_id() );

        $user_role = $this->get_user_role_by_id($team_leader_id);
        //if ( ('td_client_role' == $user_role) || ('td_contractor_role' == $user_role) ) :
            $the_team = get_user_meta($team_leader_id, META_KEY);
            return $the_team;
        //endif;
        return false;
    }

    public function get_team_members_by_member_id($member_id = NULL)
    {
        $member_id = ( isset($member_id) ? $member_id : get_current_user_id());

        $team_leader_id = $this->get_team_leader($member_id);

        $team_members = $this->get_team_members_by_team_leader($team_leader_id);

        return $team_members;
    }

}