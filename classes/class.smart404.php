<?php
    class Smart404{
        private $redirect;
        private $urls;
        public function __construct(){
            @ini_set("display_errors", true);
        }
        //activating plugin
        static function plugin_activation(){
            if(!Smart404::option_exists("Smart404")){
                add_option("Smart404", true);
            }else{
                update_option("Smart404", true);
            }
        }
        //uninstalling plugin
        static function plugin_uninstall(){
            if(Smart404::option_exists("Smart404")){
                global $wpdb;

                update_option("Smart404", false);
                $wpdb->query("DROP TABLE `smart404_configs`, `smart404_redirects`, `smart404_urls`; ");
                wp_die();
            }
        }

        //checking plugin active status
        static function option_exists($name, $site_wide=false){
            global $wpdb; return $wpdb->query("SELECT * FROM ". ($site_wide ? $wpdb->base_prefix : $wpdb->prefix). "options WHERE option_name ='$name' LIMIT 1");
        }

        //deactivating plugin
        static function plugin_deactivation(){

        }

        //initializing plugin
        public function init(){
            global $wpdb;
            add_action("admin_menu", function (){
                add_menu_page("Smart 404", "Smart 404", "publish_pages", "s404", [$this, "get_current_page"]);
                add_submenu_page("s404", "Settings","Settings", "publish_pages","s404_settings", function (){
                    require_once S404_DIR . "settings-page.php";
                });
            });
            add_action("admin_enqueue_scripts", [$this, "set_scripts"]);
            add_action("template_redirect", [$this, "custom_redirect"]);
            add_action("wp_ajax_sm404_get_redirect", [$this, "custom_redirect"]);
            add_action("wp_ajax_nopriv_sm404_get_redirect", [$this, "custom_redirect"]);
            $this->create_table();
            add_action("wp_ajax_sm404_post_types", [$this, "set_post_types"]);
            add_filter("do_redirect_guess_404_permalink", "__return_false");
        }
        public function get_current_page(){
            $page = $_GET["page"] ?? "";
            $subpage = $_GET["subpage"] ?? "";
            if($page){
                global $sm404;
                $sm404 = new Smart404();
                if($subpage){
                    require_once S404_DIR . "subpages/$subpage.php";
                }else{
                    require_once S404_DIR . "$page.php";
                }
            }
        }
        //setter for post type settings
        public function set_post_types(){
            if($_POST["data"]){
                global $wpdb;
                if($wpdb->get_var("SELECT * FROM `smart404_configs` WHERE `config_key` = 'post_types'")){
                    $wpdb->update("smart404_configs", ["config_value" => stripslashes($_POST["data"])], ["config_key" => "post_types"]);
                }else{
                    $wpdb->insert("smart404_configs", ["config_key" => "post_types", "config_value" => stripslashes($_POST["data"])]);
                }
            }
            wp_die();
        }
        //creating smart404 table in sql
        public function create_table(){
            global $wpdb;
            $wpdb->query("CREATE TABLE IF NOT EXISTS `smart404_urls`(`404` VARCHAR(255), `auto_redirect` VARCHAR(255), `has_redirect` BOOLEAN, `date` DATETIME default current_timestamp, `referer` BOOLEAN) ");
            $wpdb->query("CREATE TABLE IF NOT EXISTS `smart404_redirects`(`404` VARCHAR(255), `redirect` VARCHAR(255), `auto_redirect` VARCHAR(255), `total_autoRedirects` int(11) DEFAULT 0,`total_customRedirects` INT(11) DEFAULT 0)");
            $wpdb->query("CREATE TABLE IF NOT EXISTS `smart404_configs`(`config_key` VARCHAR(16), `config_value` VARCHAR(8000))");
            $wpdb->query("ALTER TABLE `smart404_redirects` CHANGE `auto_redirect` `auto_redirect` VARCHAR(255) NULL DEFAULT ''");
            $wpdb->query("ALTER TABLE `smart404_redirects` ADD `redirect_type` INT NOT NULL AFTER `total_customRedirects`;");
            $wpdb->query("ALTER TABLE `smart404_redirects` ADD `post_type` VARCHAR(255) NOT NULL DEFAULT '' AFTER `redirect_type`;");
            if(!$wpdb->get_var("SELECT `config_key` FROM `smart404_configs` WHERE `config_key` = 'post_types'")){
                $wpdb->insert("smart404_configs", ["config_key" => "post_types", "config_value" => '["post", "page"]']);
            }
        }
        public function custom_redirect(){
            global $wpdb;
                $url = $this->get_url();
                $result = $wpdb->get_results("SELECT `redirect`, `redirect_type` FROM `smart404_redirects` WHERE `404` = \"$url\" AND `redirect` != ''", ARRAY_A);
                $redirect = null;
                if(is_array($result) && count($result)){
                    $redirect = $result[0]['redirect'];
                    $redirectType = $result[0]['redirect_type'] ?? false;
                    $wpdb->query("UPDATE `smart404_redirects` SET `total_customRedirects` = `total_customRedirects` + 1 WHERE `404` = '$url' AND `redirect` = '$redirect'");
                    $this->insert_404();
                    wp_redirect($redirect, $redirectType ?: 301);
                    die();
                }else if(is_404() && get_option("sm404_autoredirect")){
                    $redirect = $this->auto_redirect();
                    $redirect = $redirect["link"] ?? $redirect;
                    if($redirect){
                        wp_redirect($redirect, 301);
                        $this->insert_404();
                        die();
                    }
                }
            if(is_404()){
                $this->insert_404();
            }
            if($redirect){
                wp_redirect($redirect);
            }
        }
        private function get_url(){
            return urldecode((empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }
        //auto redirecting 404 page
        public function auto_redirect(){
            global $wpdb;

            if(count(Sm404settings::get_active_post_types())){
                $url = $this->get_url();
                $redirect = $wpdb->get_var("SELECT `auto_redirect` FROM `smart404_redirects` INNER JOIN `smart404_configs` ON `config_key` = 'post_types' AND `config_value` LIKE CONCAT('%', `post_type`, '%') WHERE `redirect` is NULL AND `404` = '$url' AND `auto_redirect` != ''");
                $redirect = $redirect ? : $this->search_links();
                $redirect =  is_array($redirect) ? $redirect[0] : $redirect ;
                $postType = $redirect["post_type"]?? null ;
                $redirect = $redirect["link"] ?? $redirect;
                if($redirect) {
                    $pattern = "/^(". preg_quote(home_url(), "/") . ")\/wp-content/";
                    if(preg_match($pattern, $url))return null;
                    if($wpdb->get_var("SELECT `404` FROM `smart404_redirects` WHERE `404` = '$url' AND `auto_redirect` = '$redirect' AND `redirect` IS NULL")){
                        $wpdb->query("UPDATE `smart404_redirects` SET `total_autoRedirects` = `total_autoRedirects` + 1 WHERE `404` = '$url' AND `redirect` IS NULL AND `auto_redirect` = '$redirect'");
                    }else{
                        $wpdb->insert("smart404_redirects", ["404" => $url, "auto_redirect" => $redirect, "total_autoRedirects" => 1, "post_type" => $postType]);
                    }
                    return $redirect;
                }
            }
            return null;

        }
        //after finding 404 url, inserting to db
        private function insert_404(){
            global $wpdb;
            $referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : false;
            $url = $this->get_url();
            $pattern = "/^(". preg_quote(home_url(), "/") . ")\/wp-content/";
            if(preg_match($pattern, $url))return null;
            $redirect = $wpdb->get_var("SELECT `redirect` FROM `smart404_redirects` WHERE `redirect` IS NOT NULL AND `404` = '$url'");
            $autoredirect = get_option("sm404_autoredirect") === "1" && (bool)($wpdb->get_var("SELECT `auto_redirect` FROM `smart404_redirects` WHERE `404` = \"$url\" AND `auto_redirect` is not null") );
            if(!$redirect && get_option("auto_redirect") == "1"){
                $redirect = $wpdb->get_var("SELECT `auto_redirect` FROM `smart404_redirects` WHERE `auto_redirect` IS NOT NULL AND `redirect` IS NULL");
            }
            $wpdb->insert("smart404_urls", ["404" => $url, "has_redirect" => $redirect !== null, "auto_redirect" => $autoredirect ?: 0, "referer" => $referer]);
            if($redirect == null){
                $sendMail = (int)$wpdb->get_var("SELECT COUNT(`404`) FROM `smart404_urls` WHERE `404` = '$url' AND `has_redirect` = 0");
                if($sendMail === 15){
                    $this->send_mail($url);
                }
            }
        }
        //sending mail
        private function send_mail($url){
            $headers = [
                "From: Smart404 <smart404@$_SERVER[HTTP_HOST]>",
                "content-type: text/html"
            ];
            ob_start();
            ?>
            <div style="max-width: 500px; padding: 15px; margin: 0 auto;">
                <div style="background-color: #d6140a; color: white; text-align: center; padding: 15px; margin-bottom: 24px;">
                    <span style="font-size: 24px; font-weight: 700">Help, I need somebody</span>
                </div>
                <div style="margin-bottom: 24px;">
                    <span><?=$url ?? ""?>-url needing to redirect</span>
                </div>
                <a href="<?=admin_url() . "admin.php?page=s404"?>" style="box-sizing: border-box;display: block; width: 100%; padding: 15px; background-color: #4099ff; color: white; font-size: 18px; font-weight: bold; text-decoration: none; text-align: center">Take action now</a>
            </div>
            <?php
            wp_mail( get_bloginfo("admin_email"), "Smart404 Warning", ob_get_clean(), $headers);
        }
        //getting redirect url by 404 url
        private function get_redirect($url){
            global $wpdb;
            return $wpdb->get_var("SELECT `redirect` FROM `smart404_redirects` WHERE `404` = \"$url\" AND `redirect` != \"\" AND `auto_redirect` = " . (get_option("sm404_autoredirect") == "1" ? "true" : "false")) ? : null;
        }
        //searching link's with matching url
        public function search_links(){
            if(!count($postTypes = Sm404settings::get_active_post_types()))return null;
            global $wpdb;
            $url  = parse_url($this->get_url(), PHP_URL_PATH); // gives "/pwsdedtech"
            $url = urldecode($url);
            $url = preg_split("/\//", $url);
            foreach ($url as $value){
                if($value){
                    $query = new WP_Query([
                        "post_status" => "publish",
                        "s" => preg_replace("/\-/", " ", $value),
                        "post_type" => $postTypes
                    ]);
                    if($query->have_posts()){
                        $permalinks = [];
                        foreach ($query->get_posts() as $item){
                            $permalinks[] = ["link" => get_permalink($item), "post_type" => get_post_type($item)];
                        }
                        return $permalinks;
                    }
                    $postTypesSql = $this->post_types_to_sql();
                    $results = $wpdb->get_results("SELECT `ID`, `post_type` FROM `wp_posts` WHERE `post_name` LIKE \"%$value%\" AND $postTypesSql", ARRAY_A);
                    if(is_array($results) && count($results)){
                        $permalinks = [];
                        foreach ($results as $result){
                            $permalinks[] = ["link" => get_permalink($result["ID"]), "post_type" => $result["post_type"]];
                        }
                        return $permalinks;
                    }
                }
            }
            return null;
        }
        //returning string for sql request for auto redirect
        private function post_types_to_sql(){
            $actives = Sm404settings::get_active_post_types();
            $string = "`post_type` IN (";
            foreach ($actives as $key => $active){
                if($key != 0){
                    $string .= ",'$active'";
                }else{
                    $string .= "'$active'";
                }
            }
            return $string . ")";
        }
        //getting total redirect's
        public function get_total_redirects($redirectType = null){
            global $wpdb;
            $redirectType = $redirectType ? ($redirectType == "custom_redirect" ? "has_redirect = 1" : ($redirectType == "auto_redirect" ? "auto_redirect = 1" : "has_redirect = 1 OR `auto_redirect` = 1")) : "has_redirect = 1 OR `auto_redirect` = 1";
            return $wpdb->get_var("SELECT COUNT(`404`) FROM `smart404_urls` WHERE ($redirectType)");
        }
        //getting total redirected page's
        public function get_total_pages($redirectType = null){
            global $wpdb;
            $redirectType = $redirectType ? ($redirectType == "custom_redirect" ? "has_redirect = 1" : ($redirectType == "auto_redirect" ? "auto_redirect = 1" : "has_redirect = 1 OR `auto_redirect` = 1")) : "has_redirect = 1 OR `auto_redirect` = 1";
            return $wpdb->get_var("SELECT COUNT(DISTINCT `404`) FROM `smart404_urls` WHERE ($redirectType)");
        }
        //setting all css and js assets
        public function set_scripts(){
            if(isset($_GET["page"]) && $_GET["page"] == "s404"){
                wp_enqueue_script("chart", S404_URL . "/assets/js/dist/chart.js", [], "1.0.0", true);
                wp_enqueue_script("chartjs-date", S404_URL . "/assets/js/dist/chartjs-date.min.js", [], "1.0.0", true);
                wp_enqueue_script("datatables", S404_URL . "/assets/js/dist/datatables.min.js", [], "1.0.0", true);
                wp_enqueue_style("datatables", S404_URL . "/assets/css/dist/jquery.dataTables.min.css", [], "1.0.0");
                wp_enqueue_script("smart-main", S404_URL . "/assets/js/dist/main.js", ["chart"], "1.0.2", true);
                wp_enqueue_style("smart-main", S404_URL . "/assets/css/dist/all-styles.css", [], "1.0.0");
                wp_enqueue_style('awesome-font', S404_URL .  '/assets/css/src/addons/font-awesome.min.css', array(), '1.0.0');
            }
            add_filter('script_loader_tag', [$this,"add_type_attribute"] , 10, 3);

        }
        function add_type_attribute($tag, $handle, $src) {
            // if not your script, do nothing and return original $tag
            if ( 'smart-main' !== $handle ) {
                return $tag;
            }
            // change the script tag by adding type="module" and return it.
            $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
            return $tag;
        }
        //get all redirect needed urls
        public function get_needle(){
            global $wpdb;
            $results = $wpdb->get_results("SELECT DISTINCT `404`, `has_redirect` FROM `smart404_urls` WHERE `has_redirect` = 0 ", ARRAY_A);
            $hasRedirect = $wpdb->get_col("SELECT DISTINCT `404` FROM `smart404_redirects` WHERE `redirect` != \"\" AND `auto_redirect` != " . (get_option("sm404_autoredirect") === "0" ? "true" : "false") );
            $needle = [];
            foreach ($results as $key => $result){
                if(!in_array($result[404], $hasRedirect)){
                    $needle[] = [
                        "404" => $result[404],
                        "total" => $wpdb->get_var("SELECT COUNT(`404`) FROM `smart404_urls` WHERE `has_redirect` = 0 AND `404` = \"$result[404]\""),
                        "disabled" => $wpdb->get_var("SELECT `404` FROM `smart404_redirects` WHERE `404` = '$result[404]' AND (`redirect` != '' OR `auto_redirect` != '')")
                    ];
                }
            }
            $key_values = array_column($needle, 'disabled');
            array_multisort($key_values, SORT_ASC, $needle);
            return $needle;
        }
        public function import_all_classes(){
            $files = scandir(S404_DIR . "/classes");
            unset($files[0],$files[1]);
            foreach ($files as $file){
                if($file != "class.smart404.php"){
                    require_once (S404_DIR . "/classes/" . $file);
                }
            }
            $this->redirect = new Redirects();
            $this->urls = new SmartUrl();
        }
        //getting 404 page content matching url's
        public function links_html(){
            ?>
            <style>
                .sm404-wrapper .links-list{
                    list-style: none;
                }
                .sm404-wrapper .links-list .link-item .link{
                    text-decoration: none;
                    color: #0A246A;
                }
                .sm404-wrapper .sm404-title .title-item{
                    margin: 0 0 12px;
                }
            </style>
                <?php if(is_array($links = $this->search_links())): ?>
                <div class="sm404-wrapper">
                    <div class="sm404-title">
                        <h2 class="title-item">Maybe you are looking?</h2>
                        <ul class="links-list">

                        <?php foreach( $links as $link) : ?>
                                <li class="link-item">
                                    <a href="<?=$link["link"]?>" class="link"><?=$link["link"]?></a>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            <?php endif ;
        }
    }