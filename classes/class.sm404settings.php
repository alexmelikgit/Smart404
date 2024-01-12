<?php
    class Sm404settings{
        function __construct(){
            $this->init();
        }
        private function init(){
            $this->enqueue_assets();
        }

        private function enqueue_assets(){
            wp_enqueue_style("smart-main", S404_URL . "/assets/css/dist/all-styles.css", [], "1.0.2");
            wp_enqueue_script("smart-main", S404_URL . "/assets/js/dist/main.js", [], "1.0.2");
            wp_enqueue_style('awesome-font', S404_URL .  '/assets/css/src/addons/font-awesome.min.css', array(), '1.0.0');
            add_filter('script_loader_tag', 'add_type_attribute' , 10, 3);
            function add_type_attribute($tag, $handle, $src) {
                // if not your script, do nothing and return original $tag
                if ( 'smart-main' !== $handle ) {
                    return $tag;
                }
                // change the script tag by adding type="module" and return it.
                $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
                return $tag;
            }
        }
        public function get_post_types(){
            $exceprt = ["attachment", "revision", "nav_menu_item", "custom_css", "customize_changeset", "oembed_cache", "user_request", 'wp_block', "wp_template", "wp_template_part", "wp_global_styles", "wp_navigation"];
            $postTypes = [];
            foreach (get_post_types(["public" => true]) as $type){
                if(!in_array($type, $exceprt)){
                    $postTypes[] = $type;
                }
            }
            return $postTypes;
        }
        public static function get_active_post_tpes(){
            global $wpdb;
            $result = $wpdb->get_var("SELECT `config_value` FROM `smart404_configs` WHERE `config_key` = 'post_types'");
            if($result){
                return json_decode($result);
            }
            return [];
        }
    }