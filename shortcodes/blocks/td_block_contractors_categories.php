<?php

class td_block_contractors_categories extends td_block {

    public function get_custom_css() {
        // $unique_block_class - the unique class that is on the block. use this to target the specific instance via css
        $unique_block_class = ((td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax()) ? 'tdc-row .' : '') . $this->block_uid;

        $compiled_css = '';

        $raw_css =
            "<style>
                /* @style_custom_contractors_categories */               
                .$unique_block_class .contractors-categories-tree {
                  --spacing : 1.5rem;
                  --radius  : 10px;
                }
                
                .$unique_block_class .contractors-categories-tree li label{
                    margin-left: 10px;                   
                }
                
                .$unique_block_class .contractors-categories-tree li{
                  display      : block;
                  position     : relative;
                  padding-left : calc(2 * var(--spacing) - var(--radius) - 2px);
                  line-height: 34px;
                }
                
                .$unique_block_class .contractors-categories-tree ul{
                  margin-left  : calc(var(--radius) - var(--spacing));
                  padding-left : 0;
                }
                
                .$unique_block_class .contractors-categories-tree ul li{
                  border-left : 2px solid #ddd;
                  margin-left: 0;
                  line-height: 34px;
                }
                
                .$unique_block_class .contractors-categories-tree ul li:last-child{
                  border-color : transparent;
                }
                
                .$unique_block_class .contractors-categories-tree ul li::before{
                  content      : '';
                  display      : block;
                  position     : absolute;
                  top          : calc(var(--spacing) / -2);
                  left         : -2px;
                  width        : calc(var(--spacing) + 2px);
                  height       : calc(var(--spacing) + 1px);
                  border       : solid #ddd;
                  border-width : 0 0 2px 2px;
                }
                
                .$unique_block_class .contractors-categories-tree summary{
                  display : block;
                  cursor  : pointer;
                }
                
                .$unique_block_class .contractors-categories-tree summary::marker,
                .$unique_block_class .contractors-categories-tree summary::-webkit-details-marker{
                  display : none;
                }
                
                .$unique_block_class .contractors-categories-tree summary:focus{
                  outline : none;
                }
                
                .$unique_block_class .contractors-categories-tree summary:focus-visible{
                  outline : 1px dotted #000;
                }
                
                .$unique_block_class .contractors-categories-tree li::after,
                .$unique_block_class .contractors-categories-tree summary::before{
                  content       : '';
                  display       : block;
                  position      : absolute;
                  top           : calc(var(--spacing) / 2 - var(--radius));
                  left          : calc(var(--spacing) - var(--radius) - 1px);
                  width         : calc(2 * var(--radius));
                  height        : calc(2 * var(--radius));
                  border-radius : 50%;
                  background    : #ddd;
                }
                
                .$unique_block_class .contractors-categories-tree summary::before{
                  z-index    : 1;
                  background : #696 url('expand-collapse.svg') 0 0;
                }
                
                .$unique_block_class .contractors-categories-tree details[open] > summary::before{
                  background-position : calc(-2 * var(--radius)) 0;
                }
                      
            </style>";


        $td_css_res_compiler = new td_css_res_compiler( $raw_css );
        $td_css_res_compiler->load_settings( __CLASS__ . '::cssMedia', $this->get_all_atts() );

        $compiled_css .= $td_css_res_compiler->compile_css();
        return $compiled_css;
    }

    static function cssMedia( $res_ctx ) {

        $res_ctx->load_settings_raw( 'style_custom_contractors_categories', 1 );

    }

    /**
     * Disable loop block features. This block does not use a loop and it doesn't need to run a query.
     */
    function __construct() {
        parent::disable_loop_block_features();
    }


