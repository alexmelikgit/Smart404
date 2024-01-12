<?php
/**
 * Plugin Name: Smart404
 */
define("S404_DIR", plugin_dir_path(__FILE__));
define("S404_URL", plugin_dir_url(__FILE__));
require_once(S404_DIR . "classes/class.smart404.php");
register_activation_hook(__FILE__, ["Smart404", "plugin_activation"]);
register_deactivation_hook(__FILE__, ["Smart404", "plugin_deactivation"]);
register_uninstall_hook(__FILE__, ["Smart404", "plugin_uninstall"]);
add_action("init", function (){
    $sm404 = new Smart404();
    $sm404->import_all_classes();
    $sm404->init();
    add_shortcode("smart404_urls", [$sm404, "links_html"]);
    require_once (S404_DIR . "smart404-functions.php");
});
