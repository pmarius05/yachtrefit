<?php

/**
 * Class tdb_location_finder
 */

class tdb_posts_list_yrn extends td_block {

    public function get_custom_css() {
        // $unique_block_class - the unique class that is on the block. use this to target the specific instance via css
        $in_composer = td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax();
        $in_element = td_global::get_in_element();
        $unique_block_class_prefix = '';
        if( $in_element || $in_composer ) {
            $unique_block_class_prefix = 'tdc-row';

            if( $in_element && $in_composer ) {
                $unique_block_class_prefix = 'tdc-row-composer';
            }
        }
        $general_block_class = $unique_block_class_prefix ? '.' . $unique_block_class_prefix : '';
        $unique_block_class = ( $unique_block_class_prefix ? $unique_block_class_prefix . ' .' : '' ) . ( $in_composer ? 'tdc-column .' : '' ) . $this->block_uid;

        $compiled_css = '';

        $raw_css =
            "<style>

                /* @style_general_tdb_posts_list */
                .tdb_posts_list {
                    transform: translateZ(0);
                    font-family: -apple-system,BlinkMacSystemFont,\"Segoe UI\",Roboto,Oxygen-Sans,Ubuntu,Cantarell,\"Helvetica Neue\",sans-serif;
                    font-size: 14px;
                }
                .tdb_posts_list a:not(.tdb-s-btn):not(.tdb-s-tol-item):not(.tdb-s-pagination-item) {
                    color: #0489FC;
                }
                .tdb_posts_list a:not(.tdb-s-btn):not(.tdb-s-tol-item):not(.tdb-s-pagination-item):hover {
                    color: #152BF7;
                }
                .tdb_posts_list .tdb-plist-notifs-top {
                    margin-bottom: 40px;
                }
                .tdb_posts_list .tdb-plist-notifs-top .tdb-s-notif:not(:last-child) {
                    margin-bottom: 28px;
                }
                .tdb_posts_list .tdb-plist-search {
                    margin-bottom: 40px;
                }
                .tdb_posts_list .tdb-plist-search .tdb-s-fc-inner {
                    margin-left: -8px;
                    margin-right: -8px;
                }
                .tdb_posts_list .tdb-plist-search .tdb-s-form-group {
                    margin-bottom: 17px;
                    padding: 0 8px;
                }
                .tdb_posts_list .tdb-plist-search .tdb-s-form-group-button {
                    width: auto;
                }
                .tdb_posts_list .tdb-plist-search button {
                    min-height: 36px;
                    width: 100%;
                }
                @media(min-width: 768px) {
                    .tdb_posts_list .tdb-plist-search .tdb-s-fc-inner {
                        margin: 0;
                    }
                    .tdb_posts_list .tdb-plist-search .tdb-s-form-group {
                        margin-bottom: 0;
                        padding: 0;
                    }
                    .tdb_posts_list .tdb-plist-search .tdb-s-form-group-keyword {
                        flex: 1;
                    }
                    .tdb_posts_list .tdb-plist-search .tdb-s-form-group-in {
                        width: 23%;
                    }
                    .tdb_posts_list .tdb-plist-search .tdb-plist-search-keyword {
                        border-right-width: 0 !important;
                        border-top-right-radius: 0 !important;
                        border-bottom-right-radius: 0 !important;
                    }
                    .tdb_posts_list .tdb-plist-search .tdb-s-form-select-wrap .tdb-plist-search-in {
                        border-right-width: 0 !important;
                        border-radius: 0 !important;
                    }
                    .tdb_posts_list .tdb-plist-search button {
                        border-top-left-radius: 0 !important;
                        border-bottom-left-radius: 0 !important;
                    }
                }
                @media(max-width: 767px) {
                    .tdb_posts_list .tdb-plist-search .tdb-s-form-group-in,
                    .tdb_posts_list .tdb-plist-search .tdb-s-form-group-button {
                        margin-bottom: 0;
                    }
                    .tdb_posts_list .tdb-plist-search .tdb-s-form-group-in {
                        flex: 1;
                    }
                }
                .tdb_posts_list .tdb-plst-add {
                    margin-top: 40px;
                }
                .tdb_posts_list .tdb-plist-title-status {
                    font-size: 0.846em;
                    opacity: .6;
                }
                .tdb_posts_list .tdb-plist-rating {
                    display: flex;
                    align-items: center;
                }
                .tdb_posts_list .tdb-plist-stars {
                    display: flex;
                    align-items: center;
                }
                .tdb_posts_list .tdb-plist-star:not(:last-child) {
                    margin-right: .143em;
                }
                .tdb_posts_list .tdb-plist-star {
                    font-size: 1em;
                    color: #b5b5b5;
                }
                .tdb_posts_list .tdb-plist-star svg {
                    display: block;
                    width: 1em;
                    height: auto;
                    fill: #C1BFBF;
                }
                .tdb_posts_list .tdb-plist-star-full,
                .tdb_posts_list .tdb-plist-star-half {
                    color: #ee8302;
                }
                .tdb_posts_list .tdb-plist-star-full svg,
                .tdb_posts_list .tdb-plist-star-half svg {
                    fill: #ee8302;
                }
                .tdb_posts_list .tdb-pl-img {
                    width: 60px;
                    height: 40px;
                    background-size: cover;
                    background-position: center;
                    background-color: #F5F5F5;
                }
                @media (max-width: 1018px) {
                    .tdb_posts_list .tdb-pl-img {
                        align-self: flex-end;
                    }
                }
                @media (min-width: 1019px) {
                    .tdb_posts_list .tdb-s-table-col-options {
                        width: 7%;
                    }
                }
                .tdb-plist-confirm-modal .tdb-s-modal {
                    width: 600px;
                    max-width: 600px;
                }
                
                /* @style_general_tdb_posts_list_composer */
                .tdb_posts_list a.tdb-s-tol-item {
                    pointer-events: none;
                }
                
                
                
                /* @all_input_border */
                body .$unique_block_class .tdb-s-form .tdb-s-form-input {
                    border: @all_input_border @all_input_border_style @all_input_border_color;
                }
                /* @input_radius */
                body .$unique_block_class .tdb-s-form .tdb-s-form-input {
                    border-radius: @input_radius;
                }
                
                /* @img_width */
                body .$unique_block_class .tdb-pl-img {
                    width: @img_width;
                }
                /* @img_height */
                body .$unique_block_class .tdb-pl-img {
                    height: @img_height;
                }
                
                /* @opt_radius */
                body .$unique_block_class .tdb-s-table-options-list {
                    border-radius: @opt_radius;
                }
                
                /* @modal_width */
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal {
                    min-width: @modal_width;
                    max-width: @modal_width;
                }
                /* @modal_radius */
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal {
                    border-radius: @modal_radius;
                }
                
                /* @btn_radius */
                body .$unique_block_class .tdb-s-btn {
                    border-radius: @btn_radius;
                }
                
                /* @notif_radius */
                body .$unique_block_class .tdb-s-notif {
                    border-radius: @notif_radius;
                }
                
                /* @accent_color */
                body .$unique_block_class a:not(.tdb-s-btn):not(.tdb-s-tol-item):not(.tdb-s-pagination-item),
                body .$unique_block_class .tdb-s-btn-hollow,
                body .tdb-plist-confirm-modal-$this->block_uid a:not(.tdb-s-btn),
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-btn-hollow {
                    color: @accent_color;
                }
                body .$unique_block_class .tdb-s-btn:not(.tdb-s-btn-hollow),
                body .$unique_block_class .tdb-s-pagination-item.tdb-s-pagination-active,
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-btn:not(.tdb-s-btn-hollow) {
                    background-color: @accent_color;
                }
                body .$unique_block_class .tdb-s-btn-hollow,
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-btn-hollow {
                    border-color: @accent_color;
                }
                body .$unique_block_class .tdb-s-form .tdb-s-form-group:not(.tdb-s-fg-error) .tdb-s-form-input:focus:not([readonly]) {
                    border-color: @accent_color !important;
                }
                /* @input_outline_accent_color */
                body .$unique_block_class .tdb-s-form .tdb-s-form-group:not(.tdb-s-fg-error) .tdb-s-form-input:focus:not([readonly]) {
                    outline-color: @input_outline_accent_color;
                }
                /* @a_color_h */
                body .$unique_block_class a:not(.tdb-s-btn):not(.tdb-s-tol-item):not(.tdb-s-pagination-item):hover,
                body .tdb-plist-confirm-modal-$this->block_uid a:not(.tdb-s-btn):hover {
                    color: @a_color_h;
                }

                /* @input_color */
                body .$unique_block_class .tdb-s-form .tdb-s-form-input {
                    color: @input_color;
                }
                body .$unique_block_class .tdb-s-form .tdb-s-form-select-wrap .tdb-s-form-select-icon {
                    fill: @input_color;
                }
                body .$unique_block_class .tdb-s-form .tdb-s-form-input:-webkit-autofill,
                body .$unique_block_class .tdb-s-form .tdb-s-form-input:-webkit-autofill:hover,
                body .$unique_block_class .tdb-s-form .tdb-s-form-input:-webkit-autofill:focus,
                body .$unique_block_class .tdb-s-form .tdb-s-form-input:-webkit-autofill:active {
                    -webkit-text-fill-color: @input_color;
                }
                /* @input_place_color */
                body .$unique_block_class .tdb-s-form .tdb-s-form-input::placeholder {
                    color: @input_place_color;
                }
                body .$unique_block_class .tdb-s-form .tdb-s-form-input::-webkit-input-placeholder {
                    color: @input_place_color;
                }
                body .$unique_block_class .tdb-s-form .tdb-s-form-input::-moz-placeholder {
                    color: @input_place_color;
                }
                body .$unique_block_class .tdb-s-form .tdb-s-form-input::-ms-input-placeholder {
                    color: @input_place_color;
                }
                body .$unique_block_class .tdb-s-form .tdb-s-form-input:::-moz-placeholder  {
                    color: @input_place_color;
                }
                /* @input_bg */
                body .$unique_block_class .tdb-s-form .tdb-s-form-input {
                    background-color: @input_bg;
                }
                body .$unique_block_class .tdb-s-form .tdb-s-form-input:-webkit-autofill,
                body .$unique_block_class .tdb-s-form .tdb-s-form-input:-webkit-autofill:hover,
                body .$unique_block_class .tdb-s-form .tdb-s-form-input:-webkit-autofill:focus,
                body .$unique_block_class .tdb-s-form .tdb-s-form-input:-webkit-autofill:active {
                    -webkit-box-shadow: 0 0 0 1000px @input_bg inset !important;
                }
                
                /* @tabl_border_color */
                body .$unique_block_class .tdb-s-table-header, 
                body .$unique_block_class .tdb-s-table-row:not(:last-child) {
                    border-bottom-color: @tabl_border_color;
                }
                /* @tabl_head_color */
                body .$unique_block_class .tdb-s-table-header {
                    color: @tabl_head_color;
                }
                body .$unique_block_class .tdb-s-table-col-order-icons svg {
                    fill: @tabl_head_color;
                }
                /* @tabl_body_color */
                body .$unique_block_class .tdb-s-table-body {
                    color: @tabl_body_color;
                }
                /* @tabl_hover_bg */
                body .$unique_block_class .tdb-s-table-body .tdb-s-table-row:hover {
                    background-color: @tabl_hover_bg;
                }
                
                /* @empty_color */
                body .$unique_block_class .tdb-plist-star-empty {
                    color: @empty_color;
                }
                body .$unique_block_class .tdb-plist-star-empty svg {
                    fill: @empty_color;
                }
                /* @full_color */
                body .$unique_block_class .tdb-plist-star-full,
                body .$unique_block_class .tdb-plist-star-half {
                    color: @full_color;
                }
                body .$unique_block_class .tdb-plist-star-full svg,
                body .$unique_block_class .tdb-plist-star-half svg {
                    fill: @full_color;
                }
                
                /* @opt_bg */
                body .$unique_block_class .tdb-s-table-options-list {
                    background-color: @opt_bg;
                }
                /* @opt_shadow */
                @media (min-width: 1019px) {
                    body .$unique_block_class .tdb-s-table-options-list {
                        box-shadow: @opt_shadow;
                    }
                }
                /* @opt_border_color */
                body .$unique_block_class .tdb-s-tol-sep {
                    background-color: @opt_border_color;
                }
                /* @opt_item_color */
                body .$unique_block_class .tdb-s-table-col-options .tdb-s-tol-item:not(.tdb-s-tol-item-red) {
                    color: @opt_item_color;
                }
                /* @opt_item_color_h */
                body .$unique_block_class .tdb-s-table-col-options .tdb-s-tol-item:not(.tdb-s-tol-item-red):hover {
                    color: @opt_item_color_h;
                }
                /* @opt_del_color */
                body .$unique_block_class .tdb-s-table-col-options .tdb-s-tol-item-red {
                    color: @opt_del_color;
                }
                /* @opt_del_color_h */
                body .$unique_block_class .tdb-s-table-col-options .tdb-s-tol-item-red:hover {
                    color: @opt_del_color_h;
                }
                /* @pag_bg */
                body .$unique_block_class .tdb-s-pagination-item:not(.tdb-s-pagination-active) {
                    background-color: @pag_bg;
                }
                /* @pag_bg_h */
                body .$unique_block_class .tdb-s-pagination-item:hover:not(.tdb-s-pagination-dots):not(.tdb-s-pagination-active) {
                    background-color: @pag_bg_h;
                }
                /* @pag_color */
                body .$unique_block_class .tdb-s-pagination-item:not(.tdb-s-pagination-active) {
                    color: @pag_color;
                }
                /* @pag_color_h */
                body .$unique_block_class .tdb-s-pagination-item:hover:not(.tdb-s-pagination-dots):not(.tdb-s-pagination-active) {
                    color: @pag_color_h;
                }
                /* @pag_color_a */
                body .$unique_block_class .tdb-s-pagination-item.tdb-s-pagination-active {
                    color: @pag_color_h;
                }
                
                /* @modal_bg */
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal {
                    background-color: @modal_bg;
                }
                /* @modal_overlay_solid */
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal-bg {
                    background-color: @modal_overlay_solid;
                }
                /* @modal_overlay_gradient */
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal-bg {
                    @modal_overlay_gradient
                }
                /* @modal_sep */
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal-header {
                    border-bottom-color: @modal_sep;
                }
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal-footer {
                    border-top-color: @modal_sep;
                }
                /* @modal_shadow */
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal {
                    box-shadow: @modal_shadow;
                }
                /* @modal_title */
                body .tdb-plist-confirm-modal-$this->block_uid h3.tdb-s-modal-title {
                    color: @modal_title;
                }
                /* @modal_close */
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal-header .tdb-s-modal-close {
                    fill: @modal_close;
                }
                /* @modal_body */
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal-txt {
                    color: @modal_body;
                }
                
                /* @btn_color */
                body .$unique_block_class .tdb-s-btn,
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-btn {
                    color: @btn_color;
                }
                /* @btn_color_h */
                body .$unique_block_class .tdb-s-btn:hover,
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-btn:hover {
                    color: @btn_color_h;
                }
                /* @btn_bg_h */
                body .$unique_block_class .tdb-s-btn:not(.tdb-s-btn-hollow):hover,
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-btn:not(.tdb-s-btn-hollow):hover {
                    background-color: @btn_bg_h;
                }
                body .$unique_block_class .tdb-s-btn-hollow:hover,
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-btn-hollow:hover {
                    border-color: @btn_bg_h;
                }
                
                /* @notif_info_color */
                body .$unique_block_class .tdb-s-notif-info {
                    color: @notif_info_color;
                }
                /* @notif_info_bg */
                body .$unique_block_class .tdb-s-notif-info {
                    background-color: @notif_info_bg;
                }
                /* @notif_succ_color */
                body .$unique_block_class .tdb-s-notif-success {
                    color: @notif_succ_color;
                }
                /* @notif_succ_bg */
                body .$unique_block_class .tdb-s-notif-success {
                    background-color: @notif_succ_bg;
                }
                
                
                /* @f_text */
                body .$unique_block_class .tdb-s-table-col,
                body .tdb-plist-confirm-modal-$this->block_uid .tdb-s-modal {
                    @f_text
                }
                
			</style>";


        $td_css_res_compiler = new td_css_res_compiler( $raw_css );
        $td_css_res_compiler->load_settings( __CLASS__ . '::cssMedia', $this->get_all_atts() );

        $compiled_css .= $td_css_res_compiler->compile_css();
        return $compiled_css;
    }

