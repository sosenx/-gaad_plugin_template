<?php
namespace apii;


class rest
{

    public static function option($data = NULL)
    {
        $r = array(
            'h' => rest::getHeaders("//", true),
            'plugin_name' => "apii",
            'handler' => "option", 'status' => 200);

        $value = base64_decode($r['h']['selected']['v']);
        $name = $r['h']['selected']['n'];
        $t = $r['h']['selected']['t'];

        switch ($t) {
            case 'u' :
                $r[$t]['status'] = update_option($name, $value);

                if ($r[$t]['status']){
                    $r[$t]['status-icon'] = '<i class="fa fa-check" aria-hidden="true"></i>';
                    $r[$t]['status-class'] = 'u-200';
                }

                break;
            default:

                break;
        }

        return json_encode($r);
        wp_send_json(json_encode($r));
    }


    public static function app_model($data = NULL)
    {
        $r = array('plugin_name' => "apii", 'handler' => "app_model", 'status' => 200);
        return json_encode($r);
    }


    public static function add_woo_product_image($data = NULL)
    {
        $r = array('plugin_name' => "apii", 'handler' => "add_woo_products_images_info", 'status' => 200);
        $h = rest::getHeaders("//", true);
        $api_login = $h['selected']['api_login'];
        $api_pass = $h['selected']['api_pass'];

        $post_id = $h['selected']['post_id'];
        $image_url = $h['selected']['image_url'];
        $images_list_length = $h['selected']['images_list_length'];


        $apii = \apii\rest::getAPIController($h['selected']['api_name'], $api_login, $api_pass);

        $r['added_image_data'] = $apii->add_product_image($post_id, $image_url)['added_image_data'];
        $r['images_list_length'] = $images_list_length;

        return json_encode($r);
    }

    public static function add_woo_product_gimage($data = NULL)
    {
        $r = array('plugin_name' => "apii", 'handler' => "add_woo_products_images_info", 'status' => 200);
        $h = rest::getHeaders("//", true);
        $api_login = $h['selected']['api_login'];
        $api_pass = $h['selected']['api_pass'];

        $post_id = $h['selected']['post_id'];
        $image_url = $h['selected']['image_url'];
        $images_list_length = $h['selected']['images_list_length'];


        $apii = \apii\rest::getAPIController($h['selected']['api_name'], $api_login, $api_pass);

        $r['added_image_data'] = $apii->add_product_gimage($post_id, $image_url)['added_image_data'];
        $r['images_list_length'] = $images_list_length;

        return json_encode($r);
    }

    public static function add_woo_products_images_info($data = NULL)
    {
        $r = array('plugin_name' => "apii", 'handler' => "add_woo_products_images_info", 'status' => 200);
        $h = rest::getHeaders("//", true);
        $api_login = $h['selected']['api_login'];
        $api_pass = $h['selected']['api_pass'];


        $apii = \apii\rest::getAPIController($h['selected']['api_name'], $api_login, $api_pass);
        $images_list = $apii->save_images_list();
        $r['images_list'] = $images_list;
        return json_encode($r);
    }

    public static function add_woo_products_galleries_info($data = NULL)
    {
        $r = array('plugin_name' => "apii", 'handler' => "add_woo_products_galleries_info", 'status' => 200);
        $h = rest::getHeaders("//", true);
        $api_login = $h['selected']['api_login'];
        $api_pass = $h['selected']['api_pass'];


        $apii = \apii\rest::getAPIController($h['selected']['api_name'], $api_login, $api_pass);
        $images_list = $apii->save_galeries_list();
        $r['images_list'] = $images_list;
        return json_encode($r);
    }

