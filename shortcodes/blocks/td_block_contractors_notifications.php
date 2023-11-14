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

//        $contractors_selected_job_categories = get_user_meta(get_current_user_id(), 'tdcwn_job_cat_selected', true);
//
//        if (empty($contractors_selected_job_categories)) {
//            echo 'You have to select the categories you want to subscribe to, in order to see the notifications.'
//        }

        global $wpdb;
        $all_notifications_1 = $wpdb->get_results(
            "SELECT * FROM " .$wpdb->prefix . $this->notifications_table . " ORDER BY `id` DESC"
        );

        $all_notifications = array();
        foreach ($all_notifications_1 as $notification) {
//            echo '<pre>';
//                print_r($notification);
//            echo '</pre>';
//            echo $notification->job_id;
            $job_categories = get_the_terms($notification->job_id, 'job-category');
            if ( false ==  $job_categories ) {
                $job_categories = array();
            }
            $contractors_selected_job_categories = get_user_meta(get_current_user_id(), 'tdcwn_job_cat_selected', true);
            if (empty($contractors_selected_job_categories)) {
                $contractors_selected_job_categories = array();
            }
//            echo '<pre>';
//                print_r($contractors_selected_job_categories);
//            echo '</pre>';

//            echo '<pre><div>$job_categories</div>';
//            print_r($job_categories);
//            echo '</pre>';

            $termIds = array_map(function($term) {
                return $term->term_id;
            }, $job_categories);

//            echo '<pre>';
//            print_r($termIds);
//            echo '</pre>';

//            $result=array_intersect($a1,$a2);

            if (!empty(array_intersect($contractors_selected_job_categories, $termIds))) {
                $all_notifications[] = $notification;
            }
        }

        // Define the number of notifications per page
        $notifications_per_page = 10;

        // Calculate the total number of pages
        $total_pages = ceil(count($all_notifications) / $notifications_per_page);




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
       .action_contact_the_client:before {
           font-family: "Font Awesome 6 Free";
           content: "\f0e0";
           display: inline-block;
           padding-right: 3px;
           vertical-align: middle;
           font-weight: 400;
           font-size: 32px;
           margin-right: 5px;
        }
        .action_contact_the_client {
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
//        $buffy .= '<br />';
//        echo '<pre>';
//        print_r($all_notifications);
//        echo '</pre>';
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
                        <?php
                        $the_job = get_post($notification->job_id);
//                        echo '<pre>';
//                            print_r($the_job);
//                        echo '</pre>';
                        echo $the_job->post_content;
                        ?>
                        <div>
                            <a href="<?php echo get_home_url(); ?>/my-account/?messages&chat=0&c=<?php echo $notification->client_id; ?>&j=<?php echo $notification->job_id; ?>" class="action_contact_the_client" title="Contact the client">Contact the client</a>
                            <!--                                <span class="action_contact_the_client c---><?php //echo $notification->client_id; ?><!-- j---><?php //echo $notification->job_id ?><!--" id="action_contact-the-client">Contact the client</span>-->
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $buffy .= ob_get_clean();
        }


        // Start the output buffer
        ob_start();
        ?>

        <div id="notification-container">
            <!-- Notifications will be dynamically loaded here -->
        </div>

        <div id="pagination-links" class="tdcwn_notifications_pagination_links">
            <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                <a href="#" class="<?php echo $page == 1 ? 'active' : ''; ?>" data-page="<?php echo $page; ?>" onclick="changePage(<?php echo $page; ?>); return false;">
                    <?php echo $page; ?>
                </a>
            <?php endfor; ?>
        </div>

        <script>
            function changePage(page) {
                // Save the current page to localStorage
                localStorage.setItem('current_page', page);

                // Hide all notifications
                document.querySelectorAll('.tdcwn_notification').forEach(function(item) {
                    item.style.display = 'none';
                });

                // Calculate the index range of notifications to show
                var start = (page - 1) * <?php echo $notifications_per_page; ?>;
                var end = start + <?php echo $notifications_per_page; ?>;

                // Show the notifications for the current page
                var currentItems = Array.from(document.querySelectorAll('.tdcwn_notification')).slice(start, end);
                currentItems.forEach(function(item) {
                    item.style.display = 'block';
                });

                // Update the active class on the pagination links
                document.querySelectorAll('#pagination-links a').forEach(function(link) {
                    link.classList.remove('active');
                });
                document.querySelector('#pagination-links a[data-page="' + page + '"]').classList.add('active');
            }

            // Load the initial page from localStorage or default to the first page
            document.addEventListener('DOMContentLoaded', function() {
                var currentPage = localStorage.getItem('current_page') || 1;
                changePage(currentPage);
            });

            // Load the initial page from localStorage or default to the first page
            document.addEventListener('DOMContentLoaded', function() {
                var currentPage = localStorage.getItem('current_page') || 1;
                changePage(currentPage);
            });
        </script>

        <?php
        // Get the buffer content
        $buffy .= ob_get_clean();



        $buffy .= '</div>';

//        $buffy .= '
//           <script>
//            jQuery(".action_contact_the_client").on("click", function () {
//                var classString = this.classList;
//                var classString = "action_contact_the_client c-9 j-10218"; // Replace this with your actual class string
//
//                // Split the class string into an array of individual classes
//                var classes = classString.split(" ");
//
//                // Initialize variables to store the extracted values
//                var value1 = null;
//                var value2 = null;
//
//                // Iterate through the classes and extract the values
//                for (var i = 0; i < classes.length; i++) {
//                    var classParts = classes[i].split("-");
//                    if (classParts.length === 2) {
//                        var prefix = classParts[0];
//                        var number = parseInt(classParts[1]);
//
//                        if (!isNaN(number)) {
//                            if (prefix === "c") {
//                                value1 = number;
//                            } else if (prefix === "j") {
//                                value2 = number;
//                            }
//                        }
//                    }
//                }
//
//                console.log("Value 1:", value1); // Output: 7
//                console.log("Value 2:", value2); // Output: 1028
//
//            });
//           </script>
//        ';

        return $buffy;
    }

}