<?php

//Add scripts
function enqueue_contractors_categories_js() {
    wp_enqueue_script('tdcwao-contractors-categories', plugins_url('/assets/js/tdCwContractorsCategories.js', __FILE__), array('jquery'), '1.0', true);

    wp_localize_script('tdcwao-contractors-categories', 'tdcwao_contractors_categories_vars', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('save_categories_to_contractors_nonce'),
    ));
}

add_action('wp_enqueue_scripts', 'enqueue_contractors_categories_js');

//Assign categories to contractors
function process_categories_to_contractors() {
    if (isset($_POST['save_categories_to_contractors_nonce']) && wp_verify_nonce($_POST['save_categories_to_contractors_nonce'], 'save_categories_to_contractors_nonce')) {
        if (is_user_logged_in()) {

            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;

            if (isset($_POST['data'])) {
                parse_str($_POST['data'], $submited_data);
                $selected_terms = $submited_data['selectedTerms'];
                $categories_limit = $submited_data['limitCategoriesToContractors'];

                $count_selected_terms = count($selected_terms);

                if ($count_selected_terms <= $categories_limit) {
                    update_user_meta( $user_id, 'selected_terms', $selected_terms);
                    echo "Saved!";
                } else {
                    echo "Ops, your plan is limited to " . $categories_limit . " categories";
                }
            }
        } else {
            echo 'Nonce verification failed. Please try again.';
        }
    }
    wp_die();
}

add_action('wp_ajax_process_categories_to_contractors', 'process_categories_to_contractors');