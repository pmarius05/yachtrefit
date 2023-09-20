<?php

class td_block_contractors_notifications extends td_block {

    var $messages_table;
    var $notifications_table;

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

        $this->messages_table = 'tdcwn_messages';
        $this->notifications_table = 'tdcwn_notifications';
    }


    function render( $atts, $content = null ) {
        parent::render( $atts ); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        // flag to check if we are in composer
        $is_composer = false;
        if( td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax() ) {
            $is_composer = true;
        }

        global $wpdb;
        $all_notifications = $wpdb->get_results(
            "SELECT * FROM " .$wpdb->prefix . $this->notifications_table
        );



        $buffy = ''; //output buffer
        $buffy .=
            '
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
        .tdcwn_notification {
            border: 1px solid #c4c4c4;
            margin: -1px 0 0 0;
        }
        .tdcwn_notification_title {
            background: #F9F9F9;
            borer: 1px solid #c4c4c4;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            cursor: pointer;
        }
        .tdcwn_notification_message {
            color: #000;
            font-weight: 600;
        }
       .action_contact-the-client:before {
           font-family: "Font Awesome 6 Free";
           content: "\f0e0";
           display: inline-block;
           padding-right: 3px;
           vertical-align: middle;
           font-weight: 400;
           font-size: 32px;
           margin-right: 5px;
        }
        .action_contact-the-client {
            font-weight: 600;
            font-size: 16px;
            display: inline-block;
            margin: 20px 10px 10px 10px;
        }
        .action_open-details svg {
            position: relative;
            top: 5px;
            margin-right: 20px;
            cursor: pointer;
        }
        .tdcwn_notification_details {
            border: 1px solid #F9F9F9;
            background: #F9F9F9;
        }
        .tdcwn_notification_details .inner {
            padding: 20px 40px;
            box-sizing: border-box;
        }

        </style>
        ';

        $buffy .= "<div class=" . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';

        //get the block css
        $buffy .= $this->get_block_css();

        //get the js for this block
        $buffy .= $this->get_block_js();

//        $buffy .= 'This is buffy Cn!';
        $buffy .= '<br />';
//        var_dump($all_notifications);
        foreach ($all_notifications as $notification) {
            ob_start();
            ?>

                <div class="tdcwn_notification">
                    <div class="tdcwn_notification_title accordion">
                        <div class="tdcwn_notification_message">
                            <span class="action_open-details" title="Show details">
                                <svg xmlns="http://www.w3.org/2000/svg" height="1.5em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>
                            </span>
                            <?php echo $notification->message; ?>
                        </div>
                        <div class="tdcwn_notification_actions">


                        </div>
                    </div>
                    <div class="tdcwn_notification_details panel">
                        <div class="inner">
                            <p>
                                <strong>Job details</strong>
                            </p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum luctus odio eget mauris condimentum, ut aliquam eros mollis. Curabitur porttitor tincidunt augue vitae pulvinar. Aenean aliquam justo tortor, non tempus eros efficitur id. Praesent quis rutrum libero. Proin vel urna in augue malesuada sodales in quis lectus. Cras sollicitudin a arcu eu iaculis. Aliquam erat volutpat. Nulla quis purus sed massa commodo eleifend.
                            <div>
                                <a href="<?php echo get_home_url(); ?>/tds-my-account/?messages&chat=1" class="action_contact-the-client" title="Contact the client">Contact the client</a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php
            $buffy .= ob_get_clean();
        }



        $buffy .= '</div>';

        return $buffy;
    }

}