    static function cssMedia( $res_ctx ) {

        /*-- GENERAL STYLES -- */
        $res_ctx->load_settings_raw( 'style_general_tdb_posts_list', 1 );
        if( td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax() ) {
            $res_ctx->load_settings_raw( 'style_general_tdb_posts_list_composer', 1 );
        }



        /*-- LAYOUT -- */
        // inputs border size
        $all_input_border = $res_ctx->get_shortcode_att('all_input_border');
        if( $all_input_border == '' ) {
            $res_ctx->load_settings_raw( 'all_input_border', '2px' );
        } else {
            if( is_numeric( $all_input_border ) ) {
                $res_ctx->load_settings_raw( 'all_input_border', $all_input_border . 'px' );
            }
        }

        // inputs border style
        $all_input_border_style = $res_ctx->get_shortcode_att('all_input_border_style');
        if( $all_input_border_style != '' ) {
            $res_ctx->load_settings_raw( 'all_input_border_style', $all_input_border_style );
        } else {
            $res_ctx->load_settings_raw( 'all_input_border_style', 'solid' );
        }

        // inputs border radius
        $input_radius = $res_ctx->get_shortcode_att('input_radius');
        if( $input_radius != '' && is_numeric( $input_radius ) ) {
            $res_ctx->load_settings_raw( 'input_radius', $input_radius . 'px' );
        }

        // images width
        $img_width = $res_ctx->get_shortcode_att('img_width');
        if( $img_width != '' && is_numeric( $img_width ) ) {
            $res_ctx->load_settings_raw( 'img_width', $img_width . 'px' );
        }
        // images height
        $img_height = $res_ctx->get_shortcode_att('img_height');
        if( $img_height != '' && is_numeric( $img_height ) ) {
            $res_ctx->load_settings_raw( 'img_height', $img_height . 'px' );
        }


        // table options border radius
        $opt_radius = $res_ctx->get_shortcode_att('opt_radius');
        $res_ctx->load_settings_raw( 'opt_radius', $opt_radius );
        if( $opt_radius != '' && is_numeric( $opt_radius ) ) {
            $res_ctx->load_settings_raw( 'opt_radius', $opt_radius . 'px' );
        }


        // modal width
        $modal_width = $res_ctx->get_shortcode_att('modal_width');
        $res_ctx->load_settings_raw( 'modal_width', $modal_width );
        if( $modal_width != '' && is_numeric( $modal_width ) ) {
            $res_ctx->load_settings_raw( 'modal_width', $modal_width . 'px' );
        }

        // modal border radius
        $modal_radius = $res_ctx->get_shortcode_att('modal_radius');
        $res_ctx->load_settings_raw( 'modal_radius', $modal_radius );
        if( $modal_radius != '' && is_numeric( $modal_radius ) ) {
            $res_ctx->load_settings_raw( 'modal_radius', $modal_radius . 'px' );
        }


        // buttons border radius
        $btn_radius = $res_ctx->get_shortcode_att('btn_radius');
        $res_ctx->load_settings_raw( 'btn_radius', $btn_radius );
        if( $btn_radius != '' && is_numeric( $btn_radius ) ) {
            $res_ctx->load_settings_raw( 'btn_radius', $btn_radius . 'px' );
        }


        // notifications border radius
        $notif_radius = $res_ctx->get_shortcode_att('notif_radius');
        $res_ctx->load_settings_raw( 'notif_radius', $notif_radius );
        if( $notif_radius != '' && is_numeric( $notif_radius ) ) {
            $res_ctx->load_settings_raw( 'notif_radius', $notif_radius . 'px' );
        }



        /*-- COLORS -- */
        $accent_color = $res_ctx->get_shortcode_att('accent_color');
        $res_ctx->load_settings_raw( 'accent_color', $accent_color );
        if( !empty( $accent_color ) ) {
            $res_ctx->load_settings_raw('input_outline_accent_color', td_util::hex2rgba($accent_color, 0.1));
        }
        $res_ctx->load_settings_raw( 'a_color_h', $res_ctx->get_shortcode_att('a_color_h') );

        $res_ctx->load_settings_raw( 'input_color', $res_ctx->get_shortcode_att('input_color') );
        $res_ctx->load_settings_raw( 'input_place_color', $res_ctx->get_shortcode_att('input_place_color') );
        $res_ctx->load_settings_raw( 'input_bg', $res_ctx->get_shortcode_att('input_bg') );
        $all_input_border_color = $res_ctx->get_shortcode_att('all_input_border_color');
        if( $all_input_border_color != '' ) {
            $res_ctx->load_settings_raw( 'all_input_border_color', $all_input_border_color );
            $res_ctx->load_settings_raw( 'input_select2_outline_color', td_util::hex2rgba($all_input_border_color, 0.18));
        } else {
            $res_ctx->load_settings_raw( 'all_input_border_color', '#D7D8DE' );
        }

        $res_ctx->load_settings_raw( 'tabl_border_color', $res_ctx->get_shortcode_att('tabl_border_color') );
        $res_ctx->load_settings_raw( 'tabl_head_color', $res_ctx->get_shortcode_att('tabl_head_color') );
        $res_ctx->load_settings_raw( 'tabl_body_color', $res_ctx->get_shortcode_att('tabl_body_color') );
        $res_ctx->load_settings_raw( 'tabl_hover_bg', $res_ctx->get_shortcode_att('tabl_hover_bg') );

        $res_ctx->load_settings_raw('full_color', $res_ctx->get_shortcode_att('full_color'));
        $res_ctx->load_settings_raw('empty_color', $res_ctx->get_shortcode_att('empty_color'));

        $res_ctx->load_settings_raw( 'opt_bg', $res_ctx->get_shortcode_att('opt_bg') );
        $res_ctx->load_shadow_settings( 4, 0, 0, 0, 'rgba(0, 0, 0, 0.12)', 'opt_shadow' );
        $res_ctx->load_settings_raw( 'opt_border_color', $res_ctx->get_shortcode_att('opt_border_color') );
        $res_ctx->load_settings_raw( 'opt_item_color', $res_ctx->get_shortcode_att('opt_item_color') );
        $res_ctx->load_settings_raw( 'opt_item_color_h', $res_ctx->get_shortcode_att('opt_item_color_h') );
        $res_ctx->load_settings_raw( 'opt_del_color', $res_ctx->get_shortcode_att('opt_del_color') );
        $res_ctx->load_settings_raw( 'opt_del_color_h', $res_ctx->get_shortcode_att('opt_del_color_h') );
        $res_ctx->load_settings_raw( 'pag_bg', $res_ctx->get_shortcode_att('pag_bg') );
        $res_ctx->load_settings_raw( 'pag_bg_h', $res_ctx->get_shortcode_att('pag_bg_h') );
        $res_ctx->load_settings_raw( 'pag_color', $res_ctx->get_shortcode_att('pag_color') );
        $res_ctx->load_settings_raw( 'pag_color_h', $res_ctx->get_shortcode_att('pag_color_h') );
        $res_ctx->load_settings_raw( 'pag_color_a', $res_ctx->get_shortcode_att('pag_color_a') );

        $res_ctx->load_settings_raw( 'modal_bg', $res_ctx->get_shortcode_att('modal_bg') );
        $res_ctx->load_color_settings( 'modal_overlay', 'modal_overlay_solid', 'modal_overlay_gradient', '', '' );
        $res_ctx->load_shadow_settings( 4, 0, 2, 0, 'rgba(0, 0, 0, .12)', 'modal_shadow' );
        $res_ctx->load_settings_raw( 'modal_sep', $res_ctx->get_shortcode_att('modal_sep') );
        $res_ctx->load_settings_raw( 'modal_title', $res_ctx->get_shortcode_att('modal_title') );
        $res_ctx->load_settings_raw( 'modal_close', $res_ctx->get_shortcode_att('modal_close') );
        $res_ctx->load_settings_raw( 'modal_body', $res_ctx->get_shortcode_att('modal_body') );

        $res_ctx->load_settings_raw( 'btn_color', $res_ctx->get_shortcode_att('btn_color') );
        $res_ctx->load_settings_raw( 'btn_color_h', $res_ctx->get_shortcode_att('btn_color_h') );
        $res_ctx->load_settings_raw( 'btn_bg_h', $res_ctx->get_shortcode_att('btn_bg_h') );

        $notif_info_color = $res_ctx->get_shortcode_att('notif_info_color');
        $res_ctx->load_settings_raw( 'notif_info_color', $notif_info_color );
        if( !empty( $notif_info_color ) ) {
            $res_ctx->load_settings_raw('notif_info_bg', td_util::hex2rgba($notif_info_color, 0.08));
        }

        $notif_succ_color = $res_ctx->get_shortcode_att('notif_succ_color');
        $res_ctx->load_settings_raw( 'notif_succ_color', $notif_succ_color );
        if( !empty( $notif_succ_color ) ) {
            $res_ctx->load_settings_raw('notif_succ_bg', td_util::hex2rgba($notif_succ_color, 0.1));
        }



        /*-- FONTS -- */
        $res_ctx->load_font_settings( 'f_text' );

    }

