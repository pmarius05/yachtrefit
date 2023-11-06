<?php

class tdcwnUtil
{
    public function get_user_role_by_id( $user_id )
    {
        // Get user data by user ID
        $user = get_userdata( $user_id );

        // Check if user exists and has roles set
        if ( $user && !empty( $user->roles ) && is_array( $user->roles ) ) {
            // Users can have multiple roles, so return all roles
            return $user->roles[0];
        } else {
            // No role found for the user, or user does not exist, returning null
            return null;
        }
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

    public function get_account_type($user_id) {
        /* @return string  */
        $type = '';
        if ( 'td_contractor_role' == $this->get_user_role_by_id($user_id) ) {
                $type = 'contractor';
        }
        if ( 'td_client_role' == $this->get_user_role_by_id($user_id) ) {
                $type = 'client';
        }
        if ( 'td_team_member' == $this->get_user_role_by_id($user_id) ) {
            $the_leader = (int)$this->get_team_leader($user_id);
            if ( 'td_contractor_role' == $this->get_user_role_by_id($the_leader) ) {
                $type = 'contractor';
            }elseif ( 'td_client_role' == $this->get_user_role_by_id($the_leader) ) {
                $type = 'client';
            }
        }

        return $type;
    }

    public function get_the_team($user_id)
    {
        /* @return array with the IDs of the team members */

        if ( 'td_team_member' == $this->get_user_role_by_id($user_id) ) :
            $the_leader_id = (int)$this->get_team_leader();
            $team_members = $this->get_team_members_by_member_id($user_id);
            //converts a multidimensional array into a single dimension array
            $team_members = array_reduce($team_members, 'array_merge', array() );
            array_unshift($team_members, $the_leader_id);
            return $team_members;

        elseif ( ('td_client_role' == $this->get_user_role_by_id($user_id) ) || ( 'td_contractor_role' ) == $this->get_user_role_by_id($user_id) ) :
            $team_members = $this->get_team_members_by_team_leader($user_id);
            array_unshift($team_members, $user_id);
            return $team_members;

        endif;

//        return false;
        return $this->get_user_role_by_id($user_id);
    }

    public function get_team_leader($member_id = NULL)
    {
        $member_id = (isset($member_id)) ? $member_id : get_current_user_id();

        return ( get_user_meta( $member_id, 'tdcwn_team', true ) ) ;
    }

    public function get_team_members_by_team_leader ($team_leader_id)
    {
//        define('TEAM_META_KEY', 'tdcwn_team');
        $team_leader_id = ( isset($team_leader_id) ? $team_leader_id : get_current_user_id() );

        $user_role = $this->get_user_role_by_id($team_leader_id);
        //if ( ('td_client_role' == $user_role) || ('td_contractor_role' == $user_role) ) :
        $the_team = get_user_meta($team_leader_id, 'tdcwn_team');
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

    public function flatten_array($array) {
        $result = array();

        if (is_array($array)) :
            foreach ($array as $element) {
                if (is_array($element)) {
                    $result = array_merge($result, $this->flatten_array($element));
                } else {
                    $result[] = $element;
                }
            }
        endif;

        return $result;
    }

    public function is_team_leader($user_id) {
        if (
            'td_client_role' == $this->get_user_role_by_id($user_id) ||
            'td_contractor_role' == $this->get_user_role_by_id($user_id)
        ) {
          return true;
        }else {
            return false;
        }
    }

    public function is_team_member ($user_id)
    {
        if ( 'td_team_member' == $this->get_user_role_by_id($user_id) ) {
            return true;
        }else {
            return false;
        }
    }
}

$tdcwnUtil = new tdcwnUtil();