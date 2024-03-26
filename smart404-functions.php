<?php
    $sm404 = new Smart404();
//    add_action("wp_ajax_sm404_custom_redirect_link", [$sm404, "add_custom_redirect"]);
    add_action("wp_ajax_sm404_if_404", function (){
        $url = "http://plugins-loc.com/";
        // Disable error reporting for file_get_contents
        $context = stream_context_create(['http' => ['ignore_errors' => true]]);
        // Fetch the URL content
        $content = file_get_contents($url, false, $context);
        // Get the response headers
        $headers = $http_response_header;
        // Check if the response code contains "404"
        if(!$headers)wp_die("false");
        foreach ($headers as $header) {
            if (stripos($header, 'HTTP/1.0 404') !== false) {
                wp_die("false");
            }
        }
        wp_die("true");
    });
    add_action("wp_ajax_smart404_getdata", "smart404_getdata");
    function smart404_getdata(){
        global $wpdb;
        $date = json_decode(stripslashes($_POST["date"]), ARRAY_A);
        $start = $date["start"];
        $end = $date["end"] . " 23:59:59";
        $redirectType = $_POST["redirect_type"] ?? null;
        $redirectType = $redirectType ? ($redirectType == "custom_redirect" ? "has_redirect = 1" : ($redirectType == "auto_redirect" ? "auto_redirect = 1" : "has_redirect = 1 OR `auto_redirect` = 1")) : "has_redirect = 1 OR `auto_redirect` = 1";
        $results = $wpdb->get_results("SELECT DISTINCT(DATE_FORMAT(`date`, '%Y-%m-%d')) as x, COUNT(*) as y FROM `smart404_urls` WHERE ($redirectType) AND `date` BETWEEN '$start' AND '$end' GROUP BY DATE_FORMAT(`date`, '%Y-%m-%d') ORDER BY y DESC;",ARRAY_A);
        $day = strtotime($start);
        $data = [];
        while($day <= strtotime($end)){
            $exist = false;
            $stringTime = date("Y-m-d", $day);
            foreach ($results as $result){
                if($result["x"] == $stringTime){
                    $data[] = (object)[
                        "x" => $result["x"],
                        "y" => $result["y"]
                    ];
                    $exist = true;
                    break;
                }
            }
            if(!$exist){
                $data[] = (object)[
                    "x" => $stringTime,
                    "y" => 0,
                ];
            }
            $day = $day + (24 * 60 * 60 );
        }
        wp_die(json_encode($data));
    }
    add_action("wp_ajax_change_auto_redirect", function(){
        if(Smart404::option_exists("sm404_autoredirect")){
            update_option("sm404_autoredirect", (get_option("sm404_autoredirect") == "1" ? "0" : "1"));
        }else{
            add_option("sm404_autoredirect", true);
        }
        wp_die();

    });
    add_action("wp_ajax_get_totals", [$sm404, "get_total_pages"]);
?>