    function render( $atts, $content = null ) {

        parent::render( $atts ); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        $buffy = ''; //output buffer
        $buffy .= '<div class="' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';

        //get the block css
        $buffy .= $this->get_block_css();

        //get the js for this block
        $buffy .= $this->get_block_js();

        $contractors_category_taxonomy = $this->get_att('contractors_taxonomy');
        $contractors_user_role = $this->get_att('contractors_user_role');

        $plan_id1 = $this->get_att('contractors_plan_id1');
        $plan_id1_categories_limit = $this->get_att('contractors_plan_id1_categories_limit');

        $plan_id2 = $this->get_att('contractors_plan_id2');
        $plan_id2_categories_limit = $this->get_att('contractors_plan_id2_categories_limit');

        $plan_id3 = $this->get_att('contractors_plan_id3');
        $plan_id3_categories_limit = $this->get_att('contractors_plan_id3_categories_limit');

        $buffy .= '<div class="tdb-block-inner td-fix-index">';

        if (is_user_logged_in()) {
            global $wpdb;

            $current_user = wp_get_current_user();
            $current_user_id = $current_user->ID;
            $user_roles = $current_user->roles;
            $assigned_categories = get_user_meta($current_user_id, 'selected_terms', true); // Get the assigned categories as an array
            if (empty($assigned_categories)) {
                $assigned_categories = array();
            }

            $table_name = 'tds_subscriptions';

            $plan_id = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT plan_id FROM $table_name WHERE user_id = %d AND status = 'active'",
                    $current_user_id
                )
            );

            $cat_limit = 0;
            if ($plan_id) {
                if ($plan_id = $plan_id1) {
                    $cat_limit = $plan_id1_categories_limit;
                } elseif ($plan_id = $plan_id2) {
                    $cat_limit = $plan_id2_categories_limit;
                } elseif ($plan_id = $plan_id3) {
                    $cat_limit = $plan_id3_categories_limit;
                }
            }

            $buffy .= '<form id="td-cw-categories-to-contractors" method="post" action="">';

            function display_child_terms_with_checkbox($child_terms, $taxonomy, $assigned_categories) {
                $output = '<ul>';
                foreach ($child_terms as $child_term_id) {
                    $child_term = get_term($child_term_id, $taxonomy);
                    $output .= '<li>';
                    $output .= '<label><input type="checkbox" name="selectedTerms[]" value="' . $child_term->term_id . '"';

                    if (in_array($child_term->term_id, $assigned_categories)) {
                        $output .= ' checked="checked"';
                    }

                    $output .= '> ' . $child_term->name . '</label>';

                    $grandchild_terms = get_term_children($child_term->term_id, $taxonomy);
                    if (!empty($grandchild_terms)) {
                        $output .= display_child_terms_with_checkbox($grandchild_terms, $taxonomy, $assigned_categories);
                    }
                    $output .= '</li>';
                }

                $output .= '</ul>';

                return $output;
            }

            if (in_array($contractors_user_role, $user_roles)) {
                $terms = get_terms(array(
                    'taxonomy'   => $contractors_category_taxonomy,
                    'hide_empty' => false,
                ));

                $buffy .= '<ul class="contractors-categories-tree">';
                foreach ($terms as $term) {
                    if ($term->parent == 0) {
                        $buffy .= '<li>';
                        $buffy .= '<label><input type="checkbox" name="selectedTerms[]" value="' . $term->term_id . '"';

                        if (in_array($term->term_id, $assigned_categories)) {
                            $buffy .= ' checked="checked"';
                        }

                        $buffy .= '> ' . $term->name . '</label>';

                        $child_terms = get_term_children($term->term_id, $contractors_category_taxonomy);
                        if (!empty($child_terms)) {
                            $buffy .= display_child_terms_with_checkbox($child_terms, $contractors_category_taxonomy, $assigned_categories);
                        }

                        $buffy .= '</li>';
                    }
                }
                $buffy .= '</ul>';
                $buffy .= '<div id="td-categories-to-contractors-response-message"></div>';
            } else {
                $buffy .= 'You do not have the necessary permissions!';
            }

            wp_nonce_field('save_field_categories_to_contractor_nonce', 'save_field_categories_to_contractor_nonce');
            $buffy .= '<input type="hidden" id="limitCategoriesToContractors" name="limitCategoriesToContractors" value="' . $cat_limit . '" />';

            $buffy .= '<label><input class="td-cw-categories-to-contractors-save" type="submit" name="save_categories_to_contractor" value="Assign Categories"></label>';
            $buffy .= '</form>';
        }


        $buffy .= '</div>';

        $buffy .= '</div>';

        return $buffy;
    }

}