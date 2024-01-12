<?php
    class SmartUrl{
        private $url;
        function __construct(string $url = ""){

            if($url != ""){
                $this->url = $url;
                $this->add_url();
            }
        }
        //adding url to db
        private function add_url(){
            global $wpdb;
            $url = '"'.$this->url.'"';
            $redirect = 1;
            $redirectStats = 0;
            $date = time();
            $referer = $_SERVER['HTTP_REFERER'] ? true : false;
            $ip = $_SERVER['HTTP_CLIENT_IP']
                ? : ($_SERVER['HTTP_X_FORWARDED_FOR']
                    ? : $_SERVER['REMOTE_ADDR']);

            $result = $wpdb->query("INSERT INTO `smart404_urls` (`404`, `auto_redirect`, `redirect_stats`, `date`, `referer`) VALUES ()");
        }
    }