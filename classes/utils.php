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

        static function check_wrappers($string, $pos, $char) {
            $wrappers = array( // list of wrapping elements to watch out for 
                // "'" => "'",
                '"' => '"',
                '{' => '}',
                '[' => ']',
                '(' => ')'
            );

            $max_pos = $pos; //
            $min_pos = $pos;

            foreach($wrappers as $start=>$end) { // loop through list of wrappers to check for outer most wrapping elements
                $start_pos = strpos($string, $start, 0); // finding postion of opening wrapper character
                if ($start_pos !== false && $start_pos < $min_pos) { // if character found and is before the delimiter instance
                    $end_pos = strpos($string, $end, $start_pos+1); // find the position of its respective closing character
                    if ($end_pos !== false && $end_pos > $max_pos) { // if there is a closing character and it occurs after the outer most found wrapper character
                        $max_pos = $end_pos; // set the outer most position to the found closing wrapper position
                        $min_pos = $start_pos; // set the first most wrapper position to the found wrapper opening position
                    }
                    // $pos = strpos($string, $char, $max_pos-1);
                    
                    // if ($pos === false) {
                    //     $pos = strlen($string) ;
                    // }

                    
                }
                }

                $pos = strpos($string, $char, $max_pos); // find the next instance of delimiter outside of wrapper

                if ($pos === false) { // if no delimiter is found 
                $pos = strlen($string); // use the length of the string as the next marker
                }

                return $pos;
        }
        
        static function split($string, $char, $account_wrapping = false) {
            $array = array(); // initialise an empty array to store split output 

            

            $pos = strpos($string, $char); // get initial postion of delimiter in string
            while (!($pos === false)) { // begin to loop through string until no instance of delimiter is found
                // echo $string . "<br/>";
                if ($account_wrapping) { // if the function has been called to ignored wrapped delimiters

                    $pos = self::check_wrappers($string, $pos, $char);

                }


                $temp = substr($string, 0, $pos); // copy the substring up to found marker 
                $array[] = $temp; // add it to result array
                $string = substr($string, $pos+1); // remove the substring from the initial string

                $pos = strpos($string, $char); // find next instance of delimiter
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

        static function fetch_table($id) {
            $tables = $GLOBALS['tables'];
        
            $tables->columns(array('*'));
            $tables->clear_where();
            $tables->add_where('id', $id, '=');
            $tables->select();
    
            if ($res = $tables->query()) {
                $table = $GLOBALS['tf']->create_table($res[0]);
                return $table;
            }

            return false;
        }
    }

?>