    /**
     * Disable loop block features. This block does not use a loop and it doesn't need to run a query.
     */
    function __construct() {
        parent::disable_loop_block_features();
    }

    function render( $atts, $content = null ) {
        parent::render( $atts ); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        $buffy = ''; // output buffer

        if ( !is_user_logged_in() && !( td_util::tdc_is_live_editor_ajax() || td_util::tdc_is_live_editor_iframe() ) ) {
            return $buffy;
        }

        // what to show in composer
        $show_in_composer = $this->get_att('show_version');

        // currently logged in user
        $current_user = wp_get_current_user();
        $current_user_id = $current_user->ID;
        $is_current_user_admin = in_array('administrator', $current_user->roles );

        // Post type
        $post_type = $this->get_att( 'post_type' );
        if ( $post_type == '' ) {
            $post_type = 'post';
        }

        // plans add limits
        $limit_from = $this->get_att('limit_from') != '' ? $this->get_att('limit_from') : 'shortcode';
        $add_new_posts_limit = -1;
        $limit_reached = false;

        if ( is_user_logged_in() ) {

            if( $limit_from == 'shortcode' || !defined( 'TD_SUBSCRIPTION' ) ) {

                $add_new_posts_limit = $this->get_att('limit_def') != '' ? $this->get_att('limit_def') : -1;

                $current_user_posts = get_posts(array(
                    'post_type' => $post_type,
                    'post_status' => array('publish', 'draft', 'private'),
                    'numberposts' => -1,
                    'author' => $current_user_id,
                ));

                if( $add_new_posts_limit > -1 && ( count( $current_user_posts ) >= $add_new_posts_limit ) ) {
                    $limit_reached = true;
                }

            } else {

                $add_new_posts_limit = 0;

                if( defined( 'TD_SUBSCRIPTION' ) && method_exists( 'tds_util', 'get_user_subscriptions' ) ) {
                    $user_subscriptions = tds_util::get_user_subscriptions($current_user_id, null, array('active', 'free'));
                    if( $user_subscriptions ) {
                        foreach( $user_subscriptions as $user_subscription ) {
                            if( isset( $user_subscription['plan_posts_remaining'] ) ) {
                                $plan_posts_remaining = $user_subscription['plan_posts_remaining'] ? unserialize($user_subscription['plan_posts_remaining']) : array();

                                if( !empty( $plan_posts_remaining ) ) {
                                    foreach( $plan_posts_remaining as $remaining_post_type => $remaining_posts ) {
                                        if( $remaining_post_type != $post_type ) {
                                            continue;
                                        }

                                        if( $remaining_posts == '' ) {
                                            continue;
                                        }

                                        $add_new_posts_limit += $remaining_posts;
                                    }
                                }
                            }
                        }
                    }

                    if( $add_new_posts_limit == 0 ) {
                        $limit_reached = true;
                    }

                }

            }

        }

        // show notifications in composer
        $show_notif_in_composer = $this->get_att('show_notif');

        $render_options = array(
            'postType' => $post_type,
            'linkedPostType' => $this->get_att('linked_post_type'),
            'showAllPosts' => $this->get_att('all_posts') != '',
            'allowPublish' => $this->get_att('allow_publish') != '',
            'allowDelete' => $this->get_att('allow_delete') != '',
            'addNewPostLimitReached' => $limit_reached,
            'limitNotifTxt' => $this->get_att('limit_notif'),
            'columns' => $this->get_att( 'display_columns' ),

            'fullStarIcon' => $this->get_att( 'tdicon_full' ),
            'halfStarIcon' => $this->get_att( 'tdicon_half' ),
            'emptyStarIcon' => $this->get_att( 'tdicon_empty' ),

            'mainFormURL' => $this->get_att('form_1'),
            'mainFormAddTxt' => $this->get_att('form_1_txt_a') != '' ? $this->get_att('form_1_txt_a') : __td( 'Add new post', TD_THEME_NAME ),
            'mainFormEditTxt' => $this->get_att('form_1_txt_e') != '' ? $this->get_att('form_1_txt_e') : __td( 'Edit post', TD_THEME_NAME ),
            'extraForm1URL' => $this->get_att('form_2'),
            'extraForm1EditTxt' => $this->get_att('form_2_txt_e') != '' ? $this->get_att('form_2_txt_e') : 'Edit post 2',
            'extraForm2URL' => $this->get_att('form_3'),
            'extraForm2EditTxt' => $this->get_att('form_3_txt_e') != '' ? $this->get_att('form_3_txt_e') : 'Edit post 3',
            'child1FormURL' => $this->get_att('form_5'),
            'child1FormEditTxt' => $this->get_att('form_5_txt_e'),
            'child2FormURL' => $this->get_att('form_6'),
            'child2FormEditTxt' => $this->get_att('form_6_txt_e'),

            'enablePagination' => $this->get_att('enable_pag') != '',
            'perPage' => $this->get_att('per_page') != '' ? $this->get_att('per_page') : 15,
            'currentPage' => 1,

        );

        $buffy .= '<div class="tdb_posts_list ' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';

        // get the block css
        $buffy .= $this->get_block_css();

        // get the js for this block
        $buffy .= $this->get_block_js();

        // add tdb_posts_list nonce to authenticate ajax requests
        if ( is_user_logged_in() ) {
            $buffy .= '<input type="hidden" id="tdb_posts_list_nonce" name="tdb_posts_list_nonce" value="' . wp_create_nonce(__CLASS__) . '"/>';
        }

        // block inner
        $buffy .= '<div class="tdb-block-inner td-fix-index tdb-s-content">';
        $buffy .= $this->tdcwn_render_list( $render_options, array() );
        $buffy .= '</div>';

        if ( !( td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax() ) ) {
            td_resources_load::render_script( TDB_SCRIPTS_URL . '/tdbModal.js' . TDB_SCRIPTS_VER, 'tdbModal-js', '', 'footer' );
            td_resources_load::render_script( TDB_SCRIPTS_URL . '/tdbPostsList.js' . TDB_SCRIPTS_VER, 'tdbPostsList-js', '', 'footer' );

            ob_start();
            ?>
            <script>

                /* global jQuery:{} */
                jQuery().ready( function () {

                    let uid = '<?php echo $this->block_uid ?>',
                        $blockObj = jQuery('.<?php echo $this->block_uid ?>');

                    let tdbPostsListItem = new tdbPostsList.item();

                    // block uid
                    tdbPostsListItem.uid = uid;

                    // block object
                    tdbPostsListItem.blockObj = $blockObj;

                    tdbPostsListItem.renderOptions = jQuery.parseJSON('<?php echo json_encode($render_options) ?>');
                    tdbPostsListItem.confirmModals = {
                        'publish': {
                            'title': tdbPostsList._stringToBinary('<?php echo __td('Publish a post', TD_THEME_NAME) ?>'),
                            'body': tdbPostsList._stringToBinary('<?php echo __td( 'Are you sure you want to publish %POST_TITLE%?', TD_THEME_NAME) ?>')
                        },
                        'delete': {
                            'title': tdbPostsList._stringToBinary('<?php echo __td('Delete a post', TD_THEME_NAME) ?>'),
                            'body': tdbPostsList._stringToBinary('<?php echo __td( 'Are you sure you want to delete %POST_TITLE%?', TD_THEME_NAME) ?>')
                        }
                    };

                    tdbPostsList.addItem(tdbPostsListItem);

                });
            </script>
            <?php
            td_js_buffer::add_to_footer( "\n" . td_util::remove_script_tag( ob_get_clean() ) );
        }

        $buffy .= '</div> <!-- ./block -->';

        return $buffy;
    }

