<?php
    class Redirects{
        private $autoRedirects, $customRedirects;
        function __construct(){
            $this->autoRedirects = new AutoRedirects();
            $this->customRedirects = new CustomRedrects();
            $this->init();
        }
        //Redirects class initializing
        private function init(){
            $this->set_hooks();
        }
        //set all Hooks
        private function set_hooks(){
            add_action("wp_ajax_sm404_custom_redirect_link", [$this->customRedirects, "add_redirect"]);
            add_action("wp_ajax_sm404_remove_custom_redirect_link", [$this->customRedirects, "remove_redirect"]);

        }
        //url validate
        public static function is_url($string){
            $valid = preg_match('/^https?:\/\/(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}/', $string);
            return $valid;
        }
        //redirect getter by 404 url
        private function get_redirect($url){
            global $wpdb;
            return $wpdb->get_var("SELECT `redirect` FROM `smart404_redirects` WHERE `404` = \"$url\" AND `redirect` != \"\"") ? : null;
        }
        //Doing redirect after finding 404
        public function do_redirect(){
            if(!is_404())return;
        }

        //getting page url
        private function get_url(){
            return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }
        //after finding 404, inserting to db
        private function insert_404(){

        }

    }

    class AutoRedirects{
        private static $obj;
        function __construct(){
            AutoRedirects::$obj = $this;
        }
        private function get_redriects(){
            global $wpdb;
            return $wpdb->get_results("SELECT * FROM `smart404_redirects` WHERE `total_autoRedirects` > 0 ORDER BY -`redirect` ASC", ARRAY_A);
        }
        static function getRedrects(){
            return AutoRedirects::$obj->get_redriects();
        }
    }
    class CustomRedrects{
        static private $obj;
        function __construct(){
            CustomRedrects::$obj = $this;
        }
        //setter for custom redirect
        private function set_redirect($data, bool $fromForm = true){
            global $wpdb;
            $needle = ["url"];
            if($fromForm){
                $needle[] = "redirect";
            }
            foreach ($needle as $key){
                if(!isset($data[$key])){
                    wp_die("data-$key is not exists");
                }
            }
            $url = $_POST["url"];
            $redirect = $_POST["redirect"] ?? "";
            $exist = $wpdb->get_results("SELECT * FROM `smart404_redirects` WHERE `404` = \"$url\"");
            $exist = count($exist);
                foreach ($needle as $key){
                    if(!Redirects::is_url($_POST[$key]))wp_die("$key is not valid url");
                }
                if($exist){
                    $wpdb->update("smart404_redirects", ["redirect" => $redirect ? : NULL, "redirect_type" => (int)$_POST["redirect_type"] ?? 301], ["404" => $url] );
                }else{
                    $wpdb->insert("smart404_redirects", ["404" => $url , "redirect" => $redirect]);
                }
            wp_die();
        }
        private function get_redirects(){
            global $wpdb;
            return $wpdb->get_results("SELECT * FROM `smart404_redirects` WHERE (`auto_redirect` != '1' OR `auto_redirect` is NULL) AND `redirect` != \"\"", ARRAY_A);
        }
        public static function getRedirects(){
            return CustomRedrects::$obj->get_redirects();
        }
        public function add_redirect(){
            $this->set_redirect($_POST);
        }
        public function remove_redirect(){
            $this->set_redirect($_POST, false);
        }
    }
