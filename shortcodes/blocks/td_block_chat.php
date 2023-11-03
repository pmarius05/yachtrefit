<?php

class td_block_chat extends td_block {

    var $messages_table;
    var $messages_index_table;
    var $plugin_path;
    var $plugin_url;

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

        $this->plugin_url = plugins_url('', __FILE__); // path used for elements like images, css, etc which are available on end user
        $this->plugin_path = dirname(__FILE__); // used for internal (server side) files

        global $wpdb;

        $this->messages_table = $wpdb->prefix . 'tdcwn_messages';
        $this->messages_index_table = $wpdb->prefix . 'tdcwn_messages_index';

        add_action('before_my_script_enqueue', array( $this, 'localize_my_script'));
    }

    function display_attachment_by_id($attachment_id) {
        // Check if it's a valid attachment
        if (get_post_type($attachment_id) !== 'attachment') {
            return '';
        }

        $mime_type = get_post_mime_type($attachment_id);
        $media_url = wp_get_attachment_url($attachment_id);

        // Image
        if (strpos($mime_type, 'image') !== false) {
            return '<a href="'.get_attachment_link($attachment_id).'" target="_blank">' . wp_get_attachment_image($attachment_id, 'thumbnail').'</a>';
//            return;
        }

        // Audio
        if (strpos($mime_type, 'audio') !== false) {
            echo '<audio controls>
                <source src="' . esc_url($media_url) . '" type="' . esc_attr($mime_type) . '">
                Your browser does not support the audio element.
              </audio>';
            return;
        }

        // Video
        if (strpos($mime_type, 'video') !== false) {
            echo '<video width="320" height="240" controls>
                <source src="' . esc_url($media_url) . '" type="' . esc_attr($mime_type) . '">
                Your browser does not support the video tag.
              </video>';
            return;
        }

        // Other file types
        return '<a href="' . esc_url($media_url) . '" class="download_link" target="_blank"><span class="download-tag">(Download)</span> ' . basename($media_url) . '</a>';
    }

    function sanitize_and_validate_file($file) {
        // Check file size (e.g., 5MB limit)
//        if ($file['size'] > 5 * 1024 * 1024) {
//            return array('error' => 'File size is too large.');
//        }

        // Check file type and MIME type
        $file_type = wp_check_filetype($file['name']);
        $allowed_file_types = array(
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            // Added document types
            'pdf' => 'application/pdf',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        );

        if (!array_key_exists($file_type['ext'], $allowed_file_types) || $file_type['type'] != $allowed_file_types[$file_type['ext']]) {
            return array('error' => 'Invalid file type. Allowed files: images (.jpg, .jpeg, .png, .gif), PDF, Excel (XLS, XLSX), Word documents (DOC, DOCX).');
        }

        // Rename the file for sanitation
        $file['name'] = sanitize_file_name($file['name']);

        // Image-specific processing
        if (in_array($file_type['type'], array('image/jpeg', 'image/png', 'image/gif'))) {
            // Check for PHP content in the image
            $content = file_get_contents($file['tmp_name']);
            if (strpos($content, '<?php') !== false) {
                return array('error' => 'File contains PHP content.');
            }

            // Re-save the image using a trusted library to strip out malicious content
            $editor = wp_get_image_editor($file['tmp_name']);
            if (!is_wp_error($editor)) {
                $editor->save($file['tmp_name']);
            } else {
                return array('error' => 'Failed to process the image.');
            }
        }

        // If it's a document, no image-specific processing is required
        // Additional security checks for documents could be implemented here if necessary

        return $file;
    }

    function sanitize_and_validate_image($file) {
        // Check file size (e.g., 5MB limit)
        if ($file['size'] > 5 * 1024 * 1024) {
            return array('error' => 'File size is too large.');
        }

        // Check file type and MIME type
        $file_type = wp_check_filetype($file['name']);
        $allowed_file_types = array(
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        );

        if (!array_key_exists($file_type['ext'], $allowed_file_types) || $file_type['type'] != $allowed_file_types[$file_type['ext']]) {
            return array('error' => 'Invalid file type. Allowed files: images (.jpg, .jpeg, .png), PDF, Excel (XLS, XLSX), Word documents (DOC, DOCX).');
        }

        // Rename the file for sanitation
        $file['name'] = sanitize_file_name($file['name']);

        // Check for PHP content in the image
        $content = file_get_contents($file['tmp_name']);
        if (strpos($content, '<?php') !== false) {
            return array('error' => 'File contains PHP content.');
        }

        // Re-save the image using a trusted library to strip out malicious content
        $editor = wp_get_image_editor($file['tmp_name']);
        if (!is_wp_error($editor)) {
            $editor->save($file['tmp_name']);
        } else {
            return array('error' => 'Failed to process the image.');
        }

        return $file;
    }

    function get_current_user_role() {
        if ( is_user_logged_in() ) {
            $user = wp_get_current_user();
            $roles = ( array ) $user->roles;
            return implode(', ', $roles); // This will return a string if the user has multiple roles.
        } else {
            return null; // Or false, or an empty string, depending on how you want to handle this.
        }
    }


    function allowed_user($id)
    {
        $current_user = $id;
        if ( 'td_team_member' ==  $this->get_current_user_role()) {
            $the_team_leader = get_user_meta(get_current_user_id(), 'tdcwn_team', true );

//            echo '<pre>';
//            print_r($the_team_leader);
//            echo '</pre>';

            $the_team_leader_team_list = get_user_meta($the_team_leader, 'tdcwn_team', true);

//            echo '<pre>';
//                print_r($the_team_leader_team_list);
//            echo '</pre>';

            if (in_array($current_user, $the_team_leader_team_list)) {
                return true;
            } else {
                return false;
            }

        }elseif ( 'td_client_role' == $this->get_current_user_role() ) {
            return true;
        }elseif ( 'td_contractor_role' == $this->get_current_user_role() ) {
            return true;
        }else {
            return false;
        }
        return false;
    }




    function render( $atts, $content = null )
    {
        parent::render($atts); // sets the live atts, $this->atts, $this->block_uid, $this->td_query (it runs the query)

        // flag to check if we are in composer
        $is_composer = false;
        if (td_util::tdc_is_live_editor_iframe() || td_util::tdc_is_live_editor_ajax()) {
            $is_composer = true;
        }


        global $wpdb;
//        $wpdb->query(
//            $wpdb->prepare(
//                    "
//                    SELECT * FROM " .$wpdb->prefix . $this->messages_table . "
//                    WHERE chat_id
//                    "
//            )
//        );


        $current_user_id = get_current_user_id();
        if ($this->allowed_user($current_user_id)) {

            if (isset($_GET['chat']) && ctype_alnum($_GET['chat'])) {
//        if ( isset($_GET['chat']) ) {

                $chat_id = $_GET['chat'];

                if (0 == $chat_id) {

                    $client_id = (int)$_GET['c'];
                    $job_id = (int)$_GET['j'];

                    $chat_hex = md5(rand());

                    // Check to see if there already is a conversation between the current user and the other user for the
                    // specified job
                    $existing_chat = $wpdb->get_var("
                    SELECT `chat_id` 
                    FROM `$this->messages_index_table`
                    WHERE `job_id`='$job_id' 
                      AND `client_id`='$client_id' 
                      AND `contractor_id`='$current_user_id'
                    LIMIT 1
                ");

                    if (isset($existing_chat)) {
                        $url = home_url() . '/my-account/?messages&chat=' . $existing_chat;
                        echo("<script>location.href = '" . $url . "'</script>");
                    } else {
                        $wpdb->query(
                            "
                        INSERT INTO `wp_tdcwn_messages_index` 
                            (`id`, `job_id`, `chat_id`, `contractor_id`, `client_id`, `public`) 
                        VALUES 
                           (NULL, '$job_id', '$chat_hex', '$current_user_id', '$client_id', '1');
                    "
                        );

                        $url = home_url() . '/my-account/?messages&chat=' . $chat_hex;
                        echo("<script>location.href = '" . $url . "'</script>");
                    }


                } elseif (isset($chat_id) && $chat_id != 0) {

                    $chat_id = $_GET['chat'];
//echo $chat_id;
                    $other_user_query = $wpdb->get_results("SELECT * FROM `$this->messages_index_table` WHERE `chat_id`='$chat_id'");

//                echo '<pre>';
//                    print_r($other_user_query);
//                echo '</pre>';
//                echo $current_user_id;

                    if ($current_user_id == $other_user_query[0]->client_id) {
                        $other_user = $other_user_query[0]->contractor_id;
                    } else {
                        $other_user = $other_user_query[0]->client_id;
                    }

//                var_dump($other_user);

                    if (isset($_POST['submit'])) {

                        $message = wp_kses_post($_POST['tnm']);

                        if (isset($_FILES['file_to_upload']) && (0 != $_FILES['file_to_upload']['size']) ) {
                            require_once(ABSPATH . 'wp-admin/includes/file.php');
                            $uploadedfile = $_FILES['file_to_upload'];

                            $result = $this->sanitize_and_validate_file($uploadedfile);

                            if ($result['error']) {
//                            echo "Error: " . $result['error'];
                                echo '<div class="tdcwn_error_message">
                                    <span class="tdcwn_error_message-header">
                                        Error:
                                    </span>
                                    <span class="tdcwn_error_message-body">
                                        ' . $result['error'] . '
                                    </span>
                                    </div>';
                            } else {

                                $upload_overrides = array(
                                    'test_form' => false,
                                );

                                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                                if ($movefile && !isset($movefile['error'])) {

                                    $filetype = wp_check_filetype(basename($movefile['file']), null);

                                    $attachment = array(
                                        'guid' => $movefile['url'],
                                        'post_mime_type' => $filetype['type'],
                                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($movefile['file'])),
                                        'post_content' => '',
                                        'post_status' => 'inherit'
                                    );

                                    // Insert the attachment into the media library
                                    $attach_id = wp_insert_attachment($attachment, $movefile['file']);

                                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it
                                    require_once(ABSPATH . 'wp-admin/includes/image.php');

                                    // Generate the metadata for the attachment, and update the database record
                                    $attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
                                    wp_update_attachment_metadata($attach_id, $attach_data);

                                    //                            echo "File has been uploaded and its attachment ID is " . $attach_id;

                                } else {
                                    echo $movefile['error'];
                                }
                            }
                        }

                        if (!isset($attach_id)) $attach_id = 0;

                        global $wpdb;
                        $wpdb->query(
                            "
                INSERT INTO `wp_tdcwn_messages` 
                    (`id`, `chat_id`, `user_from`, `user_to`, `message`, `file_id`, `timestamp`) 
                    VALUES 
                    (NULL, '$chat_id', '$current_user_id', '$other_user', '$message', '$attach_id' , current_timestamp());
                "
                        );
                    }

                    $get_chat_messages = $wpdb->get_results("
                    SELECT * FROM `$this->messages_table` WHERE chat_id = '$chat_id' ORDER BY `id` DESC
                ");


                    $current_conversation_id = $wpdb->get_var("
                    SELECT id FROM `$this->messages_index_table` WHERE `chat_id`='$chat_id'
                ");

                } else {
                    $get_chat_messages = array('message' => 'Select a conversation');
                }
            }

            global $tdcwnUtil;

            $the_team = $tdcwnUtil->get_the_team($current_user_id);
            $quoted_array = array_map(function($item){
                return "'".$item."'";
            }, $the_team);
            $the_team_csv = implode(',', $quoted_array);
//            echo '<pre>';
//                print_r($the_team);
//            echo '</pre>';

            $get_the_conversations = $wpdb->get_results("SELECT * FROM `$this->messages_index_table` 
                                                        WHERE `client_id` IN ($the_team_csv)
                                                        OR `contractor_id`IN ($the_team_csv)     
                                                    ");
        }
//        echo '<pre>';
//        var_dump($get_the_conversations);
//        echo '</pre>';
        $buffy = ''; //output buffer

        $buffy .= "
        <style>
        
        

        </style>
        ";

        $buffy .= '<div class="' . $this->get_block_classes() . '" ' . $this->get_block_html_atts() . '>';

        //get the block css
        $buffy .= $this->get_block_css();

        //get the js for this block
        $buffy .= $this->get_block_js();

//        $buffy .= 'This is buffy!';

        ob_start();
        ?>


        <!--        <link rel="stylesheet" href="--><?php //echo $this->plugin_url;?><!--../assets/css/messaging-system.css">-->
<!--        <link rel="stylesheet" href="--><?php //echo esc_url( untrailingslashit($this->plugin_url) . '/../assets/css/messaging-system.css' ); ?><!--">-->



        <div id="frame">
            <div id="sidepanel">
                <div id="profile">
                    <div class="wrap">
                        <p>My conversations</p>
                        <div id="status-options">
                            <ul>
                                <li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>
                                <li id="status-away"><span class="status-circle"></span> <p>Away</p></li>
                                <li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>
                                <li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>
                            </ul>
                        </div>
                        <div id="expanded">
                            <label for="twitter"><i class="fa fa-facebook fa-fw" aria-hidden="true"></i></label>
                            <input name="twitter" type="text" value="mikeross" />
                            <label for="twitter"><i class="fa fa-twitter fa-fw" aria-hidden="true"></i></label>
                            <input name="twitter" type="text" value="ross81" />
                            <label for="twitter"><i class="fa fa-instagram fa-fw" aria-hidden="true"></i></label>
                            <input name="twitter" type="text" value="mike.ross" />
                        </div>
                    </div>
                </div>
                <!--                <div id="search">-->
                <!--                    <label for=""><i class="fa fa-search" aria-hidden="true"></i></label>-->
                <!--                    <input type="text" placeholder="Search contacts..." />-->
                <!--                </div>-->
                <div id="contacts">
                    <ul>
                        <?php
                        global $wp;
                        foreach ($get_the_conversations as $conversation) {
                            $active_conversation_class = '';
                            if (isset($current_conversation_id) && $conversation->id == $current_conversation_id) $active_conversation_class = 'active';
                            echo '
                                    <li class="contact '. $active_conversation_class .'">
                                        <a href="'.home_url($wp->request).'/?messages&chat='.$conversation->chat_id.'" class="for-mobile">
                                            <span class="name">#' . $conversation->id .  ' </span>
                                        </a>
                                        
                                        <a href="'.home_url($wp->request).'/?messages&chat='.$conversation->chat_id.'">
                                            <div class="wrap">
                                                <div class="meta">
                                                    <p class="name">Conversation #' . $conversation->id .  ' </p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                ';
                        }
                        ?>


                    </ul>
                </div>
                <div id="bottom-bar">
                    <!--                    <button id="addcontact"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i> <span>Add contact</span></button>-->
                    <!--                    <button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> <span>Settings</span></button>-->
                </div>
            </div>
            <div class="content">
                <div class="contact-profile">
                    <div>
                        <!--                        <img src="http://emilcarlsson.se/assets/harveyspecter.png" alt="" />-->
                        <p>Conversation #<?php if(isset($current_conversation_id)) echo $current_conversation_id; ?></p>
                    </div>
                    <!--                    <div class="social-media">-->
                    <!--                        <i class="fa fa-facebook" aria-hidden="true"></i>-->
                    <!--                        <i class="fa fa-twitter" aria-hidden="true"></i>-->
                    <!--                        <i class="fa fa-instagram" aria-hidden="true"></i>-->
                    <!--                    </div>-->
                    <div class="tdcwn-message-success">
                        <span>
                            The message was successfully sent!
                        </span>
                    </div>
                </div>
                <div class="messages">
                    <ul>
                        <?php
//                        echo '<pre>';
//                        print_r($get_chat_messages);
//                        echo '</pre>';
                        if (isset($get_chat_messages)) {
                            foreach ($get_chat_messages as $the_message) {
                                $css_class = '';
                                if (in_array($the_message->user_from, $the_team)) {
                                    $css_class = 'sent';
                                }else {
                                    $css_class = 'replies';
                                }
                                $message_to_display = (strlen($the_message->message) > 0 ) ? '<span>'.$the_message->message.'</span>' : '';
                                if ( strlen($message_to_display) > 0 ) :
                                    echo '
                                        <li class=" ' . $css_class . ' ">
                                            '. $this->display_attachment_by_id($the_message->file_id) .
                                                $message_to_display
                                            . '<span class="closed toggle-arrow"></span>
                                        </li>
                                        ';
                                endif;
//                                echo $the_message->file_id;
                                if (0 != ($the_message->file_id)) :
                                    echo '
                                        <li class=" ' . $css_class . ' ">
                                            '. $this->display_attachment_by_id($the_message->file_id)
                                        . '<span class="closed toggle-arrow"></span>
                                        </li>
                                        ';
                                endif;
                            }
                        }
                        ?>

                    </ul>
                </div>
                <div class="message-input">

                    <div class="wrap" id="send-message" style="display: none">

                        <div class="textarea-pop">
                            <?php (isset($chat_id)) ? $chat_id : $chat_id = ''; ?>
                            <form method="POST" action="?messages&chat=<?php echo $chat_id;?>" enctype="multipart/form-data">
                                <textarea name="tnm" placeholder="Write your message"></textarea>
                                <div class="bottom-line">
                                    <span class="submit arrow-down">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/></svg>
                                    </span>
                                    <input type="file" name="file_to_upload" id="file-input" />
                                    <input type="submit" class="submit" name="submit" value="Send" />
                                    <label for="file-input">
                                        <img src='<?php echo $this->plugin_url . '/../assets/img/attach.svg'; ?>' />
                                    </label>
                                </div>

                            </form>
                        </div>
<!--                        <button class="submit" id="send-the-message">Send</button>-->
<!--                        <input class="submit attach-file" name="attach-file" type="file">-->
<!--
                       </input>-->
<!--                        <div class="image-upload">-->
<!--                            <label for="file-input">-->
<!--                                <img src="--><?php //echo $this->plugin_url . '/../assets/img/attach.svg'; ?><!--" />-->
<!--                            </label>-->
<!---->
<!--                            <input id="file-input" type="file"/>-->
<!--                        </div>-->
                    </div>

                    <div class="wrap" id="reply-message">
                        <span class="submit" id="reply-message">Reply</span>

                    </div>


                </div>
<!--                <div class="textarea-pop">-->
<!--                    <textarea placeholder="Write your message" id="the-message-to-be-sent" autofocus></textarea>-->
<!--                    <input type="hidden" id="the-chat-id" value="--><?php //echo $chat_id;?><!--">-->
<!--                    <input type="hidden" id="the-other-user" value="--><?php //echo $other_user;?><!--">-->
<!--                </div>-->

            </div>
        </div>



        <!--        <script src="--><?php //echo esc_url( untrailingslashit($this->plugin_url) . '/../assets/js/script.js' ); ?><!--"></script>-->

        <script>
            //jQuery("#send-the-message").on('click', function () {
            //
            //    let message = document.getElementById('the-message-to-be-sent').value;
            //    //console.log(<?php ////echo json_encode('f172873e6069d65e87acb679096018da'); ?>////);
            //    //let chat_id = <?php ////echo json_encode($chat_id); ?>////;
            //    //let other_user = <?php ////echo ($other_user); ?>////;
            //    let chat_id = document.getElementById('the-chat-id').value;
            //    let other_user = document.getElementById('the-other-user').value;
            //    let image_to_send = document.getElementById('file-input').value;
            //    console.log('img ' + image_to_send);
            //    if (image_to_send.length == 0) {
            //        console.log('image is empty');
            //    }
            //
            //    jQuery.ajax({
            //        url: my_ajax_object.ajax_url,
            //        type: "POST",
            //        data: {
            //            action: 'tdcwn_send_message',
            //            message: message,
            //            other_user: other_user,
            //            chat_id: chat_id,
            //            image: image_to_send
            //        },
            //        success: function() {
            //            jQuery('.textarea-pop').css({
            //                "position" : "absolute",
            //                "width" : "100%",
            //                "transition" : "all 1s",
            //                "bottom" : "auto"
            //            });
            //            jQuery("#reply-message").css({
            //                "display" : "flex"
            //            });
            //            jQuery("#send-message").css({
            //                "display" : "none"
            //            });
            //
            //            jQuery(".tdcwn-message-success").css({
            //                "display" : "block"
            //            });
            //            setTimeout(function() {
            //                jQuery(".tdcwn-message-success").css({
            //                    "display" : "none"
            //                });
            //            }, 5000);
            //
            //            console.log('Message sent!');
            //            // console.log(this.success);
            //        },
            //        error: function() {
            //            jQuery(".tdcwn-message-error").css({
            //                "display" : "block"
            //            });
            //            setTimeout(function() {
            //                jQuery(".tdcwn-message-error").css({
            //                    "display" : "none"
            //                });
            //            }, 5000);
            //            console.log( 'An error occurred' );
            //        }
            //    });
            //});
        </script>
        <?php
        $buffy .= ob_get_clean();

        $buffy .= '</div>';

        return $buffy;
    }

    function localize_my_script($chat_id) {
        $translation_array = array('myJsVar' => $chat_id);
        wp_localize_script('my-script', 'my_object_name', $translation_array);
    }



}