    public function tdcwn_render_list( $options, $active_filters ) {

        $buffy = '';


        /* --
        -- FLAG TO CHECK IF WE ARE IN COMPOSER
        -- */
        $is_composer = false;
        if( td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax() ) {
            $is_composer = true;
        }



        /* --
        -- VARIOUS SETTINGS
        -- */
        /* -- Columns order icons -- */
        $column_order_icons = '<div class="tdb-s-table-col-order-icons">';
        $column_order_icons .= '<svg xmlns="http://www.w3.org/2000/svg" width="8" height="4.571" viewBox="0 0 8 4.571"><path id="Path_2" data-name="Path 2" d="M4,2,8,6.571H0Z" transform="translate(0 -2)"/></svg>';
        $column_order_icons .= '<svg xmlns="http://www.w3.org/2000/svg" width="8" height="4.571" viewBox="0 0 8 4.571"><path id="Path_1" data-name="Path 1" d="M4,2,8,6.571H0Z" transform="translate(8 6.571) rotate(180)"/></svg>';
        $column_order_icons .= '</div>';


        /* -- Rating stars -- */
        $full_star_icon = tdb_util::get_icon_att($options['fullStarIcon']);
        $full_star_icon_data = '';
        if( td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax() ) {
            $full_star_icon_data = 'data-td-svg-icon="' . $options['fullStarIcon'] . '"';
        }
        $full_star_icon_html = '';
        if ( !empty( $full_star_icon ) ) {
            if( base64_encode( base64_decode( $full_star_icon ) ) == $full_star_icon ) {
                $full_star_icon_html = base64_decode( $full_star_icon ) ;
            } else {
                $full_star_icon_html = '<i class="' . $full_star_icon . '"></i>';
            }
        }

        $half_star_icon = tdb_util::get_icon_att($options['halfStarIcon']);
        $half_star_icon_data = '';
        if( td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax() ) {
            $half_star_icon_data = 'data-td-svg-icon="' . $options['halfStarIcon'] . '"';
        }
        $half_star_icon_html = '';
        if ( !empty( $half_star_icon ) ) {
            if( base64_encode( base64_decode( $half_star_icon ) ) == $half_star_icon ) {
                $half_star_icon_html = base64_decode( $half_star_icon ) ;
            } else {
                $half_star_icon_html = '<i class="' . $half_star_icon . '"></i>';
            }
        }

        $empty_star_icon = tdb_util::get_icon_att($options['emptyStarIcon']);
        $empty_star_icon_data = '';
        if( td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax() ) {
            $empty_star_icon_data = 'data-td-svg-icon="' . $options['emptyStarIcon'] . '"';
        }
        $empty_star_icon_html = '';
        if ( !empty( $empty_star_icon ) ) {
            if( base64_encode( base64_decode( $empty_star_icon ) ) == $empty_star_icon ) {
                $empty_star_icon_html = base64_decode( $empty_star_icon ) ;
            } else {
                $empty_star_icon_html = '<i class="' . $empty_star_icon . '"></i>';
            }
        }


        /* -- Forms URLs -- */
        $main_form_url = $options['mainFormURL'];
        $main_form_add_txt = $options['mainFormAddTxt'];
        $main_form_edit_txt = $options['mainFormEditTxt'];

        $extra_form_1_url = $options['extraForm1URL'];
        $extra_form_1_edit_txt = $options['extraForm1EditTxt'];

        $extra_form_2_url = $options['extraForm2URL'];
        $extra_form_2_edit_txt = $options['extraForm2EditTxt'];

        /* -- Post childs forms --*/

        $child1_form_url = $options['child1FormURL'];
        $child1_form_edit_txt = $options['child1FormEditTxt'];

        $child2_form_url = $options['child2FormURL'];
        $child2_form_edit_txt = $options['child2FormEditTxt'];


        /* -- Limit notification text -- */
        $limit_notif = rawurldecode( base64_decode( strip_tags( $options['limitNotifTxt'] ) ) );



        /* --
        -- COLUMNS TO DISPLAY
        -- */
        $columns_to_display = $options['columns'];
        if( $columns_to_display != '' ) {
            $columns_to_display = explode( "\n", rawurldecode( base64_decode( strip_tags( $columns_to_display ) ) ) );
        } else {
            $columns_to_display = array();
        }
        $columns_to_display = array_map('trim', $columns_to_display);
        $columns_to_display = array_filter($columns_to_display, function($value) { return !is_null($value) && $value !== ''; });
        $predefined_columns = array('id', 'overall_rating', 'title', 'featured_image', 'categories', 'tags', 'date', 'author', 'source_title');

        $custom_columns = array_diff($columns_to_display, $predefined_columns);



        /* --
        -- CURRENTLY LOGGED IN USER INFO
        -- */
        $current_user = wp_get_current_user();
        $is_current_user_admin = in_array('administrator', $current_user->roles );



        /* --
        -- GET THE POSTS
        -- */
        $allowed_post_statuses = array('publish', 'draft', 'private', 'pending');

        $args = array(
            'post_type' => $options['postType'],
            'post_status' => $allowed_post_statuses,
            'numberposts' => -1
        );


        global $tdcwnUtil;
        $the_team = $tdcwnUtil->get_the_team($current_user->ID);
        $the_team = $tdcwnUtil->flatten_array($the_team);

//        echo '<pre>';
//        print_r($the_team);
//        echo '</pre>';

        /* -- If the user is not an admin or the show all posts for admins -- */
        /* -- option is disabled, get the posts for the currently logged in user only -- */
        if( !$is_current_user_admin || !$options['showAllPosts'] || $options['showAllPosts'] === 'false' ) {
            // $args['author'] = $current_user->ID;
            $args['author__in'] = $the_team;
        }

        $current_user_initial_posts = get_posts($args);
        $current_user_posts = array();


        /* -- Check for linked posts -- */
        $linked_post_type = $options['linkedPostType'];
        if( $linked_post_type != '' && post_type_exists( $linked_post_type ) ) {
            foreach ( $current_user_initial_posts as $current_user_initial_post ) {
                $post_linked_posts = get_post_meta($current_user_initial_post->ID, 'tdc-post-linked-posts', true);

                if( !empty( $post_linked_posts ) ) {
                    if( isset( $post_linked_posts[$linked_post_type] ) ) {
                        foreach ( $post_linked_posts[$linked_post_type] as $post_linked_post_id ) {
                            $post_linked_post = get_post($post_linked_post_id);

                            if( !is_null( $post_linked_post ) ) {
                                if( in_array( $post_linked_post->post_status, $allowed_post_statuses ) ) {
                                    $current_user_posts[] = $post_linked_post;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $current_user_posts = $current_user_initial_posts;
        }


        /* -- Build the posts array -- */
        $posts = array();
        if( !empty( $current_user_posts ) ) {
            foreach ( $current_user_posts as $current_user_post ) {
                $post = array(
                    'ID' => $current_user_post->ID,
                    'featured_image' => '',
                    'title' => $current_user_post->post_title,
                    'author' => get_the_author_meta('display_name', $current_user_post->post_author),
                    'author_url' => get_author_posts_url($current_user_post->post_author),
                    'publish_date' => get_the_time(get_option('date_format'), $current_user_post->ID),
                    'parent' => !empty( $current_user_post->post_parent ) ? $current_user_post->post_parent : '',
                    'tdc_parent' => get_post_meta( $current_user_post->ID, 'tdc-parent-post-id', true ),
                );

                if( $current_user_post->post_status == 'publish' ) {
                    $post['status'] = 'Published';
                } else {
                    $post['status'] = ucfirst($current_user_post->post_status);
                }

                foreach ( $columns_to_display as $column ) {
                    switch ($column) {
                        case 'overall_rating':
                            $post_type = get_post_type($post['ID']);

                            if( $post_type == 'tdc-review' ) {
                                $overall_rating = td_util::get_overall_review_rating($post['ID']);
                            } else {
                                $overall_rating = td_util::get_overall_post_rating($post['ID']);
                            }

                            $post['overall_rating'] = $overall_rating ? $overall_rating : floatval(0);

                            break;

                        case 'featured_image':
                            $featured_image = '';

                            if ( has_post_thumbnail( $post['ID'] ) ) {
                                $post_thumbnail_id = get_post_thumbnail_id( $post['ID'] );

                                if ( !empty( $post_thumbnail_id ) ) {
                                    $featured_image = wp_get_attachment_image_src( $post_thumbnail_id, 'full' )[0];
                                }
                            }

                            $post['featured_image'] = $featured_image;

                            break;

                        case 'categories':
                            $post['categories'] = self::list_terms($post['ID'], 'category');
                            break;

                        case 'tags':
                            $post['tags'] = self::list_terms($post['ID'], 'post_tag');
                            break;

                        case 'source_title':
                            $source_post_id = get_post_meta($post['ID'], 'tdc-parent-post-id', true);
                            $source_post_title = '';
                            if ('' !== $source_post_id) {
                                $source_post_title = get_the_title($source_post_id);
                            }

                            $post['source_title'] = $source_post_title;
                            $post['source_url'] = esc_url( get_permalink( $source_post_id ) );

                            break;
                    }
                }

                foreach ( $custom_columns as $custom_column ) {
                    $post[$custom_column] = '';

                    if( !empty( $taxonomy = self::get_taxonomy_from_string($custom_column) ) ) {
                        $post[$custom_column] = self::list_terms( $post['ID'], $taxonomy );
                    } else {
                        $custom_field_data = td_util::get_acf_field_data($custom_column, $post['ID']);

                        if( !$custom_field_data['meta_exists'] ) {
                            if( metadata_exists('post', $post['ID'], $custom_column) ) {
                                $custom_field_data['value'] = get_post_meta($post['ID'], $custom_column, true);
                                $custom_field_data['type'] = 'text';
                                $custom_field_data['meta_exists'] = true;
                            }
                        }

                        if( !empty( $custom_field_data['value'] ) ) {
                            if( $custom_field_data['type'] == 'image' ) {
                                $img_url = '';

                                if( is_array( $custom_field_data['value'] ) ) {
                                    $img_url = $custom_field_data['value']['url'];
                                } else if( is_string( $custom_field_data['value'] ) ) {
                                    $img_url = $custom_field_data['value'];
                                } else if ( is_numeric( $custom_field_data['value'] ) ) {
                                    $img_id = $custom_field_data['value'];
                                    $img_info = get_post( $img_id );

                                    if( $img_info ) {
                                        $img_url = $img_info->guid;
                                    }
                                }

                                $post[$custom_column] = '<div class="tdb-pl-img" ' . ( $img_url != '' ? 'style="background-image:url(' . $img_url . ')"' : '' ) . '></div>';
                            } else if( $custom_field_data['type'] == 'taxonomy' ) {
                                $field_values = $custom_field_data['value'];

                                foreach ( $field_values as $key => $field_value ) {
                                    $term_type = $custom_field_data['taxonomy'];
                                    $term_data = $field_value;
                                    if( is_numeric( $field_value ) ) {
                                        $term_data = get_term_by('term_id', $field_value, $term_type);
                                    }

                                    if( $term_data ) {
                                        $post[$custom_column] .= $term_data->name;

                                        if( $key != array_key_last( $field_values ) ) {
                                            $post[$custom_column] .= ', ';
                                        }
                                    }
                                }
                            } else {
                                $field_value = $custom_field_data['value'];

                                if( is_array( $field_value ) ) {
                                    foreach ( $field_value as $key => $value ) {
                                        if( is_array( $value ) ) {
                                            $post[$custom_column] .= $value['label'];
                                        } else if( td_util::isAssocArray( $field_value ) ) {
                                            if( $key == 'label' ) {
                                                $post[$custom_column] .= $value;
                                            }
                                        } else {
                                            $post[$custom_column] .= $value;
                                        }

                                        if( $key != array_key_last( $field_value ) ) {
                                            $post[$custom_column] .= ', ';
                                        }
                                    }
                                } else {
                                    $post[$custom_column] .= $field_value;
                                }
                            }
                        }
                    }
                }

                $posts[] = $post;
            }
        }

        /* -- Check for filters and apply them -- */
        $search_keyword = '';
        $search_in = 'title';
        $sorted_column_name = '';
        $sorted_column_order = '';

        // create a hierarchical array of parent-child relationships
        $sorted_posts = self::sort_posts( $posts );

        if( !empty($active_filters) ) {

            // Search by keyword
            if( isset( $active_filters['search'] ) ) {
                $search = $active_filters['search'];
                $search_keyword = $search['keyword'];
                $search_in = $search['in'];

                if( $search_keyword != '' ) {

                    $posts = array_filter( $posts, function($post) use ( $search_keyword, $search_in ) {

                        if( $search_in == 'id' ) {
                            return $post['ID'] == $search_keyword;
                        }

                        return stripos( $post[$search_in], $search_keyword ) !== false;

                    });

                    // process search results posts to add parents from sorted posts
                    $search_results_posts = array();
                    foreach ( $posts as $post ) {
                        $parent_posts = self::get_parent_posts( $sorted_posts, $post['ID'] );
                        $search_results_posts = array_merge( $search_results_posts, $parent_posts );
                        $search_results_posts[] = $post;
                    }

                    // sort posts hierarchically
                    $sorted_posts_from_keywords = self::sort_posts( $search_results_posts );
                    $posts = self::build_posts_array( $sorted_posts_from_keywords, td_util::array_key_first( $sorted_posts_from_keywords ) );

                }
            }

            // Sort by column
            if( isset( $active_filters['columnSort'] ) ) {
                $column_sort = $active_filters['columnSort'];
                $column_sort_name = $sorted_column_name = $column_sort['name'];
                $column_sort_order = $sorted_column_order = $column_sort['order'];

                usort($posts, function($a, $b) use ( $search_keyword, $column_sort_name, $column_sort_order) {

                    switch( $column_sort_name ) {
                        case 'id':
                            if( $column_sort_order == 'DESC' ) {
                                return $b['ID'] - $a['ID'];
                            }

                            return $a['ID'] - $b['ID'];

                        case 'overall_rating':
                            if( $column_sort_order == 'DESC' ) {
                                return $b[$column_sort_name] - $a[$column_sort_name];
                            }

                            return $a[$column_sort_name] - $b[$column_sort_name];

                        case 'title':
                        case 'author':
                        case 'source_title':

                            if( $search_keyword != '' ) {

                                // sort only first level (main) parents posts
                                if ( empty($a['depth']) && empty($b['depth']) ) {
                                    if( $column_sort_order == 'DESC' ) {
                                        return strcasecmp($b[$column_sort_name], $a[$column_sort_name]);
                                    }

                                    return strcasecmp($a[$column_sort_name], $b[$column_sort_name]);
                                }

                            } else {
                                if( $column_sort_order == 'DESC' ) {
                                    return strcasecmp($b[$column_sort_name], $a[$column_sort_name]);
                                }

                                return strcasecmp($a[$column_sort_name], $b[$column_sort_name]);
                            }

                        case 'date':
                            if( $column_sort_order == 'DESC' ) {
                                return strtotime($b['publish_date']) - strtotime($a['publish_date']);
                            }

                            return strtotime($a['publish_date']) - strtotime($b['publish_date']);

                    }

                });

                // sort posts hierarchically only after a title/author/source_title sort
                if ( in_array( $column_sort_name, array( 'title', 'author', 'source_title' ) ) ) {
                    $sorted_posts_from_cols = self::sort_posts( $posts );
                    $posts = self::build_posts_array( $sorted_posts_from_cols, td_util::array_key_first( $sorted_posts_from_cols ) );
                }

            }

        } else {

            // build a flat array of posts with depth in the display order
            $posts = self::build_posts_array($sorted_posts, td_util::array_key_first( $sorted_posts ));

        }


        /* -- Apply pagination settings -- */
        $enable_pag = $options['enablePagination'];
        $per_page = $options['perPage'];
        $num_pages = 3;
        $current_page = $options['currentPage'];

        if( !empty( $posts ) ) {
            if( $enable_pag ) {
                $posts_count = count($posts);
                $num_pages = ceil($posts_count / $per_page);

                $offset = ( $current_page - 1 ) * $per_page;

                $posts = array_slice($posts, $offset, $per_page);
            }
        } else {
            if( $is_composer ) {
                for ( $i = 1; $i < 6; $i++ ) {
                    $posts[] = array(
                        'ID' => $i,
                        'featured_image' => TDB_URL . '/assets/images/td_meta_replacement.png',
                        'title' => 'Sample post ' . $i,
                        'author' => 'John Doe',
                        'publish_date' =>  date( get_option( 'date_format' ), time() ),
                        'status' => 'Published',
                    );
                }
            }
        }



        /* --
        -- BUILD THE LIST HTML
        -- */
        if( empty( $columns_to_display ) ) {
            /* -- The user needs to fill in the columns option -- */
            $buffy .= td_util::get_block_error('Posts List', 'You have not selected any <strong>columns</strong> to display.' );
        } else {

            /* -- Render the search input -- */
            $buffy .= '<div class="tdb-s-form tdb-plist-search">';
            $buffy .= '<div class="tdb-s-form-content">';
            $buffy .= '<div class="tdb-s-fc-inner">';
            $buffy .= '<div class="tdb-s-form-group tdb-s-form-group-sm tdb-s-form-group-keyword">';
            $buffy .= '<input type="text" class="tdb-s-form-input tdb-plist-search-keyword" placeholder="Search by keyword..." value="' . $search_keyword . '">';
            $buffy .= '</div>';

            $buffy .= '<div class="tdb-s-form-group tdb-s-form-group-sm tdb-s-form-group-in">';
            $buffy .= '<div class="tdb-s-form-select-wrap">';
            $buffy .= '<select class="tdb-s-form-input tdb-plist-search-in">';
            foreach ( $columns_to_display as $column ) {
                if( $column == 'featured_image' || $column == 'date' || $column == 'overall_rating' ) {
                    continue;
                }

                $buffy .= '<option value="' . $column . '" ' . ( $search_in == $column ? 'selected' : '' ) . '>';
                if( in_array( $column, $predefined_columns ) ) {
                    $buffy .= self::display_column_name($column);
                } else {
                    $buffy .= self::display_custom_column_name($column);
                }
                $buffy .= '</option>';
            }
            $buffy .= '</select>';

            $buffy .= '<svg class="tdb-s-form-select-icon" xmlns="http://www.w3.org/2000/svg" width="8.947" height="12.578" viewBox="0 0 8.947 12.578"><g transform="translate(7.947 1) rotate(90)"><path d="M0,7.947A1,1,0,0,1-.58,7.761,1,1,0,0,1-.815,6.366l2.06-2.893L-.815.58A1,1,0,0,1-.58-.815,1,1,0,0,1,.815-.58L3.288,2.893a1,1,0,0,1,0,1.16L.815,7.527A1,1,0,0,1,0,7.947Z" transform="translate(8.104 0)"/><path d="M2.474,7.947a1,1,0,0,1-.815-.42L-.815,4.053a1,1,0,0,1,0-1.16L1.659-.58A1,1,0,0,1,3.053-.815,1,1,0,0,1,3.288.58L1.228,3.473l2.06,2.893a1,1,0,0,1-.814,1.58Z" transform="translate(0 0)"/></g></svg>';
            $buffy .= '</div>';
            $buffy .= '</div>';

            $buffy .= '<div class="tdb-s-form-group tdb-s-form-group-sm tdb-s-form-group-button">';
            $buffy .= '<button class="tdb-s-btn tdb-s-btn-sm">Search</button>';
            $buffy .= '</div>';
            $buffy .= '</div>';
            $buffy .= '</div>';
            $buffy .= '</div>';


            /* -- Check if we have any posts to display -- */
            if( empty( $posts ) ) {
                $buffy .= '<div class="tdb-s-notif tdb-s-notif-info"><div class="tdb-s-notif-descr">';
                if( $search_keyword != '' ) {
                    $buffy .= __td( 'No search results.', TD_THEME_NAME );
                } else {
                    $buffy .= __td( 'You have not created any posts.', TD_THEME_NAME );
                }
                $buffy .= '</div></div>';
            } else {
                // Posts list
                $buffy .= '<table class="tdb-s-table tdb-s-content">';
                $buffy .= '<thead class="tdb-s-table-header">';
                $buffy .= '<tr class="tdb-s-table-row tdb-s-table-row-h">';
                // Predefined columns headings
                foreach ( $columns_to_display as $column ) {
                    $column_name = '';
                    $column_sortable = false;

                    if( in_array( $column, $predefined_columns ) ) {
                        if( $column != 'featured_image' && $column != 'categories' && $column != 'tags' ) {
                            $column_sortable = true;
                        }

                        $column_name = self::display_column_name($column);
                    } else {
                        $column_name = self::display_custom_column_name($column);
                    }

                    $buffy .= '<th class="tdb-s-table-col" data-column="' . $column . '" ' . ( $column_sortable ? ( $sorted_column_name == $column ? 'data-order="' . $sorted_column_order . '"' : '' ) : '' ) . '>';
                    if( $column_sortable ) {
                        $buffy .= '<div class="tdb-s-table-col-order">';
                    }
                    $buffy .= $column_name;

                    if( $column_sortable ) {
                        $buffy .= $column_order_icons;
                    }
                    if( $column_sortable ) {
                        $buffy .= '</div>';
                    }
                    $buffy .= '</th>';
                }

                $buffy .= '<th class="tdb-s-table-col tdb-s-table-col-options"></th>';
                $buffy .= '</tr>';
                $buffy .= '</thead>';

                $buffy .= '<tbody class="tdb-s-table-body">';
                foreach ($posts as $post) {

                    $buffy .= '<tr class="tdb-s-table-row tdb-plist-post" data-post-id="' . $post['ID'] . '">';
                    // Predefined columns values
                    foreach ( $columns_to_display as $column ) {
                        switch ($column) {
                            case 'id':
                                $buffy .= '<td class="tdb-s-table-col">';
                                $buffy .= '<div class="tdb-s-table-col-label">' . __td( 'ID', TD_THEME_NAME ) . '</div>';
                                $buffy .= '#' . $post['ID'];
                                $buffy .= '</td>';
                                break;

                            case 'overall_rating':
                                $buffy .= '<td class="tdb-s-table-col">';
                                $buffy .= '<div class="tdb-s-table-col-label">' . __td( 'Rating', TD_THEME_NAME ) . '</div>';

                                if( $post['overall_rating'] ) {
                                    $buffy .= self::display_rating_stars( $post['overall_rating'], $full_star_icon_html, $full_star_icon_data, $half_star_icon_html, $half_star_icon_data, $empty_star_icon_html, $empty_star_icon_data );
                                } else {
                                    $buffy .= __td( 'No rating', TD_THEME_NAME );
                                }

                                $buffy .= '</td>';
                                break;

                            case 'title':
                                $buffy .= '<td class="tdb-s-table-col tdb-s-table-col-title">';
                                $buffy .= '<div class="tdb-s-table-col-label">' . __td( 'Title', TD_THEME_NAME ) . '</div>';
                                $buffy .= $post['title'];
                                if( $post['status'] != 'Published' ) {
                                    $buffy .= '<span class="tdb-plist-title-status"> (' . $post['status'] . ')</span>';
                                }
                                $buffy .= '</td>';
                                break;

                            case 'featured_image':
                                $buffy .= '<td class="tdb-s-table-col">';
                                $buffy .= '<div class="tdb-s-table-col-label">' . __td( 'Post image', TD_THEME_NAME ) . '</div>';
                                $buffy .= '<div class="tdb-pl-img" ' . ( $post['featured_image'] != '' ? 'style="background-image:url(' . $post['featured_image'] . ')"' : '' ) . '></div>';
                                $buffy .= '</td>';
                                break;

                            case 'categories':
                                $buffy .= '<td class="tdb-s-table-col tdb-s-table-col-terms">';
                                $buffy .= '<div class="tdb-s-table-col-label">' . __td( 'Categories', TD_THEME_NAME ) . '</div>';
                                $buffy .= $post['categories'];
                                $buffy .= '</td>';
                                break;

                            case 'tags':
                                $buffy .= '<td class="tdb-s-table-col tdb-s-table-col-terms">';
                                $buffy .= '<div class="tdb-s-table-col-label">' . __td( 'Tags', TD_THEME_NAME ) . '</div>';
                                $buffy .= $post['tags'];
                                $buffy .= '</td>';
                                break;

                            case 'date':
                                $buffy .= '<td class="tdb-s-table-col">';
                                $buffy .= '<div class="tdb-s-table-col-label">' . __td( 'Date', TD_THEME_NAME ) . '</div>';
                                $buffy .= $post['publish_date'];
                                $buffy .= '</td>';
                                break;

                            case 'author':
                                $buffy .= '<td class="tdb-s-table-col">';
                                $buffy .= '<div class="tdb-s-table-col-label">' . __td( 'Author', TD_THEME_NAME ) . '</div>';
                                $buffy .= '<a href="' . $post['author_url'] . '">' . $post['author'] . '</a>';
                                $buffy .= '</td>';
                                break;

                            case 'source_title':
                                $buffy .= '<td class="tdb-s-table-col">';
                                $buffy .= '<div class="tdb-s-table-col-label">' . __td( 'Source title', TD_THEME_NAME ) . '</div>';
                                $buffy .= !empty($post['source_title']) ? '<a href="' . $post['source_url'] . '">' . $post['source_title'] . '</a>' : '';
                                $buffy .= '</td>';
                                break;
                        }
                    }

                    // Custom columns values
                    foreach ( $custom_columns as $custom_column ) {
                        $buffy .= '<td class="tdb-s-table-col">';
                        $buffy .= '<div class="tdb-s-table-col-label">' . self::display_custom_column_name($custom_column) . '</div>';
                        $buffy .= !empty($post[$custom_column]) ? $post[$custom_column] : '';
                        $buffy .= '</td>';
                    }

                    // Options list
                    $buffy .= '<td class="tdb-s-table-col tdb-s-table-col-options">';
                    $buffy .= '<svg class="tdb-s-table-options-toggle" xmlns="http://www.w3.org/2000/svg" width="4.001" height="16" viewBox="0 0 4.001 16"><g transform="translate(-1210.999 -335)"><path d="M-10898,14a2,2,0,0,1,2-2,2,2,0,0,1,2,2,2,2,0,0,1-2,2A2,2,0,0,1-10898,14Zm0-6a2,2,0,0,1,2-2,2,2,0,0,1,2,2,2,2,0,0,1-2,2A2,2,0,0,1-10898,8Zm0-6a2,2,0,0,1,2-2,2,2,0,0,1,2,2,2,2,0,0,1-2,2A2,2,0,0,1-10898,2Z" transform="translate(12109 335)"/></g></svg>';

                    $buffy .= '<div class="tdb-s-table-options-list">';
                    $buffy .= '<a class="tdb-s-tol-item" href="' . esc_url(get_permalink($post['ID'])) . '" target="blank">' . __td( 'View', TD_THEME_NAME ) . '</a>';

                    if( $main_form_url != '' || $extra_form_1_url != '' || $extra_form_2_url != '' || $child1_form_url != '' || $child2_form_url != '' ) {
//                                            $buffy .= '<div class="tds-s-tol-sep"></div>';

                        $parent_id = wp_get_post_parent_id( $post['ID'] );
                        $parent_post = false;
                        $post_level = 0;

                        if ( $parent_id ) {
                            $post_level = count( get_post_ancestors( $post['ID'] ) ) + 1;
                        } else {
                            $parent_post = true;
                        }

                        if ( $parent_post ) {
                            if ($main_form_url != '') {
                                $buffy .= '<div class="tds-s-tol-sep"></div>';
                                $buffy .= '<a class="tdb-s-tol-item" href="' . esc_url(add_query_arg('post_id', $post['ID'], $main_form_url)) . '">' . $main_form_edit_txt . '</a>';
                            }
                        }
                        if( $extra_form_1_url != '' ) {
                            $buffy .= '<div class="tds-s-tol-sep"></div>';
                            $buffy .= '<a class="tdb-s-tol-item" href="' . esc_url(add_query_arg('post_id', $post['ID'], $extra_form_1_url) ) . '">' . $extra_form_1_edit_txt . '</a>';
                        }
                        if( $extra_form_2_url != '' ) {
                            $buffy .= '<div class="tds-s-tol-sep"></div>';
                            $buffy .= '<a class="tdb-s-tol-item" href="' . esc_url(add_query_arg('post_id', $post['ID'], $extra_form_2_url) ) . '">' . $extra_form_2_edit_txt . '</a>';
                        }
                        if( $child1_form_url != '' || $child2_form_url != '' ) {
                            if( $child1_form_url != '' && $post_level == 2 ) {
                                $buffy .= '<div class="tds-s-tol-sep"></div>';
                                $buffy .= '<a class="tdb-s-tol-item" href="' . esc_url(add_query_arg('post_id', $post['ID'], $child1_form_url) ) . '">' . $child1_form_edit_txt . '</a>';
                            }
                            if( $child2_form_url != '' && $post_level > 2 ) {
                                $buffy .= '<div class="tds-s-tol-sep"></div>';
                                $buffy .= '<a class="tdb-s-tol-item" href="' . esc_url(add_query_arg('post_id', $post['ID'], $child2_form_url) ) . '">' . $child2_form_edit_txt . '</a>';
                            }
                        }
                    }


                    if( ( $is_current_user_admin || $options['allowPublish'] || $options['allowPublish'] === 'true' ) &&
                        ( $post['status'] == 'Pending' || $post['status'] == 'Draft' || $post['status'] == 'Private' )
                    ) {
                        $buffy .= '<div class="tds-s-tol-sep"></div>';
                        $buffy .= '<a class="tdb-s-tol-item tdb-plist-publish-post" href="#" data-post-id="' . $post['ID'] . '" data-post-title="' . $post['title'] . '">' . __td( 'Publish', TD_THEME_NAME ) . '</a>';
                    }

                    if( $is_current_user_admin || $options['allowDelete'] || $options['allowDelete'] === 'true' ) {
                        $buffy .= '<div class="tds-s-tol-sep"></div>';
                        $buffy .= '<a class="tdb-s-tol-item tdb-s-tol-item-red tdb-plist-delete-post" data-post-id="' . $post['ID'] . '" data-post-title="' . $post['title'] . '" href="#">' . __td( 'Delete', TD_THEME_NAME ) . '</a>';
                    }
                    $buffy .= '</div>';
                    $buffy .= '</td>';
                    $buffy .= '</tr>';
                }
                $buffy .= '</tbody>';
                $buffy .= '</table>';

                // Pagination
                if( $enable_pag != '' ) {
                    $buffy .= tdc_util::get_custom_pagination(
                        $current_page,
                        $num_pages,
                        'tdb_posts_list_page',
                        3,
                        array(
                            'wrapper' => 'tdb-s-pagination',
                            'item' => 'tdb-s-pagination-item',
                            'active' => 'tdb-s-pagination-active',
                            'dots' => 'tdb-s-pagination-dots'
                        )
                    );
                }
            }

        }


        /* -- Render the add new post button -- */
        if( $main_form_url != '' ) {
            if( $is_current_user_admin || !$options['addNewPostLimitReached'] ) {
                $buffy .= '<a class="tdb-s-btn tdb-plst-add" href="' . esc_url($main_form_url) . '">' . $main_form_add_txt . '</a>';
            } else {
                $buffy .= $limit_notif;
            }
        }


        return $buffy;

    }

    static function sort_posts( $posts ) {

        $sorted_posts_array = array();

        foreach ( $posts as $post ) {

            $parent_id = 0;

            // get parent meta
            $parent_post_id_meta = get_post_meta( $post['ID'], 'tdc-parent-post-id', true );

            // set parent from meta
            if( !empty( $parent_post_id_meta ) ) {
                $parent_id = $parent_post_id_meta;

                // set parent from post data
            } elseif ( !empty( $post['parent'] ) ) {
                $parent_id = $post['parent'];
            }

            if ( !isset($sorted_posts_array[$parent_id]) ) {
                $sorted_posts_array[$parent_id] = array();
            }

            $sorted_posts_array[$parent_id][$post['ID']] = $post;

        }

        return $sorted_posts_array;

    }

    static function build_posts_array( $posts, $parent_id, $depth = 0, &$result = array() ) {

        if ( isset($posts[$parent_id]) && is_array($posts[$parent_id]) ) {
            foreach ($posts[$parent_id] as $post_id => $post) {
                $post['depth'] = $depth;

                $posts_title_prefix = str_repeat( '-', $depth );
                if( $posts_title_prefix != '' ) {
                    $posts_title_prefix .= ' ';
                }
                $post['title'] = !empty( $posts_title_prefix ) ? $posts_title_prefix . $post['title'] : $post['title'];

                $result[] = $post;
                self::build_posts_array( $posts, $post_id, $depth + 1, $result );
            }
        }

        return $result;

    }

    /* ---------
	---- FORMAT PREDEFINED COLUMN NAME
	--------- */
    static function display_column_name( $column ) {

        $column_name = '';

        switch ( $column ) {
            case 'id':
                $column_name = __td( 'ID', TD_THEME_NAME );
                break;

            case 'overall_rating':
                $column_name = __td( 'Rating', TD_THEME_NAME );
                break;

            case 'title':
                $column_name = __td( 'Title', TD_THEME_NAME );
                break;

            case 'featured_image':
                $column_name = __td( 'Post image', TD_THEME_NAME );
                break;

            case 'categories':
                $column_name = __td( 'Categories', TD_THEME_NAME );
                break;

            case 'tags':
                $column_name = __td( 'Tags', TD_THEME_NAME );
                break;

            case 'date':
                $column_name = __td( 'Date', TD_THEME_NAME );
                break;

            case 'author':
                $column_name = __td( 'Author', TD_THEME_NAME );
                break;

            case 'source_title':
                $column_name = __td( 'Sources title', TD_THEME_NAME );
                break;
        }

        return $column_name;

    }

}