    public static function add_woo_products_part($data = NULL)
    {
        $r = array('plugin_name' => "apii", 'handler' => "add_woo_products_part", 'status' => 200);
        $h = rest::getHeaders("//", true);

        $part = (int)$h['selected']['part'];
        $part_max_index = (int)$h['selected']['part_max_index'];

        $api_login = $h['selected']['api_login'];
        $api_pass = $h['selected']['api_pass'];


        $apii = \apii\rest::getAPIController($h['selected']['api_name'], $api_login, $api_pass);

        $filename = \apii\APII_DIR . 'json/' . $h['selected']['api_name'] . '/product-part-' . $part . '.json';
        $filename_next = \apii\APII_DIR . 'json/' . $h['selected']['api_name'] . '/product-part-' . ($part + 1) . '.json';
        $is_last = !is_file($filename_next);
        $is_file = is_file($filename);


        $r['part'] = $part;
        $r['progress'] = $part === $part_max_index ? "All parts done successfully" : 'part ' . ($part + 1) . ' of ' . ($part_max_index + 1) . ' done.';
        $r['is_last'] = $is_last;
        $r['is_file'] = $is_file;
        $r['next_part'] = !$is_last ? $part + 1 : -1;
        $r['part_max_index'] = $part_max_index;


        if ($is_file) {
            $records = json_decode(file_get_contents($filename), ARRAY_N);

            if (is_array($records)) {
                foreach ($records as $record) {
                    $post_title = trim(is_array($record['name']) ? $record['name']['pl'] : $record['name']);
                    $content = $apii->add_woo_product_content($record);
                    $categories = $record['categories'];
                    $image = $record['images'];

                    $apii->add_woo_product(array(
                            'post_title' => $post_title,
                            'post_content' => $content,
                            'record' => $record,
                            'post_category' => is_array($categories) ? $categories : array($categories),
                            'image' => is_array($image) ? array_shift($image) : $image,
                            'gallery' => $image
                        )
                    );
                }
            }

        }

        return json_encode($r);
    }


    public static function add_woo_products_info($data = NULL)
    {
        $r = array('plugin_name' => "apii", 'handler' => "add_woo_products", 'status' => 200);
        $h = rest::getHeaders("//", true);

        $parts = glob(\apii\APII_DIR . 'json/' . $h['selected']['api_name'] . '/product-part*');
        if (is_array($parts) && !empty($parts)) {
            $max = 0;
            //get max index
            foreach ($parts as $index => $filename) {
                $match = array();
                preg_match('/product-part-(\d+)\.json/', $filename, $match);
                $part_index = (int)$match[1];
                $max = $part_index > $max ? $part_index : $max;
            }

            $parts_list = array();
            $parts_max = $max - 1;
            for ($i = 0; $i < $parts_max; $i++) {
                $parts_list[] = \apii\APII_DIR . 'json/product-part-' . $i . '.json';
            }

            $r['parts_list'] = $parts_list;
            $r['max_index'] = $parts_max;
        }


        return json_encode($r);
    }


    public static function add_woo_categories($data = NULL)
    {
        $r = array('plugin_name' => "apii\\rest::add_woo_categories");
        $h = rest::getHeaders("//", true);
        $downloads_dir = \apii\APII_DIR . 'json/' . $h['selected']['api_name'];
        $products_json_file = $downloads_dir . '/categories.json';
        if (is_file($products_json_file)) {
            $api_login = $h['selected']['api_login'];
            $api_pass = $h['selected']['api_pass'];

            $apii = \apii\rest::getAPIController($h['selected']['api_name'], $api_login, $api_pass);
            $apii->add_woo_categories();
        }


        return json_encode($r);
    }


