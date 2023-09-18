<?php

global $wpdb;


/**
 * Notifications table
 */
$notifications_table = $wpdb->prefix . 'tdcwn_notifications';
if($wpdb->get_var("SHOW TABLES LIKE '$notifications_table'") != $notifications_table) {


    $notifications_table_sql = "
    CREATE TABLE `$notifications_table` (
            `id` int(11) NOT NULL,
            `message` text NOT NULL,
            `id_job` int(11) NOT NULL,
            `id_client` int(11) NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

    $notifications_table_sql .= "ALTER TABLE `$notifications_table` ADD PRIMARY KEY (`id`);";
    $notifications_table_sql .= "ALTER TABLE `$notifications_table` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

// Include WP upgrade functions and create table
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($notifications_table_sql);
}

/**
 * Messages table
 */
$messages_table = $wpdb->prefix . 'tdcwn_messages';
if ( $wpdb->get_var("SHOW TABLES LIKE '$messages_table'") != $messages_table ) {
    $messages_table_sql = "
        CREATE TABLE `$messages_table` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user` varchar(255) NOT NULL,
            `message` text NOT NULL,
            `timestamp` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY (`id`)
        );
    ";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($messages_table_sql);
}


