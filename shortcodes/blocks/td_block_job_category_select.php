<?php

class td_block_job_category_select extends td_block {

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


    function render( $atts, $content = null ) {
        parent::render( $atts ); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        // flag to check if we are in composer
        $is_composer = false;
        if( td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax() ) {
            $is_composer = true;
        }

        global $tdcwnUtil;

        // Get the user subscription plan
        $current_user = get_current_user_id();

        // Check the plan the user is subscribed to and set the category limit
        $category_limit = 0;
        if ( $this->is_user_subscribed_to_plan($current_user, PLAN_1) ) {
            $category_limit = JOB_CATS_PLAN_1;
        }elseif ( $this->is_user_subscribed_to_plan($current_user, PLAN_2) ) {
            $category_limit = JOB_CATS_PLAN_2;
        }


        $job_categories = get_terms(
            array(
                'taxonomy'      => 'job-category',
                'hide_empty'    => false
            )
        );




//                echo '<pre>';
//        var_dump($job_categories);
//        echo '</pre>';

        if ( isset($_POST['save_job_cat_subscription']) ) {
            if (isset($_POST['job_categories_selected'])) {
            $job_categories_selected = $_POST['job_categories_selected'];
            $job_categories_selected_count = count($job_categories_selected);

            $errors = array();

            if ( $job_categories_selected_count > $category_limit ) {
                // Check the category count selected
                $errors[] = 'Category limit exceeded.';
            }

            if (empty($errors)) {
                update_user_meta( $current_user, 'tdcwn_job_cat_selected', $job_categories_selected );
                $success = true;
            }
            }
        }

        // Get selected Job categories from the database
        $selected_categories = get_user_meta($current_user, 'tdcwn_job_cat_selected', true);
        if (!is_array($selected_categories) || empty($selected_categories)) {
            $selected_categories = array();
        }
//        echo '<pre>';
//        var_dump($selected_categories);
//        echo '</pre>';

//        echo $selected_categories[1];

        $buffy = ''; //output buffer
        $buffy .= '<div class="' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';

        //get the block css
        $buffy .= $this->get_block_css();

        //get the js for this block
        $buffy .= $this->get_block_js();


//        $buffy .= '
//            <style>
//                .tdb-s-fc-check.galda:after {
//                    opacity: 1 !important;
//                }
//            </style>';


        $buffy .= '<form method="POST" action="" class="tds-s-form">';

            $buffy .= '<div class="tds-s-page-sec-header">';
                $buffy .= '<h2 class="tds-spsh-title">Select the categories you want to subscribe to</h2>';
                $buffy .= '<div class="tds-spsh-descr">Based on your plan, you can subscribe to maximum 3 categories.</div>';
            $buffy .= '</div>';

            $buffy .= '
                <div class="tdb-ft-checkboxes">
                    <div class="tdb-s-form-checkboxes-wrap">
            ';

            $buffy .= '<p>The currently selected categories are: ';

            if (!empty($selected_categories)) {
                foreach ($selected_categories as $selected_category) {
                    $buffy .= '<span style="font-weight: 600;margin: 0 10px;">'.get_term($selected_category)->name.'</span>';
                }
            }else {
                $buffy .= '<span style="font-weight: 600;margin: 0 10px;"> none </span>';
            }
            $buffy .= '</p>';
            $buffy .= '<div style="width: 100%;height: 1px;"></div>';

                $class = '';
                foreach ($job_categories as $category) {
                    //foreach ($selected_categories as $selected_category) {

//                        if ( $category->term_id == $selected_category ) {
////                            echo $selected_category . '<br>';
//                            $class = 'checked';
////                            $buffy .= $class;
//                            $buffy .= '
//                                <div class="tdb-s-form-checkx tdb-ft-term-164">
//                                    <label class="tdb-s-fc-label">
//                                    <input type="checkbox" name="job_categories_selected[]" value="'.$category->term_id.'" '.$class.' >
//                                    <span class="tdb-s-fc-title">'.$category->name.'</span></label>
//                                </div>
//                            ';
//                        }
//                        elseif ($category->term_id != $selected_category)  {
//                            $buffy .= '
//                                <div class="tdb-s-form-checkX tdb-ft-term-164">
//                                    <label class="tdb-s-fc-label">
//                                    <input type="checkbox" name="job_categories_selected[]" value="'.$category->term_id.'"><span class="tdb-s-fc-check"></span>
//                                    <span class="tdb-s-fc-title" style="color:red;">'.$category->name.'</span></label>
//                                </div>
//                            ';
//                        }


                    //}


                    $buffy .= '
                                <div class="tdb-s-form-checkX tdb-ft-term-164" style="margin-right: 20px;">
                                    <label class="tdb-s-fc-label">
                                    <input type="checkbox" name="job_categories_selected[]" value="'.$category->term_id.'" '.$class.' >
                                    <span class="tdb-s-fc-title">'.$category->name.'</span></label>
                                </div>
                            ';
                }

            $buffy .= '                
                    </div>
                </div>
            ';

            $buffy .= '
                <div class="tds-s-form-footer">
                    <button class="tds-s-btn" type="submit" name="save_job_cat_subscription">Save changes</button>
                </div>
                ';

        $buffy .'</form>';

        $buffy .= '</div>';

        if (isset($success)) :
            $buffy .= '
                <script>
                    let success = `<div class="tds-s-notif tds-s-notif-sm tds-s-notif-success"><div class="tds-s-notif-descr">Account details changed successfully.</div></div>`;
                      jQuery(".tds_account_details .tds-block-inner").prepend(success);
                </script>
            ';
        endif;

        return $buffy;
    }

    public function is_user_subscribed_to_plan( $user_id, $plan_id ) {

        global $wpdb;

        $subscriptions_query = "SELECT
					tds_subscriptions.*, 
					tds_plans.name AS 'plan_name' 
				FROM
					tds_subscriptions 
					LEFT JOIN tds_plans
					ON tds_subscriptions.plan_id = tds_plans.id
                WHERE
                    tds_subscriptions.user_id = %s
                    AND tds_subscriptions.plan_id = %s
                    AND ( tds_subscriptions.status = 'active' OR tds_subscriptions.status = 'free' )";

        $user_subscription = $wpdb->get_results($wpdb->prepare( $subscriptions_query, $user_id, $plan_id), ARRAY_A);

        if ( null !== $user_subscription) {
            if( count($user_subscription) ) {
                return true;
            }
        }

        return false;

    }

}