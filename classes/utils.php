<?php

    class Utils {

        static public $locations = array();
        static private function restore_session() {
            if (isset($_SESSION['utils_locations'])) {
                self::$locations = $_SESSION['utils_locations'];
            }
        }

        static private function save_session() {
            $_SESSION['utils_locations'] = self::$locations;
        }
        
        static function split($string, $char) {
            $array = array();

            $pos = strpos($string, $char);
            while (!($pos === false)) {
                $temp = substr($string, 0, $pos);
                $array[] = $temp;
                $string = substr($string, $pos+1);  
                $pos = strpos($string, $char);
            }

            $array[] = $string;

            return $array;
        }

        static function to_csv($array) {
            $string = "";
            foreach ($array as $key=>$value) {
                $string .= $value.",";
            }
            $string = substr($string, 0, -1);
            return $string;
        }

        static function check_quotes($string) {
            $string = str_replace("'", "\'", $string);
            return $string;
        }

        static function add_location($name, $url) {
            self::restore_session();
            self::$locations[$name] = $url;
            self::save_session();
        }

        static public function remove_location($name) {
            self::restore_session();
            if (isset(self::$locations[$name])) {
                unset(self::$locations[$name]);
            }
            
            self::save_session();
        }

        static function get_location($name) {
            self::restore_session();
            if (!isset(self::$locations[$name])) {
                return null;
            }
            return self::$locations[$name];
        }

        static function navigate($name) {
            self::restore_session();
            if (!isset(self::$locations[$name])) {
                exit(header("Location: ".self::$locations['home']));
            }
            exit(header("Location: ".self::$locations[$name]));
            
        }


        static function check_chars($string) {
            $string = str_replace(":", "", $string);
            $string = str_replace("(", "_", $string);
            $string = str_replace(")", "_", $string);
            $string = str_replace("-", "_", $string);
            $string = str_replace("&", "_", $string);
            $string = str_replace("@", "_", $string);
            $string = str_replace("*", "_", $string);
            $string = str_replace("$", "_", $string);
            $string = str_replace("|", "_", $string);
            $string = str_replace("%", "_", $string);
            $string = str_replace("~", "_", $string);
            $string = str_replace(" ", "_", $string);
            return $string;   
        }

        static function generate_token($id) {
            $token = $id.",";
            $token .= bin2hex(random_bytes(32));
            return $token;
        }

        static function decode_token($token) {
            $array = Utils::split($token, ",");
            $data = array("id"=>$array[0], "token"=>$array[1]);
            return $data;
        }

        static function validateDate($date, $format = 'Y-m-d H:i:s'){
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) === $date;
        }

        static function get_json_object($data, $headings) {

            $temp = array();
            foreach ($data as $key=>$value) {
                if (isset($headings[$key]['name'])) {
                    $temp[$headings[$key]['name']] = $value;
                }
                
            }
            return $temp;
        }
    }

?>