    /**
     * [get_categories_file_info description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function unlink_products_json($data = NULL)
    {
        $r = array('plugin_name' => "apii\\rest::unlink_products_json");
        $h = rest::getHeaders("//", true);

        rest::unlink_source_json("product", $h['selected']['api_name']);
        return json_encode($r);
    }

    /**
     * [get_categories_file_info description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function unlink_categories_json($data = NULL)
    {
        $r = array('plugin_name' => "apii\\rest::unlink_categories_json");
        $h = rest::getHeaders("//", true);
        rest::unlink_source_json("categories", $h['selected']['api_name']);
        return json_encode($r);
    }

    /**
     * [get_categories_file_info description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function unlink_source_json($label, $apiname = 'parapi')
    {
        $json_dir = \apii\APII_DIR . 'json/' . $apiname;
        $files = glob($json_dir . '/' . $label . '*');

        if (!empty($files)) {
            foreach ($files as $file) {
                unlink($file);
            }
            return true;
        }
    }

    /**
     * Checks if files product list are downloaded for parsing
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function get_categories_file_info($data = NULL)
    {
        $h = rest::getHeaders("//", true);
        $r = array('plugin_name' => "apii\\rest::get_categories_file_info");
        $downloads_dir = \apii\APII_DIR . 'json/' . $h['selected']['api_name'];

        if (!is_dir($downloads_dir)) {
            mkdir($downloads_dir, 0775);
        }

        if (is_dir($downloads_dir)) {
            $products_json_file = $downloads_dir . '/categories.json';
            if (is_file($products_json_file)) {
                return filemtime($products_json_file);
            } else {

                $api_login = $h['selected']['api_login'];
                $api_pass = $h['selected']['api_pass'];

                $apii = \apii\rest::getAPIController($h['selected']['api_name'], $api_login, $api_pass);
                $r['get_products_status'] = $apii->get_categories();
            }
        }

        return json_encode($r);
    }

    public static function getAPIController($apiname, $api_login, $api_pass = NULL)
    {

        switch ($apiname) {
            case 'parapi' :
                $controller = new par_api($api_login, $api_pass);
                break;
            case 'macmaapi' :
                $controller = new macma_api($api_login, $api_pass);
                break;
            case 'apii' :
                $controller = new asgard_api($api_login, $api_pass);
                break;

            default :
                $controller = new par_api($api_login, $api_pass);
                break;
        }
        return $controller;
    }

    /**
     * Checks if files product list are downloaded for parsing
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public static function get_current_products_file_info($data = NULL)
    {
        $h = rest::getHeaders("//", true);
        $r = array('plugin_name' => "apii\\rest::get_current_products_file_info");
        $downloads_dir = \apii\APII_DIR . 'json/' . $h['selected']['api_name'];

        if (!is_dir($downloads_dir)) {
            mkdir($downloads_dir, 0775);
        }

        if (is_dir($downloads_dir)) {
            $products_json_file = $downloads_dir . '/products.json';
            if (is_file($products_json_file)) {
                return filemtime($products_json_file);
            } else {

                $api_login = $h['selected']['api_login'];
                $api_pass = $h['selected']['api_pass'];

                $apii = \apii\rest::getAPIController($h['selected']['api_name'], $api_login, $api_pass);
                $r['get_products_status'] = $apii->get_products();
            }
        }

        return json_encode($r);
    }


    public static function rest_test_callback($data = NULL)
    {
        $r = array('plugin_name' => "apii\\rest::rest_test_callback");
        return json_encode($r);
    }


    /**
     * Zwraca tablicę z nagówkami. Możliwe podanie jest wyrażenia regularnego jakie mają speniać klucze dodawanych nagówków.
     *
     * @param string regexp wyrażenie regularne jakie mają speniać nagówki. Jego brak spowoduje zwrócenie wszystkich nagówków.
     *
     * @return array
     */
    public static function getHeaders($regexp = NULL, $return_rest = NULL)
    {
        /*
        * Headers to return array
        */
        $h = array();
        /*
        * Headers that doesn't match criteria array
        */
        $rh = array();
        /*
        * All Headers in a request
        */
        $allh = getallheaders();

        if (!empty($allh)) {
            foreach ($allh as $key => $value) {
                if (preg_match($regexp, $key)) {
                    $h[$key] = $value;
                } elseif ($return_rest) {
                    $rh[$key] = $value;
                }
            }
        }

        return $return_rest ? array('selected' => $h, 'others' => $rh) : $h;
    }
}

?>
