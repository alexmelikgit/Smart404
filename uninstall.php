<?php
global $wpdb;
$wpdb->query("DROP TABLE `smart404_configs`, `smart404_redirects`, `smart404_urls`;");